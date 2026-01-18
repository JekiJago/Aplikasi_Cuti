<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\LeaveBalanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LeaveRequestController extends Controller
{
    public function __construct(
        private LeaveBalanceService $leaveBalanceService
    ) {
    }

    public function index()
    {
        $user  = auth()->user();
        $query = $user->leaveRequests()->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('from') && request('to')) {
            $query->whereBetween('start_date', [request('from'), request('to')]);
        }

        $leaveRequests = $query->paginate(10);
        
        // DAPATKAN DATA KUOTA YANG KONSISTEN
        $currentYear = now()->year;
        $currentYearSummary = $this->leaveBalanceService->getFixedAnnualLeaveSummary($user, $currentYear);

        return view('leave-requests.index', compact('leaveRequests', 'currentYearSummary'));
    }

    public function create()
    {
        $user          = auth()->user();
        $currentYear   = now()->year;
        $previousYear  = $currentYear - 1;
        
        // Get available annual leave
        $availableQuota = $this->leaveBalanceService->getAvailableAnnualLeaveWithPriority($user->id);
        $totalAvailable = $availableQuota['total_available'];

        return view('leave-requests.create', [
            'remaining'           => $totalAvailable,
            'currentYear'         => $currentYear,
            'previousYear'        => $previousYear,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'alasan'     => ['required', 'string', 'max:2000'],
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end   = Carbon::parse($validated['end_date']);

        // Validasi rentang tanggal
        if ($start->diffInDays($end) > 365) {
            throw ValidationException::withMessages([
                'end_date' => 'Rentang tanggal tidak boleh lebih dari 1 tahun.',
            ]);
        }

        // Hitung hari kerja untuk validasi kuota
        $days = LeaveRequest::workingDaysBetween($start, $end);

        // Validasi kuota dengan prioritas baru
        $this->validateLeaveRequest($user, 'tahunan', $days, $start, $end);

        // Cek tabrakan dengan cuti lain
        $this->ensureNoOverlap($user, $start, $end);

        // Buat pengajuan cuti
        $leaveRequest = LeaveRequest::create([
            'user_id'   => $user->id,
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'],
            'alasan'     => $validated['alasan'],
            'status'     => 'pending',
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function show($id)
    {
        $leave = LeaveRequest::with(['user', 'approver'])->findOrFail($id);

        $this->authorize('view', $leave);

        // Tambahkan info kuota tahun untuk cuti tahunan
        if ($leave->leave_type === 'tahunan') {
            $currentYear = now()->year;
            $quotaSummary = $this->leaveBalanceService->getFixedAnnualLeaveSummary($leave->user, $currentYear);
            return view('leave-requests.show', compact('leave', 'quotaSummary'));
        }

        return view('leave-requests.show', compact('leave'));
    }

    public function destroy($id)
    {
        $leave = LeaveRequest::findOrFail($id);

        $this->authorize('delete', $leave);

        // Hanya bisa hapus jika masih pending
        if ($leave->status !== 'pending') {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Hanya pengajuan yang masih menunggu bisa dihapus.');
        }

        // Hapus file attachment jika ada
        if ($leave->file_path) {
            Storage::disk('public')->delete($leave->file_path);
        }

        $leave->delete();

        return redirect()->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    /**
     * Approve leave request with proper quota deduction
     */
    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        // Authorization check - hanya admin atau atasan langsung
        if (auth()->user()->role !== 'admin' && auth()->id() !== $leaveRequest->user->supervisor_id) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menyetujui cuti ini.');
        }
        
        // Hanya bisa approve jika pending
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Permohonan cuti sudah diproses.');
        }
        
        if ($leaveRequest->leave_type === 'tahunan') {
            try {
                // Potong kuota dengan prioritas yang benar
                $result = $this->leaveBalanceService->deductAnnualLeaveWithPriority(
                    $leaveRequest->user_id,
                    $leaveRequest->start_date,
                    $leaveRequest->end_date
                );
                
                // Update status leave request
                $leaveRequest->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approver_id' => auth()->id()
                ]);
                
                // Log penggunaan kuota
                Log::info('Leave approved with quota usage:', $result);
                
                return back()->with('success', 'Cuti disetujui. Kuota telah dipotong dengan prioritas.');
                
            } catch (\Exception $e) {
                Log::error('Failed to deduct quota: ' . $e->getMessage());
                return back()->with('error', 'Gagal memotong kuota: ' . $e->getMessage());
            }
        } else {
            // Untuk cuti non-tahunan, update status saja
            $leaveRequest->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approver_id' => auth()->id()
            ]);
            
            return back()->with('success', 'Cuti disetujui.');
        }
    }

    /**
     * Reject leave request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);
        
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        // Authorization check - hanya admin atau atasan langsung
        if (auth()->user()->role !== 'admin' && auth()->id() !== $leaveRequest->user->supervisor_id) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menolak cuti ini.');
        }
        
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Permohonan cuti sudah diproses.');
        }
        
        $leaveRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejecter_id' => auth()->id()
        ]);
        
        return back()->with('success', 'Cuti ditolak.');
    }

    /**
     * Cancel leave request (return quota if needed)
     */
    public function cancel($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        if ($leaveRequest->status !== 'approved') {
            return back()->with('error', 'Hanya cuti yang sudah disetujui yang bisa dibatalkan.');
        }
        
        if ($leaveRequest->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki izin untuk membatalkan cuti ini.');
        }
        
        if ($leaveRequest->leave_type === 'tahunan') {
            try {
                // Kembalikan kuota yang telah dipotong
                $this->leaveBalanceService->returnAnnualLeave(
                    $leaveRequest->user_id,
                    $leaveRequest->start_date,
                    $leaveRequest->end_date
                );
                
                Log::info('Quota returned for cancelled leave request: ' . $id);
            } catch (\Exception $e) {
                Log::error('Failed to return quota: ' . $e->getMessage());
            }
        }
        
        $leaveRequest->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);
        
        return back()->with('success', 'Cuti berhasil dibatalkan. Kuota telah dikembalikan.');
    }

    /**
     * Updated validation with priority rules
     */
    private function validateLeaveRequest($user, string $leaveType, int $days, Carbon $start, Carbon $end): void
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;

        switch ($leaveType) {
            case 'tahunan':
                // Validasi dengan prioritas baru
                $validation = $this->leaveBalanceService->validateAnnualLeaveWithPriority($user, $start, $end);
                
                if (!$validation['valid']) {
                    throw ValidationException::withMessages([
                        'leave_type' => $validation['message'] ?? 'Kuota cuti tahunan tidak mencukupi.',
                    ]);
                }
                
                // Simpan simulasi penggunaan kuota
                session()->flash('quota_simulation', $validation['simulation'] ?? []);
                break;

            case 'urusan_penting':
                $limit      = 30;
                $remaining  = $limit - ($user->important_leave_used_days ?? 0);
                if ($remaining <= 0 || $days > $remaining) {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Kuota cuti urusan penting (30 hari) sudah habis.',
                    ]);
                }
                break;

            case 'cuti_besar':
                $hireDate = $user->hire_date;
                if (!$hireDate || $hireDate->diffInYears(now()) < 5) {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Cuti besar hanya untuk pegawai dengan masa kerja minimal 5 tahun.',
                    ]);
                }

                if ($user->big_leave_last_used_at && $user->big_leave_last_used_at->addYears(5)->isFuture()) {
                    $next = $user->big_leave_last_used_at->copy()->addYears(5)->format('d M Y');

                    throw ValidationException::withMessages([
                        'leave_type' => "Cuti besar berikutnya baru bisa diajukan setelah {$next}.",
                    ]);
                }

                if ($days > 90) {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Durasi cuti besar maksimal 90 hari.',
                    ]);
                }
                break;

            case 'cuti_non_aktif':
                if (($user->non_active_leave_used_days ?? 0) > 0) {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Cuti non-aktif hanya bisa dipakai satu kali seumur hidup.',
                    ]);
                }

                if ($days < 365 || $days > 730) {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Durasi cuti non-aktif wajib 1 - 2 tahun (365-730 hari).',
                    ]);
                }
                break;

            case 'cuti_bersalin':
                if ($user->gender !== 'female') {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Cuti bersalin khusus untuk pegawai perempuan.',
                    ]);
                }

                if (($user->maternity_leave_used_count ?? 0) >= 3) {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Cuti bersalin maksimal sampai penggunaan ke-3.',
                    ]);
                }

                if ($days > 90) {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Durasi cuti bersalin maksimal 90 hari.',
                    ]);
                }
                break;

            case 'cuti_sakit':
                $limit     = 540;
                $remaining = $limit - ($user->sick_leave_used_days ?? 0);

                if ($remaining <= 0 || $days > $remaining) {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Kuota cuti sakit (540 hari sepanjang karir) tidak mencukupi.',
                    ]);
                }
                break;
        }
    }

    private function ensureNoOverlap($user, Carbon $start, Carbon $end): void
    {
        $overlap = LeaveRequest::hasOverlap($user->id, $start, $end);

        if ($overlap) {
            throw ValidationException::withMessages([
                'start_date' => 'Tanggal pengajuan bertabrakan dengan cuti lain yang sudah disetujui.',
            ]);
        }
    }

}