<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ==========================
        // DASHBOARD ADMIN
        // ==========================
        if ($user->isAdmin()) {

            // Statistik ringkas di 4 kartu atas
            $stats = [
                'employees' => User::where('role', 'employee')->count(),
                'pending'   => LeaveRequest::where('status', 'pending')->count(),
                'approved'  => LeaveRequest::where('status', 'approved')->count(),
                'rejected'  => LeaveRequest::where('status', 'rejected')->count(),
            ];

            // Statistik per bulan untuk grafik (Jan–Des di tahun berjalan)
            $months = collect(range(1, 12))->map(function ($month) {
                return Carbon::createFromDate(now()->year, $month, 1);
            });

            $monthlyStats = $months->map(function (Carbon $date) {
                return [
                    'label'    => $date->translatedFormat('M'), // Jan, Feb, Mar, dst (ikut locale)
                    'approved' => LeaveRequest::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->where('status', 'approved')
                        ->count(),
                    'rejected' => LeaveRequest::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->where('status', 'rejected')
                        ->count(),
                    'pending'  => LeaveRequest::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->where('status', 'pending')
                        ->count(),
                ];
            });

            // Pengajuan terbaru (list bawah kiri)
            $recentLeaves = LeaveRequest::with('user')
                ->latest()
                ->take(5)
                ->get();

            // Pegawai dengan sisa cuti terendah (list bawah kanan)
            $lowLeaveEmployees = User::where('role', 'employee')
                ->orderByRaw('(annual_leave_quota - used_leave_days) asc')
                ->take(5)
                ->get();

            return view('dashboard.admin', compact(
                'stats',
                'monthlyStats',
                'recentLeaves',
                'lowLeaveEmployees'
            ));
        }

        // ==========================
        // DASHBOARD PEGAWAI
        // ==========================
        $totalAnnualQuota = $user->annual_leave_quota ?? 0;
        $usedAnnual       = $user->used_leave_days ?? 0;
        $remaining        = max(0, $totalAnnualQuota - $usedAnnual);

        $stats = [
            'quota'     => $totalAnnualQuota,
            'used'      => $usedAnnual,
            'remaining' => $remaining,
            'expiring'  => 0, // bisa kamu hitung nanti (cuti akan hangus)
        ];

        $userLeaves = $user->leaveRequests()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.employee', [
            'stats'      => $stats,
            'userLeaves' => $userLeaves,
        ]);
    }
}
