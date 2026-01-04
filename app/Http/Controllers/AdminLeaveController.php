<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\LeaveBalanceService;
use App\Models\AnnualQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminLeaveController extends Controller
{
    protected $leaveBalanceService;

    public function __construct(LeaveBalanceService $leaveBalanceService)
    {
        $this->leaveBalanceService = $leaveBalanceService;
    }

    /**
     * List semua pengajuan cuti
     */
    public function index()
    {
        $query = LeaveRequest::with('user')->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('department')) {
            $query->whereHas('user', function ($q) {
                $q->where('department', request('department'));
            });
        }

        if (request('type')) {
            $query->where('leave_type', request('type'));
        }

        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('employee_id', 'like', "%{$search}%");
                  });
            });
        }

        $leaves = $query->paginate(15);

        return view('admin.leaves.index', compact('leaves'));
    }

    /**
     * Detail pengajuan cuti
     */
    public function show($id)
    {
        $leave = LeaveRequest::with(['user', 'approver'])->findOrFail($id);
        
        // Hitung hari kerja aktual jika cuti tahunan
        $workingDays = 0;
        if ($leave->leave_type === 'tahunan' && $leave->start_date && $leave->end_date) {
            $daysPerYear = $this->leaveBalanceService->calculateAnnualDaysPerYear(
                Carbon::parse($leave->start_date),
                Carbon::parse($leave->end_date)
            );
            $workingDays = array_sum($daysPerYear);
        }

        return view('admin.leaves.show', compact('leave', 'workingDays'));
    }

    /**
     * Approve cuti
     * - Update status
     * - Potong kuota (AnnualQuota) dengan perhitungan hari kerja
     */
    public function approve(Request $request, $id)
    {
        $leave = LeaveRequest::with('user')->findOrFail($id);

        // Cegah double approve
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Pengajuan cuti sudah diproses.');
        }

        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            DB::transaction(function () use ($leave, $request) {

                // 1. Approve status cuti
                $leave->approve(
                    auth()->id(),
                    $request->input('admin_notes')
                );

                // 2. Potong kuota HANYA untuk cuti tahunan
                if ($leave->leave_type === 'tahunan') {
                    // Validasi ketersediaan kuota sebelum memotong
                    $hasEnoughBalance = $this->leaveBalanceService->validateLeaveBalance(
                        $leave->user_id,
                        Carbon::parse($leave->start_date),
                        Carbon::parse($leave->end_date)
                    );

                    if (!$hasEnoughBalance) {
                        throw new \Exception('Kuota cuti tahunan tidak mencukupi.');
                    }

                    // Potong kuota dengan perhitungan hari kerja
                    $this->leaveBalanceService->deductAnnualLeave(
                        $leave->user_id,
                        Carbon::parse($leave->start_date),
                        Carbon::parse($leave->end_date)
                    );
                }
            });

            // TODO: kirim notifikasi ke pegawai
            // Notification::send($leave->user, new LeaveApprovedNotification($leave));

        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyetujui cuti: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.leaves.show', $leave->id)
            ->with('success', 'Pengajuan cuti berhasil disetujui.');
    }

    /**
     * Tolak cuti
     */
    public function reject(Request $request, $id)
    {
        $leave = LeaveRequest::with('user')->findOrFail($id);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Pengajuan cuti sudah diproses.');
        }

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($leave, $request) {
            $leave->reject(
                auth()->id(),
                $request->input('admin_notes')
            );
        });

        // TODO: kirim notifikasi ke pegawai
        // Notification::send($leave->user, new LeaveRejectedNotification($leave));

        return redirect()
            ->route('admin.leaves.show', $leave->id)
            ->with('success', 'Pengajuan cuti ditolak.');
    }

    /**
     * Cancel approval (hanya untuk admin)
     */
    public function cancelApproval($id)
    {
        $leave = LeaveRequest::with('user')->findOrFail($id);

        if ($leave->status !== 'approved') {
            return back()->with('error', 'Hanya cuti yang sudah disetujui yang dapat dibatalkan.');
        }

        try {
            DB::transaction(function () use ($leave) {
                // Kembalikan kuota jika cuti tahunan
                if ($leave->leave_type === 'tahunan' && $leave->start_date && $leave->end_date) {
                    // Hitung hari kerja yang sudah dipotong
                    $daysPerYear = $this->leaveBalanceService->calculateAnnualDaysPerYear(
                        Carbon::parse($leave->start_date),
                        Carbon::parse($leave->end_date)
                    );
                    
                    // Kembalikan kuota (reverse FIFO - newest first)
                    foreach ($daysPerYear as $year => $daysToReturn) {
                        if ($daysToReturn <= 0) continue;
                        
                        // Cari kuota untuk tahun ini atau sebelumnya
                        $quotas = AnnualQuota::where('user_id', $leave->user_id)
                            ->where('year', '<=', $year)
                            ->where('used_days', '>', 0)
                            ->orderBy('year', 'desc') // LIFO untuk pengembalian
                            ->lockForUpdate()
                            ->get();
                        
                        $remainingDays = $daysToReturn;
                        
                        foreach ($quotas as $quota) {
                            if ($remainingDays <= 0) break;
                            
                            $used = $quota->used_days;
                            $canReturn = min($used, $remainingDays);
                            
                            $quota->decrement('used_days', $canReturn);
                            $remainingDays -= $canReturn;
                        }
                    }
                }

                // Batalkan status cuti
                $leave->update([
                    'status' => 'pending',
                    'approved_at' => null,
                    'approved_by' => null,
                    'rejected_at' => null,
                    'rejected_by' => null,
                    'admin_notes' => null,
                ]);
            });

        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membatalkan persetujuan: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.leaves.show', $leave->id)
            ->with('success', 'Persetujuan cuti berhasil dibatalkan.');
    }

    /**
     * Statistik cuti
     */
    public function statistics()
    {
        $stats = [
            'pending'  => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
            'cancelled' => LeaveRequest::where('status', 'cancelled')->count(),

            'byType' => LeaveRequest::selectRaw('leave_type, COUNT(*) as total')
                ->groupBy('leave_type')
                ->pluck('total', 'leave_type'),

            'byDept' => LeaveRequest::selectRaw('users.department, COUNT(*) as total')
                ->join('users', 'leave_requests.user_id', '=', 'users.id')
                ->groupBy('users.department')
                ->pluck('total', 'users.department'),

            'monthly' => LeaveRequest::selectRaw('MONTH(start_date) as month, COUNT(*) as total')
                ->whereYear('start_date', date('Y'))
                ->where('status', 'approved')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month'),

            'recentApprovals' => LeaveRequest::with('user')
                ->where('status', 'approved')
                ->whereDate('approved_at', '>=', now()->subDays(7))
                ->orderBy('approved_at', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('admin.statistics', compact('stats'));
    }

    /**
     * Download / preview lampiran cuti - FIXED VERSION
     */
    public function attachment(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);
        
        // Periksa apakah user memiliki akses (admin atau pemilik cuti)
        if (!auth()->user()->isAdmin() && auth()->id() != $leave->user_id) {
            abort(403, 'Unauthorized access.');
        }

        // Pastikan ada attachment
        if (!$leave->file_path || $leave->file_path == '') {
            abort(404, 'Tidak ada lampiran untuk cuti ini.');
        }

        // Periksa file exists di storage
        if (!Storage::disk('public')->exists($leave->file_path)) {
            abort(404, 'File lampiran tidak ditemukan di server.');
        }

        // Path lengkap ke file
        $filePath = Storage::disk('public')->path($leave->file_path);
        $fileName = basename($leave->file_path);

        // Jika parameter download=1, kirim sebagai download
        if ($request->has('download') && $request->boolean('download')) {
            return response()->download($filePath, $fileName);
        }

        // Default: tampilkan preview (browser akan menampilkan sesuai tipe file)
        return response()->file($filePath);
    }

    /**
     * View leave balance for a user
     */
    public function viewBalance($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        $balanceSummary = $this->leaveBalanceService->getAnnualLeaveSummary($user);
        $balanceBreakdown = $this->leaveBalanceService->getBalanceBreakdown($userId);
        
        return view('admin.leaves.balance', compact('user', 'balanceSummary', 'balanceBreakdown'));
    }

    /**
     * Manual quota adjustment
     */
    public function adjustQuota(Request $request, $userId)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'quota_days' => 'required|integer|min:0|max:365',
            'reason' => 'required|string|max:500',
        ]);

        try {
            $quota = $this->leaveBalanceService->addAnnualQuota(
                $userId,
                $request->year,
                $request->quota_days
            );

            // Log adjustment
            \App\Models\QuotaAdjustment::create([
                'user_id' => $userId,
                'admin_id' => auth()->id(),
                'year' => $request->year,
                'old_quota' => $quota->getOriginal('annual_quota'),
                'new_quota' => $request->quota_days,
                'reason' => $request->reason,
            ]);

            return back()->with('success', 'Kuota berhasil disesuaikan.');

        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyesuaikan kuota: ' . $e->getMessage());
        }
    }
}