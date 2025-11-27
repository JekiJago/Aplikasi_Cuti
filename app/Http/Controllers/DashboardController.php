<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\LeaveBalanceService;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function __construct(
        private LeaveBalanceService $leaveBalanceService
    ) {
    }

    public function index()
    {
        $user = auth()->user();

        if ($user?->isAdmin()) {
            $stats         = $this->adminStats();
            $monthlyStats  = $this->monthlyChartData();
            $recentLeaves  = LeaveRequest::with('user')->latest()->limit(5)->get();
            $lowLeaveEmployees = $this->lowLeaveEmployees();

            return view('dashboard.admin', compact('stats', 'monthlyStats', 'recentLeaves', 'lowLeaveEmployees'));
        }

        $annualSummary = $this->leaveBalanceService->getAnnualLeaveSummary($user);
        $recentLeaves  = $user?->leaveRequests()->latest()->limit(5)->get() ?? collect();

        return view('dashboard', compact('annualSummary', 'recentLeaves'));
    }

    private function adminStats(): array
    {
        return [
            'employees' => User::where('role', 'employee')->count(),
            'pending'   => LeaveRequest::where('status', 'pending')->count(),
            'approved'  => LeaveRequest::where('status', 'approved')->count(),
            'rejected'  => LeaveRequest::where('status', 'rejected')->count(),
        ];
    }

    private function monthlyChartData(): Collection
    {
        $year = now()->year;
        $raw  = LeaveRequest::selectRaw('MONTH(start_date) as month, status, COUNT(*) as total')
            ->whereYear('start_date', $year)
            ->groupBy('month', 'status')
            ->get()
            ->groupBy('month');

        return collect(range(1, 12))->map(function ($month) use ($raw) {
            $data = $raw->get($month, collect());

            $approved = (int) optional($data->firstWhere('status', 'approved'))->total;
            $rejected = (int) optional($data->firstWhere('status', 'rejected'))->total;
            $pending  = (int) optional($data->firstWhere('status', 'pending'))->total;

            return [
                'label'    => now()->setMonth($month)->translatedFormat('M'),
                'approved' => $approved,
                'rejected' => $rejected,
                'pending'  => $pending,
            ];
        });
    }

    private function lowLeaveEmployees(): Collection
    {
        return User::where('role', 'employee')
            ->get()
            ->map(function ($employee) {
                $summary   = $this->leaveBalanceService->getAnnualLeaveSummary($employee);
                $remaining = $summary['current_year_available'] ?? 0;

                return (object) [
                    'name'                   => $employee->name,
                    'employee_id'            => $employee->employee_id,
                    'annual_leave_quota'     => $summary['quota_per_year'] ?? 0,
                    'current_year_used'      => $summary['quota_per_year'] - $remaining,
                    'remaining_leave_days'   => $remaining,
                ];
            })
            ->sortBy('remaining_leave_days')
            ->take(5);
    }
}


