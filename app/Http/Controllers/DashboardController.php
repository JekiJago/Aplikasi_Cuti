<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\LeaveBalanceService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private LeaveBalanceService $leaveBalanceService
    ) {
    }

    public function index()
    {
        $user = Auth::user();

        // Jika admin → langsung tampilkan dashboard.admin
        if ($user->isAdmin()) {
            $stats            = $this->adminStats();
            $monthlyStats     = $this->monthlyChartData();
            $recentLeaves     = LeaveRequest::with('user')->latest()->limit(5)->get();
            $lowLeaveEmployees = $this->lowLeaveEmployees();

            return view('dashboard.admin', compact(
                'stats',
                'monthlyStats',
                'recentLeaves',
                'lowLeaveEmployees'
            ));
        }

        // Jika pegawai → tampilkan dashboard pegawai dengan PERHITUNGAN YANG BENAR
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;
        
        // GUNAKAN METHOD BARU YANG MEMAKAI FIFO BREAKDOWN (SAMA DENGAN ADMIN)
        $currentYearSummary = $this->leaveBalanceService->getFixedAnnualLeaveSummary($user, $currentYear);
        $previousYearSummary = $this->leaveBalanceService->getFixedAnnualLeaveSummary($user, $previousYear);
        
        // Ambil data riwayat cuti terbaru
        $recentLeaves  = $user->leaveRequests()->latest()->limit(5)->get();
        
        // Hitung statistik cuti
        $leaveStats = [
            'pending' => $user->leaveRequests()->where('status', 'pending')->count(),
            'approved' => $user->leaveRequests()->where('status', 'approved')->count(),
            'rejected' => $user->leaveRequests()->where('status', 'rejected')->count(),
        ];

        return view('dashboard.index', compact(
            'currentYearSummary',
            'previousYearSummary',
            'currentYear',
            'previousYear',
            'recentLeaves',
            'leaveStats'
        ));
    }

    public function admin()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $stats            = $this->adminStats();
        $monthlyStats     = $this->monthlyChartData();
        $recentLeaves     = LeaveRequest::with('user')->latest()->limit(5)->get();
        $lowLeaveEmployees = $this->lowLeaveEmployees();

        return view('dashboard.admin', compact(
            'stats',
            'monthlyStats',
            'recentLeaves',
            'lowLeaveEmployees'
        ));
    }

    private function adminStats(): array
    {
        $totalEmployees = User::where('role', 'employee')->count();
        $pending = LeaveRequest::where('status', 'pending')->count();
        $approved = LeaveRequest::where('status', 'approved')->count();
        $rejected = LeaveRequest::where('status', 'rejected')->count();
        $totalRequests = $pending + $approved + $rejected;

        $approvalRate = $totalRequests > 0 ? round(($approved / $totalRequests) * 100, 1) : 0;
        $rejectionRate = $totalRequests > 0 ? round(($rejected / $totalRequests) * 100, 1) : 0;

        $avgLeaveDays = $this->calculateAvgLeaveDays();

        $monthlyActivity = LeaveRequest::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $employeeGrowth = $this->calculateEmployeeGrowth();

        return [
            'employees'        => $totalEmployees,
            'employee_growth'  => $employeeGrowth,
            'pending'          => $pending,
            'approved'         => $approved,
            'rejected'         => $rejected,
            'approval_rate'    => $approvalRate,
            'rejection_rate'   => $rejectionRate,
            'avg_leave_days'   => $avgLeaveDays,
            'monthly_activity' => $monthlyActivity,
            'total_requests'   => $totalRequests,
        ];
    }

    private function calculateAvgLeaveDays(): float
    {
        $avgDays = LeaveRequest::where('status', 'approved')
            ->selectRaw('AVG(DATEDIFF(end_date, start_date) + 1) as avg_days')
            ->first();

        return $avgDays ? round($avgDays->avg_days, 1) : 0;
    }

    private function calculateEmployeeGrowth(): float
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        $lastMonthYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;

        $currentMonthEmployees = User::where('role', 'employee')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $lastMonthEmployees = User::where('role', 'employee')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastMonthYear)
            ->count();

        if ($lastMonthEmployees == 0) {
            return $currentMonthEmployees > 0 ? 100 : 0;
        }

        $growth = (($currentMonthEmployees - $lastMonthEmployees) / $lastMonthEmployees) * 100;
        return round($growth, 1);
    }

    private function monthlyChartData(): Collection
    {
        $year = now()->year;

        $raw = LeaveRequest::selectRaw('MONTH(start_date) as month, status, COUNT(*) as total')
            ->whereYear('start_date', $year)
            ->groupBy('month', 'status')
            ->get()
            ->groupBy('month');

        return collect(range(1, 12))->map(function ($month) use ($raw) {
            $data = $raw->get($month, collect());

            return [
                'label'    => now()->setMonth($month)->translatedFormat('M'),
                'approved' => (int) optional($data->firstWhere('status', 'approved'))->total,
                'rejected' => (int) optional($data->firstWhere('status', 'rejected'))->total,
                'pending'  => (int) optional($data->firstWhere('status', 'pending'))->total,
            ];
        });
    }

    private function lowLeaveEmployees(): Collection
    {
        return User::where('role', 'employee')
            ->get()
            ->map(function ($employee) {
                $currentYear = now()->year;
                $previousYear = $currentYear - 1;
                
                // GUNAKAN SERVICE YANG SAMA
                $currentYearSummary = $this->leaveBalanceService->getFixedAnnualLeaveSummary($employee, $currentYear);
                $previousYearSummary = $this->leaveBalanceService->getFixedAnnualLeaveSummary($employee, $previousYear);
                
                $remaining = ($currentYearSummary['current_year_available'] ?? 0) + 
                            ($previousYearSummary['current_year_available'] ?? 0);
                $quota     = $currentYearSummary['quota_per_year'] ?? 0;

                return (object) [
                    'name'                => $employee->name,
                    'employee_id'         => $employee->employee_id,
                    'annual_leave_quota'  => $quota,
                    'current_year_used'   => $currentYearSummary['details'][$currentYear]['used'] ?? 0,
                    'previous_year_used'  => $previousYearSummary['details'][$previousYear]['used'] ?? 0,
                    'remaining_leave_days'=> $remaining,
                    'current_year_remaining' => $currentYearSummary['current_year_available'] ?? 0,
                    'previous_year_remaining' => $previousYearSummary['current_year_available'] ?? 0,
                ];
            })
            ->filter(fn($employee) => $employee->remaining_leave_days <= 5)
            ->sortBy('remaining_leave_days')
            ->take(5);
    }
}