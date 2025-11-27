<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\LeaveBalanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        $user          = auth()->user();
        $annualSummary = $this->leaveBalanceService->getAnnualLeaveSummary($user);

        $quotaCards = $this->buildQuotaCards($user, $annualSummary);

        return view('leave-requests.create', [
            'remaining'     => $annualSummary['total_available'],
            'quota'         => $annualSummary['quota_per_year'],
            'annualSummary' => $annualSummary,
            'quotaCards'    => $quotaCards,
        ]);
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

        if ($validated['leave_type'] === 'tahunan') {
            $days = LeaveRequest::workingDaysBetween($start, $end);

            if ($days <= 0) {
                throw ValidationException::withMessages([
                    'start_date' => 'Rentang tanggal tidak memiliki hari kerja (akhir pekan/libur).',
                ]);
            }
        } else {
            $days = $start->diffInDays($end) + 1;
        }

        $this->validateLeaveRequest($user, $validated['leave_type'], $days, $start);

        $this->ensureNoOverlap($user, $start, $end);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('leave_attachments', 'public');
        }

        LeaveRequest::create([
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

    private function validateLeaveRequest($user, string $leaveType, int $days, Carbon $start): void
    {
        switch ($leaveType) {
            case 'tahunan':
                $annualSummary = $this->leaveBalanceService->getAnnualLeaveSummary($user);
                $available     = max(0, $annualSummary['total_available']);

                if ($days > $available) {
                    throw ValidationException::withMessages([
                        'leave_type' => "Sisa cuti tahunan hanya {$available} hari (maksimal 2 tahun terakhir).",
                    ]);
                }
                break;

            case 'urusan_penting':
                $limit      = 30;
                $remaining  = $limit - $user->important_leave_used_days;
                if ($remaining <= 0 || $days > $remaining) {
                    throw ValidationException::withMessages([
                        'leave_type' => 'Kuota cuti urusan penting (30 hari) sudah habis.',
                    ]);
                }
                break;

            case 'cuti_besar':
                $hireDate = $user->hire_date;
                if (! $hireDate || $hireDate->diffInYears(now()) < 5) {
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
                if ($user->non_active_leave_used_days > 0) {
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

                if ($user->maternity_leave_used_count >= 3) {
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
                $remaining = $limit - $user->sick_leave_used_days;

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
            throw ValidationException::withMessages([
                'start_date' => 'Tanggal pengajuan bertabrakan dengan cuti lain yang sudah disetujui.',
            ]);
        }
    }

    private function buildQuotaCards($user, array $annualSummary): array
    {
        $now            = now();
        $annualRemaining = $annualSummary['total_available'] ?? 0;
        $importantRemaining = max(0, 30 - ($user->important_leave_used_days ?? 0));
        $sickRemaining = max(0, 540 - ($user->sick_leave_used_days ?? 0));

        $bigEligible = $user->hire_date && $user->hire_date->diffInYears($now) >= 5;
        $bigCooldownEnds = $user->big_leave_last_used_at
            ? $user->big_leave_last_used_at->copy()->addYears(5)
            : null;
        $bigAvailable = ($bigEligible && (! $bigCooldownEnds || $bigCooldownEnds->isPast())) ? 90 : 0;

        $nonActiveAvailable = ($user->non_active_leave_used_days ?? 0) > 0 ? 0 : 1;
        $maternityRemaining = $user->gender === 'female'
            ? max(0, 3 - ($user->maternity_leave_used_count ?? 0))
            : 0;

        return [
            [
                'code'      => 'tahunan',
                'label'     => 'Cuti Tahunan',
                'remaining' => $annualRemaining,
                'unit'      => 'hari',
                'note'      => 'Termasuk carry-over maksimal 2 tahun terakhir',
            ],
            [
                'code'      => 'urusan_penting',
                'label'     => 'Cuti Urusan Penting',
                'remaining' => $importantRemaining,
                'unit'      => 'hari',
                'note'      => 'Hak total 30 hari sepanjang karir',
            ],
            [
                'code'      => 'cuti_besar',
                'label'     => 'Cuti Besar',
                'remaining' => $bigAvailable,
                'unit'      => 'hari',
                'note'      => $bigEligible
                    ? ($bigAvailable > 0
                        ? 'Masa kerja ≥5 tahun, dapat 90 hari per 5 tahun'
                        : 'Sudah dipakai, bisa ajukan lagi setelah ' . optional($bigCooldownEnds)->format('d M Y'))
                    : 'Tunggu sampai masa kerja 5 tahun',
            ],
            [
                'code'      => 'cuti_non_aktif',
                'label'     => 'Cuti Non-Aktif',
                'remaining' => $nonActiveAvailable,
                'unit'      => 'x',
                'note'      => 'Durasi 1-2 tahun, hanya sekali seumur hidup',
            ],
            [
                'code'      => 'cuti_bersalin',
                'label'     => 'Cuti Bersalin',
                'remaining' => $maternityRemaining * 90,
                'unit'      => 'hari',
                'note'      => $user->gender === 'female'
                    ? 'Khusus pegawai perempuan, maks 3 kali (90 hari per persalinan)'
                    : 'Hanya untuk pegawai perempuan',
            ],
            [
                'code'      => 'cuti_sakit',
                'label'     => 'Cuti Sakit',
                'remaining' => $sickRemaining,
                'unit'      => 'hari',
                'note'      => 'Total hak 540 hari sepanjang karir',
            ],
        ];
    }
}


