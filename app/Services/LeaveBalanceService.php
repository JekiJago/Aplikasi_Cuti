<?php

namespace App\Services;

use App\Models\AnnualQuota;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveBalanceService
{
    private const DEFAULT_ANNUAL_QUOTA = 12;

    /**
     * NEW METHOD: Get annual leave summary with FIFO calculation (for consistency with admin)
     */
    public function getFixedAnnualLeaveSummary(User $user, int $year): array
    {
        // GUNAKAN FIFO BREAKDOWN YANG SAMA DENGAN ADMIN
        $fifoBreakdown = AnnualQuota::getFIFOBreakdown($user->id);
        $quotas = $fifoBreakdown['quotas'] ?? [];
        
        $previousYear = $year - 1;
        
        // Hitung total available sesuai FIFO
        $totalAvailable = 0;
        $details = [];
        
        // Ambil data untuk tahun yang diminta dan tahun sebelumnya
        $yearsToShow = [$previousYear, $year];
        
        foreach ($yearsToShow as $yearKey) {
            if (isset($quotas[$yearKey])) {
                $quotaData = $quotas[$yearKey];
                $available = $quotaData['remaining'] ?? 0;
                
                // Ambil data asli dari database
                $dbQuota = AnnualQuota::where('user_id', $user->id)
                    ->where('year', $yearKey)
                    ->first();
                
                $isExpired = $dbQuota ? $dbQuota->is_expired : ($yearKey < (date('Y') - 1));
                
                $details[$yearKey] = [
                    'label' => $yearKey,
                    'quota' => $quotaData['total'] ?? 0,
                    'used' => $quotaData['used'] ?? 0,
                    'available' => $available,
                    'is_active' => !$isExpired,
                    'is_expired' => $isExpired,
                ];
                
                if (!$isExpired) {
                    $totalAvailable += $available;
                }
            } else {
                // Default jika tidak ada data
                $defaultQuota = $user->annual_leave_quota ?? self::DEFAULT_ANNUAL_QUOTA;
                $isExpired = $yearKey < (date('Y') - 1);
                
                $details[$yearKey] = [
                    'label' => $yearKey,
                    'quota' => $defaultQuota,
                    'used' => 0,
                    'available' => $isExpired ? 0 : $defaultQuota,
                    'is_active' => !$isExpired,
                    'is_expired' => $isExpired,
                ];
                
                if (!$isExpired) {
                    $totalAvailable += $defaultQuota;
                }
            }
        }

        // FORMAT UNTUK KOMPATIBILITAS DENGAN VIEW LAMA
        return [
            'year' => $year,
            'total_available' => $totalAvailable,
            'current_year_available' => $details[$year]['available'] ?? 0,
            'previous_year_available' => $details[$previousYear]['available'] ?? 0,
            'quota_per_year' => $user->annual_leave_quota ?? self::DEFAULT_ANNUAL_QUOTA,
            'details' => $details,
            'fifo_breakdown' => $fifoBreakdown,
        ];
    }

    /**
     * Get annual leave summary for a user - DIPERBAIKI untuk konsisten dengan admin
     */
    public function getAnnualLeaveSummary(User $user, ?int $referenceYear = null): array
    {
        // PANGGIL METHOD BARU YANG SUDAH FIX
        $year = $referenceYear ?? now()->year;
        return $this->getFixedAnnualLeaveSummary($user, $year);
    }

    /**
     * Get annual leave summary for employee dashboard (optimized dan konsisten dengan admin)
     */
    public function getEmployeeDashboardBalance(int $userId): array
    {
        $user = User::findOrFail($userId);
        
        // Gunakan FIFO breakdown untuk konsistensi dengan admin
        $fifoBreakdown = AnnualQuota::getFIFOBreakdown($userId);
        $quotas = $fifoBreakdown['quotas'] ?? [];
        
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
        
        $prevYearData = $quotas[$previousYear] ?? ['total' => 0, 'used' => 0, 'remaining' => 0];
        $currentYearData = $quotas[$currentYear] ?? ['total' => 0, 'used' => 0, 'remaining' => 0];
        
        // Total aktif = sisa tahun lalu + sisa tahun ini (sesuai FIFO)
        $totalActiveBalance = $prevYearData['remaining'] + $currentYearData['remaining'];
        
        // Hitung penggunaan kuota tahun sebelumnya untuk cuti di tahun berjalan
        $usedFromPrevForCurrentYear = $fifoBreakdown['used_from_previous_for_current_year'] ?? 0;
        
        // Ambil data real dari database untuk perbandingan
        $prevYearQuota = AnnualQuota::where('user_id', $userId)
            ->where('year', $previousYear)
            ->first();
            
        $currentYearQuota = AnnualQuota::where('user_id', $userId)
            ->where('year', $currentYear)
            ->first();
        
        return [
            'total_available' => $totalActiveBalance,
            'previous_year' => [
                'year' => $previousYear,
                'quota' => $prevYearData['total'],
                'used' => $prevYearData['used'],
                'remaining' => $prevYearData['remaining'],
                'real_used_days' => $prevYearQuota->used_days ?? 0,
                'is_expired' => $prevYearQuota->is_expired ?? false,
            ],
            'current_year' => [
                'year' => $currentYear,
                'quota' => $currentYearData['total'],
                'used' => $currentYearData['used'],
                'remaining' => $currentYearData['remaining'],
                'real_used_days' => $currentYearQuota->used_days ?? 0,
                'is_expired' => $currentYearQuota->is_expired ?? false,
            ],
            'used_from_previous_for_current_year' => $usedFromPrevForCurrentYear,
            'fifo_breakdown' => $fifoBreakdown,
            'is_consistent' => true,
        ];
    }

    /**
     * Calculate working days per year for a leave period (excluding weekends and holidays)
     */
    public function calculateAnnualDaysPerYear(Carbon $start, Carbon $end): array
    {
        $holidays = Holiday::pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $daysPerYear = [];
        $date = $start->copy();

        while ($date->lte($end)) {
            if (
                !$date->isWeekend() &&
                !in_array($date->format('Y-m-d'), $holidays)
            ) {
                $year = $date->year;
                $daysPerYear[$year] = ($daysPerYear[$year] ?? 0) + 1;
            }
            $date->addDay();
        }

        return $daysPerYear;
    }

    /**
     * NEW METHOD: Deduct annual leave with priority system (sisa 2024 -> kuota 2025 -> kuota 2026)
     */
    public function deductAnnualLeaveWithPriority(int $userId, Carbon $startDate, Carbon $endDate): array
    {
        return DB::transaction(function () use ($userId, $startDate, $endDate) {
            // Calculate actual working days per year
            $daysPerYear = $this->calculateAnnualDaysPerYear($startDate, $endDate);
            
            if (empty($daysPerYear)) {
                throw new \Exception('Tidak ada hari kerja dalam periode cuti.');
            }

            $user = User::findOrFail($userId);
            if (method_exists($user, 'generateYearlyQuotas')) {
                $user->generateYearlyQuotas();
            }
            
            $quotaUsage = [];
            $remainingDaysTotal = 0;
            
            // Urutkan tahun dari yang paling lama (2024, 2025, 2026)
            ksort($daysPerYear);
            
            foreach ($daysPerYear as $year => $daysToDeduct) {
                Log::info("Processing year {$year}: {$daysToDeduct} days to deduct");
                
                $remainingDays = $daysToDeduct;
                
                // PRIORITAS 1: Gunakan sisa kuota tahun sebelumnya (2024) untuk hari di 2025
                if ($remainingDays > 0) {
                    $previousYear = $year - 1;
                    $previousQuota = AnnualQuota::where('user_id', $userId)
                        ->where('year', $previousYear)
                        ->where('is_expired', false)
                        ->whereColumn('used_days', '<', 'annual_quota')
                        ->lockForUpdate()
                        ->first();
                    
                    if ($previousQuota) {
                        $available = $previousQuota->annual_quota - $previousQuota->used_days;
                        $deduct = min($available, $remainingDays);
                        
                        if ($deduct > 0) {
                            $previousQuota->increment('used_days', $deduct);
                            $remainingDays -= $deduct;
                            
                            $quotaUsage[$previousYear] = ($quotaUsage[$previousYear] ?? 0) + $deduct;
                            Log::info("Deducted {$deduct} days from previous year {$previousYear} quota");
                        }
                    }
                }
                
                // PRIORITAS 2: Gunakan kuota tahun berjalan (2025)
                if ($remainingDays > 0) {
                    $currentQuota = AnnualQuota::where('user_id', $userId)
                        ->where('year', $year)
                        ->where('is_expired', false)
                        ->whereColumn('used_days', '<', 'annual_quota')
                        ->lockForUpdate()
                        ->first();
                    
                    if (!$currentQuota) {
                        $currentQuota = AnnualQuota::create([
                            'user_id' => $userId,
                            'year' => $year,
                            'annual_quota' => self::DEFAULT_ANNUAL_QUOTA,
                            'used_days' => 0,
                            'is_expired' => $year < (date('Y') - 1)
                        ]);
                    }
                    
                    $available = $currentQuota->annual_quota - $currentQuota->used_days;
                    $deduct = min($available, $remainingDays);
                    
                    if ($deduct > 0) {
                        $currentQuota->increment('used_days', $deduct);
                        $remainingDays -= $deduct;
                        
                        $quotaUsage[$year] = ($quotaUsage[$year] ?? 0) + $deduct;
                        Log::info("Deducted {$deduct} days from current year {$year} quota");
                    }
                }
                
                // PRIORITAS 3: Gunakan kuota tahun depan (2026) - JIKA PERLU
                if ($remainingDays > 0) {
                    $nextYear = $year + 1;
                    $nextQuota = AnnualQuota::firstOrCreate(
                        [
                            'user_id' => $userId,
                            'year' => $nextYear
                        ],
                        [
                            'annual_quota' => self::DEFAULT_ANNUAL_QUOTA,
                            'used_days' => 0,
                            'is_expired' => $nextYear < (date('Y') - 1)
                        ]
                    );
                    
                    if (!$nextQuota->is_expired) {
                        $available = $nextQuota->annual_quota - $nextQuota->used_days;
                        $deduct = min($available, $remainingDays);
                        
                        if ($deduct > 0) {
                            $nextQuota->increment('used_days', $deduct);
                            $remainingDays -= $deduct;
                            
                            $quotaUsage[$nextYear] = ($quotaUsage[$nextYear] ?? 0) + $deduct;
                            Log::info("Deducted {$deduct} days from next year {$nextYear} quota");
                        }
                    }
                }
                
                if ($remainingDays > 0) {
                    $remainingDaysTotal += $remainingDays;
                    Log::warning("Could not deduct {$remainingDays} days for year {$year}");
                }
            }
            
            if ($remainingDaysTotal > 0) {
                throw new \Exception("Kuota cuti tidak mencukupi. Masih kurang {$remainingDaysTotal} hari.");
            }
            
            return [
                'success' => true,
                'quota_usage' => $quotaUsage,
                'total_days' => array_sum($daysPerYear),
                'days_per_year' => $daysPerYear
            ];
        });
    }

    /**
     * OLD METHOD: Keep for backward compatibility
     */
    public function deductAnnualLeave(int $userId, Carbon $startDate, Carbon $endDate): void
    {
        $result = $this->deductAnnualLeaveWithPriority($userId, $startDate, $endDate);
        
        if (!$result['success']) {
            throw new \Exception('Gagal memotong kuota cuti.');
        }
    }

    /**
     * NEW METHOD: Validate if user has enough leave with priority rules
     */
    public function validateAnnualLeaveWithPriority(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $daysPerYear = $this->calculateAnnualDaysPerYear($startDate, $endDate);
        $totalDays = array_sum($daysPerYear);
        
        if ($totalDays <= 0) {
            return [
                'valid' => false,
                'message' => 'Tidak ada hari kerja dalam periode cuti yang diajukan.'
            ];
        }
        
        // Get all active quotas
        $currentYear = date('Y');
        $activeYears = [$currentYear - 1, $currentYear, $currentYear + 1];
        
        $quotas = AnnualQuota::where('user_id', $user->id)
            ->whereIn('year', $activeYears)
            ->orderBy('year', 'asc')
            ->get()
            ->keyBy('year');
        
        // Ensure all years have quota records
        foreach ($activeYears as $year) {
            if (!isset($quotas[$year])) {
                $quotas[$year] = (object) [
                    'annual_quota' => self::DEFAULT_ANNUAL_QUOTA,
                    'used_days' => 0,
                    'is_expired' => $year < ($currentYear - 1)
                ];
            }
        }
        
        // Simulate deduction with priority rules
        $simulation = [
            'quota_usage' => [],
            'remaining_by_year' => [],
            'total_needed' => $totalDays,
            'total_available' => 0
        ];
        
        ksort($daysPerYear);
        
        foreach ($daysPerYear as $year => $daysNeeded) {
            $remaining = $daysNeeded;
            $yearUsage = [];
            
            // Check previous year quota (for carryover)
            $prevYear = $year - 1;
            if (isset($quotas[$prevYear]) && !$quotas[$prevYear]->is_expired) {
                $prevAvailable = max(0, $quotas[$prevYear]->annual_quota - $quotas[$prevYear]->used_days);
                $usePrev = min($prevAvailable, $remaining);
                
                if ($usePrev > 0) {
                    $yearUsage[$prevYear] = $usePrev;
                    $remaining -= $usePrev;
                }
            }
            
            // Check current year quota
            $currentAvailable = max(0, $quotas[$year]->annual_quota - $quotas[$year]->used_days);
            $useCurrent = min($currentAvailable, $remaining);
            
            if ($useCurrent > 0) {
                $yearUsage[$year] = $useCurrent;
                $remaining -= $useCurrent;
            }
            
            // Check next year quota (only if current year exhausted)
            $nextYear = $year + 1;
            if ($remaining > 0 && isset($quotas[$nextYear])) {
                $nextAvailable = max(0, $quotas[$nextYear]->annual_quota - $quotas[$nextYear]->used_days);
                $useNext = min($nextAvailable, $remaining);
                
                if ($useNext > 0) {
                    $yearUsage[$nextYear] = $useNext;
                    $remaining -= $useNext;
                }
            }
            
            $simulation['quota_usage'][$year] = $yearUsage;
            $simulation['remaining_by_year'][$year] = $remaining;
        }
        
        // Calculate total available
        $totalAvailable = 0;
        foreach ($quotas as $quota) {
            if (!$quota->is_expired) {
                $totalAvailable += max(0, $quota->annual_quota - $quota->used_days);
            }
        }
        $simulation['total_available'] = $totalAvailable;
        
        // Check if any year still has remaining days
        $hasRemaining = false;
        foreach ($simulation['remaining_by_year'] as $year => $remaining) {
            if ($remaining > 0) {
                $hasRemaining = true;
                break;
            }
        }
        
        if ($hasRemaining || $totalAvailable < $totalDays) {
            $message = "Kuota cuti tidak mencukupi. ";
            if ($hasRemaining) {
                $message .= "Terdapat kekurangan pada tahun: " . implode(', ', array_keys(array_filter($simulation['remaining_by_year'])));
            } else {
                $message .= "Total kuota tersedia: {$totalAvailable} hari, dibutuhkan: {$totalDays} hari";
            }
            
            return [
                'valid' => false,
                'message' => $message,
                'simulation' => $simulation
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Kuota cuti mencukupi.',
            'simulation' => $simulation,
            'total_available' => $totalAvailable,
            'total_needed' => $totalDays,
            'remaining' => $totalAvailable - $totalDays
        ];
    }

    /**
     * Return annual leave (for cancellation) with priority reversal
     */
    public function returnAnnualLeave(int $userId, Carbon $startDate, Carbon $endDate): void
    {
        DB::transaction(function () use ($userId, $startDate, $endDate) {
            // Calculate actual working days per year
            $daysPerYear = $this->calculateAnnualDaysPerYear($startDate, $endDate);
            
            if (empty($daysPerYear)) {
                throw new \Exception('Tidak ada hari kerja dalam periode cuti.');
            }

            // Return in reverse order (newest first)
            foreach ($daysPerYear as $year => $daysToReturn) {
                if ($daysToReturn <= 0) {
                    continue;
                }

                // Get quotas with used days (newest first)
                $quotas = AnnualQuota::where('user_id', $userId)
                    ->where('year', '>=', $year - 1) // Include previous year
                    ->where('year', '<=', $year + 1) // Include next year
                    ->where('used_days', '>', 0)
                    ->orderBy('year', 'desc') // LIFO - newest first
                    ->lockForUpdate()
                    ->get();

                $remainingDays = $daysToReturn;

                foreach ($quotas as $quota) {
                    if ($remainingDays <= 0) {
                        break;
                    }

                    $used = $quota->used_days;
                    $canReturn = min($used, $remainingDays);

                    if ($canReturn > 0) {
                        $quota->decrement('used_days', $canReturn);
                        $remainingDays -= $canReturn;
                    }
                }

                if ($remainingDays > 0) {
                    Log::warning("Cannot return {$remainingDays} days for year {$year} for user {$userId}");
                }
            }
        });
    }

    /**
     * Get available annual leave days for user with priority consideration
     */
    public function getAvailableAnnualLeaveWithPriority(int $userId): array
    {
        $user = User::findOrFail($userId);
        
        // Gunakan FIFO breakdown untuk konsistensi
        $fifoBreakdown = AnnualQuota::getFIFOBreakdown($userId);
        $quotas = $fifoBreakdown['quotas'] ?? [];
        
        $available = [];
        $total = 0;
        
        $currentYear = date('Y');
        
        foreach ($quotas as $year => $quotaData) {
            if ($year >= ($currentYear - 1)) { // Hanya tahun aktif
                $remaining = $quotaData['remaining'] ?? 0;
                $available[$year] = [
                    'quota' => $quotaData['total'] ?? 0,
                    'used' => $quotaData['used'] ?? 0,
                    'remaining' => $remaining,
                    'is_expired' => $year < ($currentYear - 1)
                ];
                $total += $remaining;
            }
        }
        
        // Tambahkan tahun depan jika perlu
        $nextYear = $currentYear + 1;
        if (!isset($available[$nextYear])) {
            $nextQuota = AnnualQuota::where('user_id', $userId)
                ->where('year', $nextYear)
                ->first();
                
            if ($nextQuota) {
                $remaining = max(0, $nextQuota->annual_quota - $nextQuota->used_days);
                $available[$nextYear] = [
                    'quota' => $nextQuota->annual_quota,
                    'used' => $nextQuota->used_days,
                    'remaining' => $remaining,
                    'is_expired' => $nextQuota->is_expired
                ];
            }
        }
        
        ksort($available);
        
        return [
            'total_available' => $total,
            'breakdown' => $available,
            'priority_order' => array_keys($available),
            'fifo_breakdown' => $fifoBreakdown
        ];
    }

    /**
     * Simple deduct method (for backward compatibility)
     */
    public function deductAnnualLeaveSimple(int $userId, int $days): void
    {
        DB::transaction(function () use ($userId, $days) {
            $user = User::findOrFail($userId);
            if (method_exists($user, 'generateYearlyQuotas')) {
                $user->generateYearlyQuotas();
            }

            $remainingDays = $days;

            // Ambil kuota TERLAMA yang masih aktif
            $quotas = AnnualQuota::where('user_id', $userId)
                ->where('is_expired', false)
                ->whereColumn('used_days', '<', 'annual_quota')
                ->orderBy('year', 'asc') // FIFO
                ->lockForUpdate()
                ->get();

            foreach ($quotas as $quota) {
                if ($remainingDays <= 0) {
                    break;
                }

                $available = $quota->annual_quota - $quota->used_days;

                if ($available >= $remainingDays) {
                    $quota->increment('used_days', $remainingDays);
                    $remainingDays = 0;
                } else {
                    $quota->increment('used_days', $available);
                    $remainingDays -= $available;
                }
            }

            if ($remainingDays > 0) {
                throw new \Exception('Kuota cuti tahunan tidak mencukupi.');
            }
        });
    }

    /**
     * Update otomatis kuota yang hangus
     */
    public function refreshExpiredQuotas(): void
    {
        $currentYear = date('Y');

        AnnualQuota::where('year', '<', ($currentYear - 1))
            ->where('is_expired', false)
            ->update(['is_expired' => true]);
    }

    /**
     * Tambah kuota tahunan untuk pengguna
     */
    public function addAnnualQuota(int $userId, int $year, int $quotaDays): AnnualQuota
    {
        return AnnualQuota::updateOrCreate(
            [
                'user_id' => $userId,
                'year' => $year,
            ],
            [
                'annual_quota' => $quotaDays,
                'is_expired' => $year < (date('Y') - 1),
            ]
        );
    }

    /**
     * Reset used_days untuk tahun tertentu (untuk admin)
     */
    public function resetUsedDays(int $userId, int $year): void
    {
        AnnualQuota::where('user_id', $userId)
            ->where('year', $year)
            ->update(['used_days' => 0]);
    }

    /**
     * Get available annual leave days for user
     */
    public function getAvailableAnnualLeave(int $userId): int
    {
        $user = User::findOrFail($userId);
        if (method_exists($user, 'generateYearlyQuotas')) {
            $user->generateYearlyQuotas();
        }

        return AnnualQuota::where('user_id', $userId)
            ->where('is_expired', false)
            ->sum(DB::raw('GREATEST(annual_quota - used_days, 0)'));
    }

    /**
     * Calculate total available annual leave for user
     */
    public function calculateTotalAvailable(int $userId): array
    {
        $user = User::findOrFail($userId);
        if (method_exists($user, 'generateYearlyQuotas')) {
            $user->generateYearlyQuotas();
        }

        $quotas = AnnualQuota::where('user_id', $userId)
            ->where('is_expired', false)
            ->orderBy('year', 'asc')
            ->get();

        $totalAvailable = 0;
        $details = [];

        foreach ($quotas as $quota) {
            $available = max(0, $quota->annual_quota - $quota->used_days);
            
            $details[] = [
                'year' => $quota->year,
                'quota' => $quota->annual_quota,
                'used' => $quota->used_days,
                'available' => $available,
                'is_expired' => $quota->is_expired,
            ];

            $totalAvailable += $available;
        }

        return [
            'total_available' => $totalAvailable,
            'details' => $details,
        ];
    }

    /**
     * Helper function: Cek apakah kuota expired berdasarkan tahun
     */
    private function isQuotaExpired(int $year): bool
    {
        $currentYear = date('Y');
        return $year < ($currentYear - 1);
    }

    /**
     * Validate if user has enough leave balance
     */
    public function validateLeaveBalance(int $userId, Carbon $startDate, Carbon $endDate): bool
    {
        $daysPerYear = $this->calculateAnnualDaysPerYear($startDate, $endDate);
        $totalDaysNeeded = array_sum($daysPerYear);
        
        $availableBalance = $this->getAvailableAnnualLeave($userId);
        
        return $availableBalance >= $totalDaysNeeded;
    }

    /**
     * Validate specific leave type balance
     */
    public function validateSpecificLeaveBalance(User $user, string $leaveType, int $days): bool
    {
        switch ($leaveType) {
            case 'tahunan':
                return $this->getAvailableAnnualLeave($user->id) >= $days;
                
            case 'sakit':
            case 'cuti_sakit':
                return ($user->getRemainingLeaveQuota('sick') ?? 540 - ($user->sick_leave_used_days ?? 0)) >= $days;
                
            case 'penting':
            case 'urusan_penting':
                return ($user->getRemainingLeaveQuota('important') ?? 30 - ($user->important_leave_used_days ?? 0)) >= $days;
                
            case 'besar':
            case 'cuti_besar':
                return ($user->getRemainingLeaveQuota('big') ?? 90) >= $days && $user->canTakeBigLeave();
                
            case 'non_aktif':
            case 'cuti_non_aktif':
                return ($user->getRemainingLeaveQuota('non_active') ?? 1) >= $days;
                
            case 'melahirkan':
            case 'cuti_bersalin':
                return ($user->getRemainingLeaveQuota('maternity') ?? (3 - ($user->maternity_leave_used_count ?? 0)) * 90) >= $days;
                
            case 'paternal':
                return ($user->getRemainingLeaveQuota('paternity') ?? 0) >= $days;
                
            case 'pernikahan':
                return ($user->getRemainingLeaveQuota('marriage') ?? 0) >= $days;
                
            case 'dinas':
            case 'lainnya':
                return true; // No quota limit
                
            default:
                return false;
        }
    }

    /**
     * Deduct specific leave type quota
     */
    public function deductSpecificLeave(User $user, string $leaveType, int $days): void
    {
        $typeMap = [
            'sakit' => 'sick',
            'cuti_sakit' => 'sick',
            'penting' => 'important',
            'urusan_penting' => 'important',
            'besar' => 'big',
            'cuti_besar' => 'big',
            'non_aktif' => 'non_active',
            'cuti_non_aktif' => 'non_active',
        ];

        if (isset($typeMap[$leaveType])) {
            $field = $typeMap[$leaveType] . '_leave_used_days';
            if (isset($user->$field)) {
                $user->increment($field, $days);
            }
        }
    }

    /**
     * Return specific leave type quota (for cancellation)
     */
    public function returnSpecificLeave(User $user, string $leaveType, int $days): void
    {
        $typeMap = [
            'sakit' => 'sick',
            'cuti_sakit' => 'sick',
            'penting' => 'important',
            'urusan_penting' => 'important',
            'besar' => 'big',
            'cuti_besar' => 'big',
            'non_aktif' => 'non_active',
            'cuti_non_aktif' => 'non_active',
        ];

        if (isset($typeMap[$leaveType])) {
            $field = $typeMap[$leaveType] . '_leave_used_days';
            if (isset($user->$field)) {
                $user->decrement($field, $days);
            }
        }
    }

    /**
     * Get leave balance breakdown
     */
    public function getBalanceBreakdown(int $userId): array
    {
        $user = User::findOrFail($userId);
        
        // Pastikan kuota ada
        if (method_exists($user, 'generateYearlyQuotas')) {
            $user->generateYearlyQuotas();
        }

        $quotas = AnnualQuota::where('user_id', $userId)
            ->where('is_expired', false)
            ->orderBy('year', 'asc')
            ->get();

        $breakdown = [];
        $totalAvailable = 0;
        $totalQuota = 0;
        $totalUsed = 0;

        foreach ($quotas as $quota) {
            $available = max(0, $quota->annual_quota - $quota->used_days);
            $breakdown[] = [
                'year' => $quota->year,
                'annual_quota' => $quota->annual_quota,
                'used_days' => $quota->used_days,
                'available_days' => $available,
                'is_expired' => $quota->is_expired,
            ];

            $totalAvailable += $available;
            $totalQuota += $quota->annual_quota;
            $totalUsed += $quota->used_days;
        }

        return [
            'breakdown' => $breakdown,
            'summary' => [
                'total_quota' => $totalQuota,
                'total_used' => $totalUsed,
                'total_available' => $totalAvailable,
                'active_years' => count($breakdown),
            ],
        ];
    }

    /**
     * Get comprehensive leave balance for user
     */
    public function getComprehensiveBalance(User $user): array
    {
        $annualSummary = $this->getAnnualLeaveSummary($user);
        $annualBreakdown = $this->getBalanceBreakdown($user->id);
        
        return [
            'annual_leave' => $annualSummary,
            'annual_breakdown' => $annualBreakdown,
            'user_info' => [
                'name' => $user->name,
                'employee_id' => $user->employee_id,
                'department' => $user->department,
                'hire_date' => $user->hire_date?->format('Y-m-d'),
                'years_of_service' => $user->hire_date ? now()->diffInYears($user->hire_date) : 0,
            ],
            'calculated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Initialize annual quotas for all users (admin function)
     */
    public function initializeAllUserQuotas(): array
    {
        $users = User::where('role', 'employee')->get();
        $results = [
            'total_users' => $users->count(),
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($users as $user) {
            try {
                if (method_exists($user, 'generateYearlyQuotas')) {
                    $user->generateYearlyQuotas();
                }
                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}