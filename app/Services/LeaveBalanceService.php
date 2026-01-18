<?php

namespace App\Services;

use App\Models\User;
use App\Models\Cuti;

class LeaveBalanceService
{
    /**
     * Get leave balance for user
     */
    public function getLeaveBalance(User $user): array
    {
        $pegawai = $user->pegawai;
        
        if (!$pegawai) {
            return [
                'kuota_tahunan' => 0,
                'cuti_dipakai' => 0,
                'sisa_cuti' => 0,
            ];
        }

        $cuti = $pegawai->cuti;
        
        if (!$cuti) {
            return [
                'kuota_tahunan' => 12,
                'cuti_dipakai' => 0,
                'sisa_cuti' => 12,
            ];
        }

        return [
            'kuota_tahunan' => $cuti->kuota_tahunan,
            'cuti_dipakai' => $cuti->cuti_dipakai,
            'sisa_cuti' => $cuti->remaining_days,
        ];
    }

    /**
     * Deduct leave days for user
     */
    public function deductLeave(User $user, int $days): void
    {
        $pegawai = $user->pegawai;
        
        if (!$pegawai) {
            throw new \Exception('Pegawai tidak ditemukan untuk user ini.');
        }

        $cuti = $pegawai->cuti;
        
        if (!$cuti) {
            throw new \Exception('Data cuti tidak ditemukan untuk pegawai ini.');
        }

        $sisa = $cuti->kuota_tahunan - $cuti->cuti_dipakai;
        
        if ($sisa < $days) {
            throw new \Exception("Kuota cuti tidak mencukupi. Sisa: {$sisa} hari, dibutuhkan: {$days} hari");
        }

        $cuti->increment('cuti_dipakai', $days);
    }

    /**
     * Return leave days for user (cancellation)
     */
    public function returnLeave(User $user, int $days): void
    {
        $pegawai = $user->pegawai;
        
        if (!$pegawai) {
            throw new \Exception('Pegawai tidak ditemukan untuk user ini.');
        }

        $cuti = $pegawai->cuti;
        
        if (!$cuti) {
            throw new \Exception('Data cuti tidak ditemukan untuk pegawai ini.');
        }

        $cuti->decrement('cuti_dipakai', $days);
    }

    /**
     * Get fixed annual leave summary for a specific year
     */
    public function getFixedAnnualLeaveSummary(User $user, int $year): array
    {
        $pegawai = $user->pegawai;
        
        if (!$pegawai) {
            return [
                'current_year_available' => 0,
                'details' => [],
            ];
        }

        $cuti = $pegawai->cuti;
        
        if (!$cuti) {
            return [
                'current_year_available' => 12,
                'details' => [
                    $year => [
                        'quota' => 12,
                        'used' => 0,
                    ],
                ],
            ];
        }

        $available = $cuti->kuota_tahunan - $cuti->cuti_dipakai;

        return [
            'current_year_available' => $available,
            'details' => [
                $year => [
                    'quota' => $cuti->kuota_tahunan,
                    'used' => $cuti->cuti_dipakai,
                ],
            ],
        ];
    }

    /**
     * Get available annual leave with priority
     */
    public function getAvailableAnnualLeaveWithPriority(int $userId): array
    {
        $user = User::find($userId);
        
        if (!$user || !$user->pegawai || !$user->pegawai->cuti) {
            return [
                'total_available' => 0,
                'breakdown' => [],
            ];
        }

        $cuti = $user->pegawai->cuti;
        $available = $cuti->kuota_tahunan - $cuti->cuti_dipakai;

        return [
            'total_available' => $available,
            'breakdown' => [
                'current_year' => [
                    'quota' => $cuti->kuota_tahunan,
                    'used' => $cuti->cuti_dipakai,
                    'available' => $available,
                ],
            ],
        ];
    }

    /**
     * Validate annual leave with priority
     */
    public function validateAnnualLeaveWithPriority($user, $start, $end): array
    {
        // Simple validation - just check if there's enough quota
        if (!$user->pegawai || !$user->pegawai->cuti) {
            return [
                'valid' => false,
                'message' => 'Data cuti tidak ditemukan',
            ];
        }

        $cuti = $user->pegawai->cuti;
        $available = $cuti->kuota_tahunan - $cuti->cuti_dipakai;

        // Hitung hari kerja (exclude weekend)
        $startDate = \Carbon\Carbon::parse($start);
        $endDate = \Carbon\Carbon::parse($end);
        $workDays = 0;

        $current = $startDate->copy();
        while ($current <= $endDate) {
            if ($current->isWeekday()) { // Monday-Friday
                $workDays++;
            }
            $current->addDay();
        }

        if ($available < $workDays) {
            return [
                'valid' => false,
                'message' => "Kuota tidak cukup. Anda membutuhkan {$workDays} hari kerja, tersedia {$available} hari",
            ];
        }

        return [
            'valid' => true,
            'days_needed' => $workDays,
            'available' => $available,
        ];
    }
}
