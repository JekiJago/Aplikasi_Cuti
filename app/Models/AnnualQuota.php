<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnnualQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'annual_quota',
        'used_days',
        'is_expired'
    ];

    protected $casts = [
        'is_expired' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRemainingDaysAttribute()
    {
        return max($this->annual_quota - $this->used_days, 0);
    }

    /**
     * Get total active leave balance for user
     */
    public static function getActiveLeaveBalance($userId)
    {
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
        
        return self::where('user_id', $userId)
            ->whereIn('year', [$previousYear, $currentYear])
            ->where('is_expired', false)
            ->where('remaining_days', '>', 0)
            ->sum('remaining_days');
    }

    /**
     * Get active leave details by year with FIFO simulation
     */
    public static function getActiveLeaveDetails($userId)
    {
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
        
        // Ambil kuota
        $quotas = self::where('user_id', $userId)
            ->whereIn('year', [$previousYear, $currentYear])
            ->orderBy('year', 'asc')
            ->get();
            
        // Ambil semua cuti approved untuk simulasi FIFO
        $approvedLeaves = LeaveRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('leave_type', 'tahunan')
            ->orderBy('start_date', 'asc')
            ->get();
            
        // Simulasi ulang penggunaan dengan FIFO
        $tempUsed = [
            $previousYear => 0,
            $currentYear => 0
        ];
        
        foreach ($approvedLeaves as $leave) {
            $workDays = self::calculateWorkDaysForLeave($leave);
            self::simulateFIFOAllocation($quotas, $tempUsed, $workDays, $leave->start_date, $leave->end_date);
        }
        
        // Format hasil
        $result = [];
        foreach ($quotas as $quota) {
            if (in_array($quota->year, [$previousYear, $currentYear])) {
                $tempUsedValue = $tempUsed[$quota->year] ?? 0;
                $remaining = max($quota->annual_quota - $tempUsedValue, 0);
                
                $result[] = [
                    'year' => $quota->year,
                    'total' => $quota->annual_quota,
                    'used' => $tempUsedValue,
                    'remaining' => $remaining,
                    'is_expired' => $quota->is_expired,
                    'is_active' => !$quota->is_expired && $quota->year >= $previousYear,
                    'real_used_days' => $quota->used_days // nilai asli dari database
                ];
            }
        }
        
        return $result;
    }

    /**
     * Calculate work days for a leave request
     */
    private static function calculateWorkDaysForLeave($leave): int
    {
        $start = Carbon::parse($leave->start_date);
        $end = Carbon::parse($leave->end_date);
        
        if ($leave->leave_type === 'tahunan') {
            return LeaveRequest::workingDaysBetween($start, $end);
        }
        
        return $start->diffInDays($end) + 1;
    }

    /**
     * Simulate FIFO allocation for work days
     */
    private static function simulateFIFOAllocation($quotas, &$tempUsed, $workDays, $startDate, $endDate): void
    {
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $holidays = Holiday::pluck('date')->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))->toArray();
        
        while ($current <= $end) {
            // Skip weekend and holidays
            if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidays)) {
                $year = $current->year;
                $allocated = false;
                
                // Try to allocate from oldest quota first (FIFO)
                foreach ($quotas->sortBy('year') as $quota) {
                    if ($quota->year <= $year && !$quota->is_expired) {
                        $alreadyUsed = $tempUsed[$quota->year] ?? 0;
                        $available = $quota->annual_quota - $alreadyUsed;
                        
                        if ($available > 0) {
                            $tempUsed[$quota->year] = $alreadyUsed + 1;
                            $allocated = true;
                            break;
                        }
                    }
                }
                
                // If not allocated, try any available quota
                if (!$allocated) {
                    foreach ($quotas->sortBy('year') as $quota) {
                        if (!$quota->is_expired) {
                            $alreadyUsed = $tempUsed[$quota->year] ?? 0;
                            $available = $quota->annual_quota - $alreadyUsed;
                            
                            if ($available > 0) {
                                $tempUsed[$quota->year] = $alreadyUsed + 1;
                                break;
                            }
                        }
                    }
                }
            }
            
            $current->addDay();
        }
    }

    /**
     * NEW: Get detailed FIFO breakdown for specific user
     */
    public static function getFIFOBreakdown($userId): array
    {
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
        
        // Get quotas
        $quotas = self::where('user_id', $userId)
            ->whereIn('year', [$previousYear, $currentYear])
            ->orderBy('year', 'asc')
            ->get()
            ->keyBy('year');
            
        // Get all approved annual leaves
        $leaves = LeaveRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('leave_type', 'tahunan')
            ->orderBy('start_date', 'asc')
            ->get();
            
        // Initialize counters
        $counters = [
            $previousYear => ['total' => 0, 'used' => 0, 'remaining' => 0],
            $currentYear => ['total' => 0, 'used' => 0, 'remaining' => 0]
        ];
        
        // Get initial values
        foreach ($quotas as $year => $quota) {
            $counters[$year]['total'] = $quota->annual_quota;
            $counters[$year]['used'] = 0; // Will calculate with FIFO
            $counters[$year]['remaining'] = $quota->annual_quota;
        }
        
        // Process each leave with FIFO
        $fifoDetails = [];
        foreach ($leaves as $leave) {
            $days = self::calculateWorkDaysForLeave($leave);
            $allocation = self::allocateDaysFIFO($counters, $days, $leave->start_date, $leave->end_date);
            $fifoDetails[] = $allocation;
        }
        
        // Calculate remaining after FIFO
        foreach ($counters as $year => &$counter) {
            $counter['remaining'] = $counter['total'] - $counter['used'];
        }
        
        // Calculate used from previous year for current year leaves
        $usedFromPrevForCurrentYear = 0;
        foreach ($fifoDetails as $detail) {
            if (isset($detail['allocations'][$previousYear]) && 
                Carbon::parse($detail['start_date'])->year == $currentYear) {
                $usedFromPrevForCurrentYear += $detail['allocations'][$previousYear];
            }
        }
        
        return [
            'quotas' => $counters,
            'total_active_balance' => $counters[$previousYear]['remaining'] + $counters[$currentYear]['remaining'],
            'used_from_previous_for_current_year' => $usedFromPrevForCurrentYear,
            'fifo_details' => $fifoDetails
        ];
    }

    /**
     * Allocate days with FIFO rules
     */
    private static function allocateDaysFIFO(&$counters, $totalDays, $startDate, $endDate): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $holidays = Holiday::pluck('date')->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))->toArray();
        
        $allocation = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'allocations' => [],
            'daily_breakdown' => []
        ];
        
        $current = $start->copy();
        while ($current <= $end) {
            if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidays)) {
                $allocated = false;
                $year = $current->year;
                
                // Try previous year first
                $prevYear = $year - 1;
                if (isset($counters[$prevYear]) && $counters[$prevYear]['remaining'] > 0) {
                    $counters[$prevYear]['used']++;
                    $counters[$prevYear]['remaining']--;
                    $allocation['allocations'][$prevYear] = ($allocation['allocations'][$prevYear] ?? 0) + 1;
                    $allocation['daily_breakdown'][] = [
                        'date' => $current->format('Y-m-d'),
                        'year' => $current->year,
                        'allocated_from' => $prevYear
                    ];
                    $allocated = true;
                }
                
                // Try current year
                if (!$allocated && isset($counters[$year]) && $counters[$year]['remaining'] > 0) {
                    $counters[$year]['used']++;
                    $counters[$year]['remaining']--;
                    $allocation['allocations'][$year] = ($allocation['allocations'][$year] ?? 0) + 1;
                    $allocation['daily_breakdown'][] = [
                        'date' => $current->format('Y-m-d'),
                        'year' => $current->year,
                        'allocated_from' => $year
                    ];
                    $allocated = true;
                }
                
                // Try any available year (shouldn't happen with valid data)
                if (!$allocated) {
                    foreach ($counters as $allocYear => $counter) {
                        if ($counter['remaining'] > 0) {
                            $counters[$allocYear]['used']++;
                            $counters[$allocYear]['remaining']--;
                            $allocation['allocations'][$allocYear] = ($allocation['allocations'][$allocYear] ?? 0) + 1;
                            $allocation['daily_breakdown'][] = [
                                'date' => $current->format('Y-m-d'),
                                'year' => $current->year,
                                'allocated_from' => $allocYear
                            ];
                            break;
                        }
                    }
                }
            }
            
            $current->addDay();
        }
        
        return $allocation;
    }

    /**
     * Cek apakah kuota sudah hangus (berdasarkan sistem 2 tahun)
     */
    public function isHangus()
    {
        $currentYear = date('Y');
        return $this->year < ($currentYear - 1);
    }

    /**
     * Cek apakah kuota masih aktif
     */
    public function isActive()
    {
        $currentYear = date('Y');
        return $this->year >= ($currentYear - 1) && $this->year <= $currentYear;
    }

    /**
     * Tahun kuota akan hangus
     */
    public function getHangusYear()
    {
        return $this->year + 2;
    }

    /**
     * Tanggal aktif sampai
     */
    public function getActiveUntil()
    {
        return ($this->year + 1) . '-12-31';
    }
}