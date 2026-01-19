<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KuotaTahunan;
use App\Models\LeaveRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika admin → langsung tampilkan dashboard admin
        if ($user->isAdmin()) {
            $stats = $this->adminStats();
            return view('dashboard.admin', compact('stats'));
        }

        // Jika pegawai → tampilkan dashboard pegawai
        $pegawai = $user->pegawai;
        $cuti = $pegawai?->cuti;
        
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        // Ambil kuota tahunan dari tabel kuota_tahunan
        $kuotaTahunanData = KuotaTahunan::where('user_id', $user->id)
            ->whereIn('tahun', [$previousYear, $currentYear])
            ->where('expired', false)
            ->orderBy('tahun', 'asc')
            ->get();

        // Siapkan data untuk view
        $kuotaDetail = [
            'tahun_lalu' => null,
            'tahun_sekarang' => null,
            'total_tersedia' => 0,
        ];

        foreach ($kuotaTahunanData as $kuota) {
            $sisa = $kuota->kuota - $kuota->dipakai;
            
            if ($kuota->tahun == $previousYear) {
                $kuotaDetail['tahun_lalu'] = [
                    'tahun' => $kuota->tahun,
                    'kuota' => $kuota->kuota,
                    'dipakai' => $kuota->dipakai,
                    'sisa' => $sisa,
                ];
            } elseif ($kuota->tahun == $currentYear) {
                $kuotaDetail['tahun_sekarang'] = [
                    'tahun' => $kuota->tahun,
                    'kuota' => $kuota->kuota,
                    'dipakai' => $kuota->dipakai,
                    'sisa' => $sisa,
                ];
            }
            
            $kuotaDetail['total_tersedia'] += $sisa;
        }

        // Siapkan leave stats untuk dashboard
        $leaveStats = [
            'pending' => LeaveRequest::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];
        
        // Siapkan recent leaves
        $recentLeaves = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'pegawai', 
            'cuti', 
            'currentYear', 
            'previousYear', 
            'kuotaDetail',
            'leaveStats', 
            'recentLeaves'
        ));
    }

    public function admin()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $stats = $this->adminStats();
        return view('dashboard.admin', compact('stats'));
    }

    private function adminStats(): array
    {
        $totalEmployees = User::where('role', 'employee')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        // Data leave requests
        $pending = LeaveRequest::where('status', 'pending')->count();
        $approved = LeaveRequest::where('status', 'approved')->count();
        $rejected = LeaveRequest::where('status', 'rejected')->count();
        $total = $pending + $approved + $rejected;

        // Approval rate
        $approvalRate = $total > 0 ? round(($approved / $total) * 100) : 0;
        $rejectionRate = $total > 0 ? round(($rejected / $total) * 100) : 0;

        // Total used quota across all users
        $totalQuota = KuotaTahunan::sum('kuota');
        $totalUsed = KuotaTahunan::sum('dipakai');

        // Average leave days
        $avgLeaveDays = 0;
        if ($approved > 0) {
            $approvedLeaves = LeaveRequest::where('status', 'approved')->get();
            $totalDays = 0;
            foreach ($approvedLeaves as $leave) {
                $totalDays += $leave->days ?? 0;
            }
            $avgLeaveDays = round($totalDays / count($approvedLeaves));
        }

        // Monthly activity (this month)
        $monthlyActivity = LeaveRequest::whereMonth('disetujui_pada', now()->month)
            ->whereYear('disetujui_pada', now()->year)
            ->where('status', 'approved')
            ->count();

        return [
            'employees' => $totalEmployees,
            'admins' => $totalAdmins,
            'total_users' => $totalEmployees + $totalAdmins,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'total_requests' => $total,
            'approval_rate' => $approvalRate,
            'rejection_rate' => $rejectionRate,
            'total_quota' => $totalQuota,
            'total_used' => $totalUsed,
            'remaining_quota' => $totalQuota - $totalUsed,
            'quota_usage_rate' => $totalQuota > 0 ? round(($totalUsed / $totalQuota) * 100) : 0,
            'avg_leave_days' => $avgLeaveDays,
            'monthly_activity' => $monthlyActivity,
            'employee_growth' => 0,
        ];
    }
}