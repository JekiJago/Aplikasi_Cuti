<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = $user->leaveRequests()->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('from') && request('to')) {
            $query->whereBetween('start_date', [request('from'), request('to')]);
        }

        $leaveRequests = $query->paginate(10);

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        $user = auth()->user();

        $remaining = $user->getRemainingLeaveDays();
        $quota     = $user->annual_leave_quota;

        return view('leave-requests.create', compact('remaining', 'quota'));
    }

    public function store(Request $request)
    {
            $user = auth()->user();

            $validated = $request->validate([
                'leave_type' => ['required', Rule::in([
                    'tahunan',
                    'urusan_penting',
                    'cuti_besar',
                    'cuti_non_aktif',
                    'cuti_bersalin',
                    'cuti_sakit',
                ])],
                'start_date' => ['required', 'date', 'after_or_equal:today'],
                'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
                'reason'     => ['required', 'string', 'max:2000'],
                'attachment' => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
            ]);

            $start = Carbon::parse($validated['start_date']);
            $end   = Carbon::parse($validated['end_date']);
            $days  = $start->diffInDays($end) + 1;

            // =============================
            // VALIDASI BERDASARKAN JENIS CUTI
            // =============================
            switch ($validated['leave_type']) {
                case 'tahunan':
                    // pakai annual_leave_quota sebagai batas (24 hari / 2 tahun)
                    $maxAnnual  = $user->annual_leave_quota ?? 24;

                    $usedAnnual = $user->leaveRequests()
                        ->where('leave_type', 'tahunan')
                        ->where('status', 'approved')
                        ->sum('days');

                    if ($usedAnnual + $days > $maxAnnual) {
                        return back()
                            ->withErrors(['leave_type' => 'Kuota cuti tahunan sudah terlampaui.'])
                            ->withInput();
                    }
                    break;

                case 'urusan_penting':
                    $maxImportant = 30; // lifetime
                    $usedImportant = $user->important_leave_used_days;

                    if ($usedImportant + $days > $maxImportant) {
                        return back()
                            ->withErrors(['leave_type' => 'Kuota cuti urusan penting (30 hari seumur hidup) sudah habis.'])
                            ->withInput();
                    }
                    break;

                case 'cuti_besar':
                    // masa kerja minimal 5 tahun
                    if (!$user->hire_date || $user->hire_date->diffInYears(now()) < 5) {
                        return back()
                            ->withErrors(['leave_type' => 'Cuti besar hanya untuk karyawan dengan masa kerja ≥ 5 tahun.'])
                            ->withInput();
                    }

                    $maxBig = 90;
                    $usedBig = $user->big_leave_used_days;

                    if ($usedBig + $days > $maxBig) {
                        return back()
                            ->withErrors(['leave_type' => 'Kuota cuti besar (90 hari seumur hidup) sudah habis.'])
                            ->withInput();
                    }
                    break;

                case 'cuti_non_aktif':
                    // anggap max 2 tahun = 730 hari (boleh kamu sesuaikan)
                    $maxNonActive = 730;
                    $usedNonActive = $user->non_active_leave_used_days;

                    if ($usedNonActive + $days > $maxNonActive) {
                        return back()
                            ->withErrors(['leave_type' => 'Kuota cuti non aktif (maks 2 tahun seumur hidup) sudah terpakai semua.'])
                            ->withInput();
                    }
                    break;

                case 'cuti_bersalin':
                    // maksimal sampai anak ke-3 → max 3x
                    if ($user->maternity_leave_used_count >= 3) {
                        return back()
                            ->withErrors(['leave_type' => 'Cuti bersalin hanya sampai kelahiran anak ke-3.'])
                            ->withInput();
                    }
                    // kalau mau batasi durasi 3 bulan ≈ 90 hari, bisa tambah:
                    // if ($days > 90) ...
                    break;

                case 'cuti_sakit':
                    $maxSick = 540;
                    $usedSick = $user->sick_leave_used_days;

                    if ($usedSick + $days > $maxSick) {
                        return back()
                            ->withErrors(['leave_type' => 'Kuota cuti sakit (540 hari sepanjang karir) sudah habis.'])
                            ->withInput();
                    }
                    break;
            }

            // =============================
            // CEK OVERLAP DENGAN CUTI APPROVED LAIN
            // =============================
            $overlap = $user->leaveRequests()
                ->where('status', 'approved')
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
                })
                ->exists();

            if ($overlap) {
                return back()
                    ->withErrors(['start_date' => 'Tanggal pengajuan bertabrakan dengan cuti yang sudah disetujui.'])
                    ->withInput();
            }

            // =============================
            // Upload file (jika ada)
            // =============================
            $filePath = null;
            if ($request->hasFile('attachment')) {
                $filePath = $request->file('attachment')->store('leave_attachments', 'public');
            }

            // =============================
            // Simpan pengajuan
            // =============================
            $leave = LeaveRequest::create([
                'user_id'      => $user->id,
                'leave_type'   => $validated['leave_type'],
                'start_date'   => $validated['start_date'],
                'end_date'     => $validated['end_date'],
                'days'         => $days,
                'reason'       => $validated['reason'],
                'file_path'    => $filePath,
                'status'       => 'pending',
                'submitted_at' => now(),
            ]);

            // TODO: buat notification & activity log

            return redirect()->route('leave-requests.index')
                ->with('success', 'Pengajuan cuti berhasil dikirim.');
        }

    public function show($id)
    {
        $leave = LeaveRequest::with(['user', 'approver'])->findOrFail($id);

        $this->authorize('view', $leave);

        return view('leave-requests.show', compact('leave'));
    }

    public function destroy($id)
    {
        $leave = LeaveRequest::findOrFail($id);

        $this->authorize('delete', $leave);

        $leave->delete();

        return redirect()->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus.');
    }
}
