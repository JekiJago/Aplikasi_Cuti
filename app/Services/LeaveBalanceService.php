<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class LeaveBalanceService
{
    private const DEFAULT_ANNUAL_QUOTA = 12;
    private const MAX_CARRY_OVER_YEARS = 1; // current year + 1 previous year

    public function getAnnualLeaveSummary(User $user, ?int $referenceYear = null): array
    {
        $year  = $referenceYear ?? now()->year;
        $quota = (int) ($user->annual_leave_quota ?: self::DEFAULT_ANNUAL_QUOTA);
        if ($quota <= 0) {
            $quota = self::DEFAULT_ANNUAL_QUOTA;
        }
        $quota = min($quota, self::DEFAULT_ANNUAL_QUOTA);

        $details        = [];
        $totalAvailable = 0;

        for ($i = 0; $i <= self::MAX_CARRY_OVER_YEARS; $i++) {
            $targetYear = $year - $i;
            $used       = $this->getApprovedDaysForYear($user, 'tahunan', $targetYear);
            $available  = max(0, $quota - $used);

            $details[$targetYear] = [
                'label'     => $targetYear,
                'used'      => $used,
                'available' => $available,
            ];

            // hanya dua tahun terakhir yang dihitung
            if ($i <= self::MAX_CARRY_OVER_YEARS) {
                $totalAvailable += $available;
            }
        }

        krsort($details);

        return [
            'year'                   => $year,
            'quota_per_year'         => $quota,
            'total_available'        => $totalAvailable,
            'current_year_available' => $details[$year]['available'],
            'details'                => $details,
            'carry_over_expires_at'  => Carbon::create($year, 12, 31, 23, 59, 59),
        ];
    }

    private function getApprovedDaysForYear(User $user, string $leaveType, int $year): int
    {
        return $user->leaveRequests()
            ->where('leave_type', $leaveType)
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->sum('days');
    }
}


