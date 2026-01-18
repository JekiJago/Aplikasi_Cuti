<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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
        
        // Hitung tahun aktif untuk sistem 2 tahun
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
        
        // Siapkan summary cuti untuk tahun saat ini dan tahun lalu
        $currentYearSummary = [
            'current_year_available' => $cuti?->kuota_tahunan - ($cuti?->cuti_dipakai ?? 0) ?? 0,
            'details' => [
                $currentYear => [
                    'quota' => $cuti?->kuota_tahunan ?? 0,
                    'used' => $cuti?->cuti_dipakai ?? 0,
                ],
            ]
        ];
        
        // Siapkan leave stats untuk dashboard
        $leaveStats = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];
        
        // Siapkan recent leaves
        $recentLeaves = [];

        return view('dashboard.index', compact('pegawai', 'cuti', 'currentYear', 'previousYear', 'currentYearSummary', 'leaveStats', 'recentLeaves'));
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

        return [
            'employees' => $totalEmployees,
            'admins' => $totalAdmins,
            'total_users' => $totalEmployees + $totalAdmins,
        ];
    }
}