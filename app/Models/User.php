<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'employee_id',
        'password',
        'role',
        'login_type',
        'position',
        'department',
        'gender',
        'hire_date',
        'annual_leave_quota',
        // Field kuota cuti lifetime
        'sick_leave_quota',
        'personal_leave_quota',
        'important_leave_quota',
        'big_leave_quota',
        'non_active_leave_quota',
        'maternity_leave_quota',
        'paternity_leave_quota',
        'marriage_leave_quota',
        // Field tracking penggunaan
        'important_leave_used_days',
        'sick_leave_used_days',
        'big_leave_used_days',
        'big_leave_last_used_at',
        'non_active_leave_used_days',
        'maternity_leave_used_count',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'big_leave_last_used_at' => 'date',
        'email_verified_at' => 'datetime',
    ];

    /**
     * RELASI KE TABEL leave_requests
     */
    public function leaveRequests()
    {
        return $this->hasMany(\App\Models\LeaveRequest::class, 'user_id');
    }

    /**
     * Relasi ke annual quotas (KUOTA PER TAHUN)
     */
    public function annualQuotas()
    {
        return $this->hasMany(AnnualQuota::class);
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah pegawai biasa
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /**
     * Get quota for specific year
     */
    public function getYearlyQuota($year): int
    {
        $quota = $this->annualQuotas()->where('year', $year)->first();
        return $quota ? $quota->annual_quota : ($this->annual_leave_quota ?? 12);
    }

    /**
     * Get used days for specific year
     */
    public function getYearlyUsedDays($year): int
    {
        $quota = $this->annualQuotas()->where('year', $year)->first();
        return $quota ? $quota->used_days : 0;
    }

    /**
     * Get current year quota
     */
    public function getCurrentYearQuotaAttribute(): int
    {
        $currentYear = date('Y');
        return $this->getYearlyQuota($currentYear);
    }

    /**
     * Get current year used days
     */
    public function getCurrentYearUsedDaysAttribute(): int
    {
        $currentYear = date('Y');
        return $this->getYearlyUsedDays($currentYear);
    }

    /**
     * Get current year remaining days
     */
    public function getCurrentYearRemainingDaysAttribute(): int
    {
        return max($this->current_year_quota - $this->current_year_used_days, 0);
    }

    /**
     * Hitung sisa kuota berdasarkan jenis cuti (lifetime)
     */
    public function getRemainingLeaveQuota($type): int
    {
        $quotaMap = [
            'sick' => $this->sick_leave_quota ?? 12,
            'personal' => $this->personal_leave_quota ?? 12,
            'important' => $this->important_leave_quota ?? 30,
            'big' => $this->big_leave_quota ?? 90,
            'non_active' => $this->non_active_leave_quota ?? 365,
            'maternity' => $this->maternity_leave_quota ?? 90,
            'paternity' => $this->paternity_leave_quota ?? 14,
            'marriage' => $this->marriage_leave_quota ?? 3,
        ];

        $usedMap = [
            'sick' => $this->sick_leave_used_days ?? 0,
            'important' => $this->important_leave_used_days ?? 0,
            'big' => $this->big_leave_used_days ?? 0,
            'non_active' => $this->non_active_leave_used_days ?? 0,
        ];

        $quota = $quotaMap[$type] ?? 0;
        $used = $usedMap[$type] ?? 0;

        return max($quota - $used, 0);
    }

    /**
     * Generate yearly quotas if not exist
     */
    public function generateYearlyQuotas(): void
    {
        $startYear = $this->hire_date
            ? Carbon::parse($this->hire_date)->year
            : now()->year;

        $currentYear = now()->year;

        for ($year = $startYear; $year <= $currentYear; $year++) {
            AnnualQuota::firstOrCreate(
                [
                    'user_id' => $this->id,
                    'year' => $year,
                ],
                [
                    'annual_quota' => $this->annual_leave_quota ?? 12,
                    'used_days' => 0,
                    'is_expired' => $year < ($currentYear - 1),
                ]
            );
        }
    }

    /**
     * Get active annual quotas (current and previous year)
     */
    public function getActiveAnnualQuotas()
    {
        $currentYear = date('Y');
        return $this->annualQuotas()
            ->whereIn('year', [$currentYear, $currentYear - 1])
            ->where('is_expired', false)
            ->orderBy('year', 'asc')
            ->get();
    }

    /**
     * Get total available annual leave days
     */
    public function getTotalAnnualLeaveAvailable(): int
    {
        return $this->getActiveAnnualQuotas()->sum(function ($quota) {
            return $quota->annual_quota - $quota->used_days;
        });
    }

    /**
     * Cek status kuota berdasarkan sistem 2 tahun
     */
    public function getQuotaStatus($year): string
    {
        $currentYear = date('Y');
        
        if ($year < ($currentYear - 1)) {
            return 'expired'; // Sudah lewat 2 tahun
        } elseif ($year >= ($currentYear - 1) && $year <= $currentYear) {
            return 'active'; // Masih dalam 2 tahun
        } else {
            return 'future'; // Tahun depan
        }
    }

    /**
     * Get expiration date untuk kuota tertentu
     */
    public function getQuotaExpirationDate($year): Carbon
    {
        return Carbon::create($year + 1, 12, 31, 23, 59, 59);
    }

    /**
     * Get active until date untuk kuota tertentu (berdasarkan sistem 2 tahun) - DITAMBAHKAN
     */
    public function getActiveUntil($year): Carbon
    {
        // Kuota aktif sampai akhir tahun berikutnya
        return Carbon::create($year + 1, 12, 31, 23, 59, 59);
    }

    /**
     * Get tahun hangus untuk kuota tertentu - DITAMBAHKAN
     */
    public function getHangusYear($year): int
    {
        // Kuota akan hangus 2 tahun setelah tahun berakhir
        // Contoh: kuota 2024 aktif sampai 2025, hangus 2026
        return $year + 2;
    }

    /**
     * Update used days for specific leave type
     */
    public function incrementUsedDays($type, $days): void
    {
        $fieldMap = [
            'sick' => 'sick_leave_used_days',
            'important' => 'important_leave_used_days',
            'big' => 'big_leave_used_days',
            'non_active' => 'non_active_leave_used_days',
        ];

        if (isset($fieldMap[$type])) {
            $field = $fieldMap[$type];
            $this->increment($field, $days);
            
            if ($type === 'big') {
                $this->update(['big_leave_last_used_at' => now()]);
            }
        }
    }

    /**
     * Decrement used days for specific leave type (for cancellation)
     */
    public function decrementUsedDays($type, $days): void
    {
        $fieldMap = [
            'sick' => 'sick_leave_used_days',
            'important' => 'important_leave_used_days',
            'big' => 'big_leave_used_days',
            'non_active' => 'non_active_leave_used_days',
        ];

        if (isset($fieldMap[$type])) {
            $field = $fieldMap[$type];
            $this->decrement($field, $days);
        }
    }

    /**
     * Check if user can take big leave (3 years interval)
     */
    public function canTakeBigLeave(): bool
    {
        if (!$this->big_leave_last_used_at) {
            return true;
        }

        $lastUsed = Carbon::parse($this->big_leave_last_used_at);
        $threeYearsAgo = now()->subYears(3);
        
        return $lastUsed->lt($threeYearsAgo);
    }

    /**
     * Check if big leave is available
     */
    public function isBigLeaveAvailable(): bool
    {
        $remaining = $this->getRemainingLeaveQuota('big');
        return $remaining > 0 && $this->canTakeBigLeave();
    }

    /**
     * Get years with available annual quotas
     */
    public function getAvailableAnnualQuotaYears(): array
    {
        return $this->annualQuotas()
            ->where('is_expired', false)
            ->whereColumn('used_days', '<', 'annual_quota')
            ->pluck('year')
            ->toArray();
    }

    /**
     * Get annual leave summary
     */
    public function getAnnualLeaveSummary($referenceYear = null): array
    {
        $currentYear = $referenceYear ?? now()->year;
        
        // Pastikan quota tergenerate
        $this->generateYearlyQuotas();

        $quotas = $this->annualQuotas()
            ->whereIn('year', [$currentYear, $currentYear - 1])
            ->orderBy('year', 'asc')
            ->get();

        $totalAvailable = 0;
        $details = [];

        foreach ($quotas as $quota) {
            $available = max(0, $quota->annual_quota - $quota->used_days);
            $isActive = !$quota->is_expired;

            $details[$quota->year] = [
                'label' => $quota->year,
                'quota' => $quota->annual_quota,
                'used' => $quota->used_days,
                'available' => $available,
                'is_active' => $isActive,
                'is_expired' => $quota->is_expired,
            ];

            if ($isActive) {
                $totalAvailable += $available;
            }
        }

        krsort($details);

        return [
            'year' => $currentYear,
            'total_available' => $totalAvailable,
            'current_year_available' => $details[$currentYear]['available'] ?? 0,
            'previous_year_available' => $details[$currentYear - 1]['available'] ?? 0,
            'details' => $details,
            'expires_at' => Carbon::create($currentYear, 12, 31, 23, 59, 59),
        ];
    }

    /**
     * Get all leave quotas summary
     */
    public function getAllLeaveQuotas(): array
    {
        return [
            'annual' => $this->getTotalAnnualLeaveAvailable(),
            'sick' => $this->getRemainingLeaveQuota('sick'),
            'personal' => $this->getRemainingLeaveQuota('personal'),
            'important' => $this->getRemainingLeaveQuota('important'),
            'big' => $this->getRemainingLeaveQuota('big'),
            'non_active' => $this->getRemainingLeaveQuota('non_active'),
            'maternity' => $this->getRemainingLeaveQuota('maternity'),
            'paternity' => $this->getRemainingLeaveQuota('paternity'),
            'marriage' => $this->getRemainingLeaveQuota('marriage'),
        ];
    }
}