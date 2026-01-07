<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AnnualQuota;
use App\Models\QuotaAdjustment;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\LeaveBalanceService;
use Carbon\Carbon;

class AdminEmployeeController extends Controller
{
    protected $leaveBalanceService;

    public function __construct(LeaveBalanceService $leaveBalanceService)
    {
        $this->leaveBalanceService = $leaveBalanceService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = User::where('role', 'employee')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('employee_id', 'like', "%$search%");
                });
            })
            ->orderBy('name')
            ->paginate(12);

        // HITUNG CUTI AKTIF - PAKAI SERVICE YANG SAMA
        $employees->getCollection()->transform(function ($employee) {

    // === SAMAKAN DENGAN DETAIL PEGAWAI (FIFO) ===
    $fifoBreakdown = AnnualQuota::getFIFOBreakdown($employee->id);

    $quotas = $fifoBreakdown['quotas'] ?? [];
    $previousYear = date('Y') - 1;
    $currentYear = date('Y');

    $prevRemaining = $quotas[$previousYear]['remaining'] ?? 0;
    $currentRemaining = $quotas[$currentYear]['remaining'] ?? 0;

    // CUTI AKTIF = SISA TAHUN LALU + TAHUN INI
    $employee->active_leave_balance = $prevRemaining + $currentRemaining;

    // (opsional) simpan detail kalau mau dipakai di view
    $employee->annual_summary = [
        'previous_year_remaining' => $prevRemaining,
        'current_year_remaining' => $currentRemaining,
        'total_available' => $employee->active_leave_balance,
    ];

    return $employee;
});


        return view('admin.employees.index', compact('employees', 'search'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        Log::info('Store Employee - Request Data:', $request->all());
        
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'employee_id' => [
                'required',
                'string',
                'max:50',
                'unique:users,employee_id',
                'regex:/^\d{6,20}$/'
            ],
            'gender'   => ['required', Rule::in(['male', 'female'])],
            'hire_date' => ['required', 'date'], // PERBAIKAN: ganti dari join_date ke hire_date
            'password' => ['required', 'confirmed', 'min:6'],
            'annual_leave_quota' => ['required', 'integer', 'min:0', 'max:365'], // PERBAIKAN: tambahkan ini
        ]);

        Log::info('Store Employee - Validated Data:', $validated);

        $employeeId = trim($validated['employee_id']);

        $employee = User::create([
            'name'        => $validated['name'],
            'employee_id' => $employeeId,
            'email'       => "{$employeeId}@pegawai.local",
            'role'        => 'employee',
            'gender'      => $validated['gender'],
            'hire_date'   => $validated['hire_date'], // PERBAIKAN: tambahkan ini
            'annual_leave_quota' => $validated['annual_leave_quota'] ?? 12, // PERBAIKAN: tambahkan ini
            'login_type'  => 'employee_id',
            'password'    => Hash::make($validated['password']),
            'sick_leave_quota' => 12,
            'personal_leave_quota' => 12,
            'important_leave_quota' => 30,
            'big_leave_quota' => 90,
            'non_active_leave_quota' => 365,
            'maternity_leave_quota' => 90,
            'paternity_leave_quota' => 14,
            'marriage_leave_quota' => 3,
            'important_leave_used_days' => 0,
            'sick_leave_used_days' => 0,
            'big_leave_used_days' => 0,
            'non_active_leave_used_days' => 0,
            'maternity_leave_used_count' => 0,
        ]);

        Log::info('Store Employee - Created Employee:', $employee->toArray());

        $employee->generateYearlyQuotas();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function show($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $leaves = $employee->leaveRequests()->latest()->get();
        
        // PAKAI METODE BARU UNTUK PERHITUNGAN FIFO YANG BENAR
        $fifoBreakdown = AnnualQuota::getFIFOBreakdown($employee->id);
        
        // Data dari FIFO breakdown
        $quotas = $fifoBreakdown['quotas'] ?? [];
        $previousYear = date('Y') - 1;
        $currentYear = date('Y');
        
        $prevYearData = $quotas[$previousYear] ?? ['total' => 0, 'used' => 0, 'remaining' => 0];
        $currentYearData = $quotas[$currentYear] ?? ['total' => 0, 'used' => 0, 'remaining' => 0];
        
        // Hitung total sisa aktif
        $totalActiveLeave = $prevYearData['remaining'] + $currentYearData['remaining'];
        
        // Detail per tahun untuk tampilan
        $activeLeaveDetails = [];
        foreach ([$previousYear, $currentYear] as $year) {
            $quota = AnnualQuota::where('user_id', $employee->id)
                ->where('year', $year)
                ->first();
                
            $data = $quotas[$year] ?? ['total' => 0, 'used' => 0, 'remaining' => 0];
            
            $activeLeaveDetails[] = [
                'year' => $year,
                'total' => $data['total'],
                'used' => $data['used'],
                'remaining' => $data['remaining'],
                'is_active' => !($quota->is_expired ?? false),
                'is_expired' => $quota->is_expired ?? false,
            ];
        }
        
        // Hitung CUTI TERPAKAI 2025 dengan benar
        $currentYearUsed = $currentYearData['used'] ?? 0;
        $currentYearQuota = $currentYearData['total'] ?? ($employee->annual_leave_quota ?? 12);
        
        // Hitung berapa dari kuota 2024 yang digunakan untuk cuti di 2025
        $usedFromPrev = $fifoBreakdown['used_from_previous_for_current_year'] ?? 0;
        $usedFromCurrent = $currentYearUsed - $usedFromPrev;
        
        // Data untuk tab ringkasan
        $summary = [
            'pending'  => $leaves->where('status', 'pending')->count(),
            'approved' => $leaves->where('status', 'approved')->count(),
            'rejected' => $leaves->where('status', 'rejected')->count(),
        ];

        return view('admin.employees.show', compact(
            'employee',
            'leaves',
            'summary',
            'totalActiveLeave',
            'activeLeaveDetails',
            'currentYear',
            'previousYear',
            'currentYearUsed',
            'currentYearQuota',
            'usedFromPrev',
            'usedFromCurrent',
            'prevYearData',
            'currentYearData'
        ));
    }

    public function edit($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        // Pastikan kuota tahunan ada
        $employee->generateYearlyQuotas();
        
        // Dapatkan semua kuota tahunan untuk form edit
        $annualQuotas = AnnualQuota::where('user_id', $employee->id)
            ->orderBy('year', 'desc')
            ->get();
            
        // Dapatkan riwayat penyesuaian kuota
        $quotaAdjustments = QuotaAdjustment::where('user_id', $employee->id)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.employees.edit', compact(
            'employee',
            'annualQuotas',
            'quotaAdjustments'
        ));
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        // PERBAIKAN: Cek apakah ini request untuk penyesuaian kuota khusus
        // Cek dari action field atau quota_adjustment_year field
        $isQuotaAdjustment = $request->input('action') === 'adjust_quota' || $request->has('quota_adjustment_year');

        if ($isQuotaAdjustment && $request->has('quota_adjustment_year')) {
            Log::info('Processing quota adjustment for employee: ' . $employee->id);
            return $this->handleQuotaAdjustment($request, $employee);
        }

        Log::info('AdminEmployeeController Update - Request Data:', $request->all());

        // VALIDASI DASAR - PASSWORD OPSIONAL, TIDAK ADA FIELD LIFETIME YANG DIVALIDASI
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'employee_id' => [
                'required',
                'string',
                'max:50',
                'unique:users,employee_id,' . $employee->id,
                'regex:/^\d{6,20}$/'
            ],
            'gender'      => ['required', Rule::in(['male', 'female'])],
            'hire_date'   => ['required', 'date'],
            'password'    => ['nullable', 'confirmed', 'min:6'], // PERBAIKAN: password nullable
            'annual_leave_quota' => ['required', 'integer', 'min:0', 'max:365'],
        ]);

        Log::info('AdminEmployeeController Update - Validated Data:', $validated);

        // SIAPKAN DATA UNTUK UPDATE
        $updateData = [
            'name'        => $validated['name'],
            'employee_id' => $validated['employee_id'],
            'email'       => "{$validated['employee_id']}@pegawai.local",
            'gender'      => $validated['gender'],
            'hire_date'   => $validated['hire_date'],
            'annual_leave_quota' => $validated['annual_leave_quota'],
        ];

        // UPDATE PASSWORD HANYA JIKA DIISI
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
            Log::info('Password akan diupdate untuk employee: ' . $employee->id);
        } else {
            Log::info('Password tidak diupdate (field kosong) untuk employee: ' . $employee->id);
        }

        Log::info('AdminEmployeeController Update - Data to Save:', $updateData);
        
        try {
            $employee->update($updateData);
            Log::info('AdminEmployeeController Update - Success for Employee ID: ' . $employee->id);
        } catch (\Exception $e) {
            Log::error('AdminEmployeeController Update - Error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }

        // Update yearly quotas jika ada (opsional)
        if ($request->has('annual_quota')) {
            Log::info('AdminEmployeeController Update - Annual Quota Data:', $request->annual_quota);
            
            foreach ($request->annual_quota as $year => $quota) {
                // Cari kuota yang sudah ada
                $existingQuota = AnnualQuota::where([
                    'user_id' => $employee->id,
                    'year' => $year
                ])->first();
                
                // Pertahankan used_days yang sudah ada
                $usedDays = $existingQuota ? $existingQuota->used_days : 0;
                
                $currentYear = date('Y');
                $isExpired = $year < ($currentYear - 1);
                
                AnnualQuota::updateOrCreate(
                    [
                        'user_id' => $employee->id,
                        'year' => $year
                    ],
                    [
                        'annual_quota' => $quota,
                        'used_days' => $usedDays, // ✅ Pertahankan nilai used_days
                        'is_expired' => $isExpired
                    ]
                );
            }
        }

        if ($request->has('reset_quota')) {
            Log::info('AdminEmployeeController Update - Reset Quota Data:', $request->reset_quota);
            
            foreach ($request->reset_quota as $year => $reset) {
                if ($reset) {
                    $currentYear = date('Y');
                    $isExpired = $year < ($currentYear - 1);
                    
                    AnnualQuota::updateOrCreate(
                        [
                            'user_id' => $employee->id,
                            'year' => $year
                        ],
                        [
                            'annual_quota' => $employee->annual_leave_quota ?? 12,
                            'used_days' => 0, // ✅ Ini benar karena RESET memang mengulang dari 0
                            'is_expired' => $isExpired
                        ]
                    );
                }
            }
        }

        $this->leaveBalanceService->refreshExpiredQuotas();

        return redirect()->route('admin.employees.show', $employee->id)
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Handle penyesuaian kuota khusus (FIX FINAL)
     */
    private function handleQuotaAdjustment(Request $request, $employee)
    {
        Log::info('Handle Quota Adjustment - Request Data:', $request->all());

        // PERBAIKAN: Validasi menerima nilai negatif
        $request->validate([
            'quota_adjustment_year' => 'required|integer|min:2000|max:' . (date('Y') + 2),
            'quota_adjustment_quota' => 'required|integer|min:-365|max:365',
            'quota_adjustment_reason' => 'required|string|max:500',
        ]);

        try {
            // 1️⃣ Ambil kuota tahunan yang ada (atau buat jika belum ada)
            $annualQuota = AnnualQuota::firstOrCreate(
                [
                    'user_id' => $employee->id,
                    'year' => $request->quota_adjustment_year,
                ],
                [
                    'annual_quota' => $employee->annual_leave_quota ?? 12,
                    'used_days' => 0,
                    'is_expired' => false,
                ]
            );

            $oldQuota = $annualQuota->annual_quota;
            $usedDays = $annualQuota->used_days;

            // 2️⃣ HITUNG KUOTA BARU (INI KUNCI)
            $newQuota = $oldQuota + $request->quota_adjustment_quota;

            // 3️⃣ VALIDASI LOGIS - Pastikan kuota baru tidak negatif
            if ($newQuota < 0) {
                return back()->with(
                    'error',
                    'Kuota tidak boleh negatif. Kuota lama: ' . $oldQuota . ', Penyesuaian: ' . $request->quota_adjustment_quota . ' = ' . $newQuota
                );
            }

            // 4️⃣ VALIDASI LOGIS - Pastikan kuota baru tidak lebih kecil dari cuti terpakai
            if ($newQuota < $usedDays) {
                return back()->with(
                    'error',
                    'Kuota baru (' . $newQuota . ') tidak boleh lebih kecil dari cuti yang sudah terpakai (' . $usedDays . ').'
                );
            }

            // 5️⃣ UPDATE KUOTA UTAMA (BUKAN DITIMPA)
            $annualQuota->update([
                'annual_quota' => $newQuota,
                'is_expired' => $request->quota_adjustment_year < (date('Y') - 1),
            ]);

            // 6️⃣ SIMPAN RIWAYAT PENYESUAIAN
            QuotaAdjustment::create([
                'user_id' => $employee->id,
                'admin_id' => auth()->id(),
                'year' => $request->quota_adjustment_year,
                'old_quota' => $oldQuota,
                'new_quota' => $newQuota,
                'adjustment_amount' => $request->quota_adjustment_quota,
                'reason' => $request->quota_adjustment_reason,
            ]);

            // 7️⃣ REFRESH FIFO
            $this->leaveBalanceService->refreshExpiredQuotas();

            Log::info('Quota adjustment SUCCESS for employee: ' . $employee->id);
            Log::info('Old quota: ' . $oldQuota . ', Adjustment: ' . $request->quota_adjustment_quota . ', New quota: ' . $newQuota);

            return redirect()
                ->route('admin.employees.show', $employee->id)
                ->with('success', 'Penyesuaian kuota berhasil. Kuota baru: ' . $newQuota . ' hari (dari ' . $oldQuota . ' hari).');

        } catch (\Exception $e) {
            Log::error('Quota adjustment FAILED: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal menyesuaikan kuota: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        $employee->annualQuotas()->delete();
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }

    public function resetPassword(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        $request->validate([
            'password' => ['required', 'confirmed', 'min:6'],
        ]);
        
        $employee->update([
            'password' => Hash::make($request->password)
        ]);
        
        return back()->with('success', 'Password berhasil direset.');
    }

    /**
     * Reset kuota tahun tertentu
     */
    public function resetQuota(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        $request->validate([
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);
        
        try {
            // RESET kuota memang harus mengembalikan used_days ke 0
            AnnualQuota::where('user_id', $employee->id)
                ->where('year', $request->year)
                ->update(['used_days' => 0]);
            
            return back()->with('success', 'Kuota tahun ' . $request->year . ' berhasil direset.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mereset kuota: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $employees = User::where('role', 'employee')
            ->with(['annualQuotas' => function($query) {
                $query->where('is_expired', false)
                      ->orderBy('year', 'desc');
            }])
            ->orderBy('name')
            ->get()
            ->map(function ($employee) {
                $annualSummary = $this->leaveBalanceService->getAnnualLeaveSummary($employee);
                
                return [
                    'NIP' => $employee->employee_id,
                    'Nama' => $employee->name,
                    'Gender' => $employee->gender === 'male' ? 'Laki-laki' : 'Perempuan',
                    'Tanggal Bergabung' => $employee->hire_date?->format('d/m/Y'),
                    'Departemen' => $employee->department ?? '-',
                    'Posisi' => $employee->position ?? '-',
                    'Sisa Cuti Aktif' => $annualSummary['total_available'],
                    'Cuti Tahun Ini' => $annualSummary['current_year_available'] ?? 0,
                    'Cuti Tahun Lalu' => $annualSummary['previous_year_available'] ?? 0,
                    'Kuota Tahunan Default' => $employee->annual_leave_quota ?? 12,
                    'Status' => 'Aktif',
                ];
            });
        
        return response()->json($employees);
    }

    public function bulkUpdateQuotas(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:' . (date('Y') - 5) . '|max:' . (date('Y') + 2),
            'quota_days' => 'required|integer|min:1|max:365',
            'reason' => 'required|string|max:500',
        ]);
        
        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        
        if ($request->has('employee_ids')) {
            foreach ($request->employee_ids as $employeeId) {
                try {
                    $employee = User::find($employeeId);
                    if ($employee) {
                        // === PERBAIKAN: Cari kuota yang sudah ada ===
                        $existingQuota = AnnualQuota::where([
                            'user_id' => $employeeId,
                            'year' => $request->year,
                        ])->first();
                        
                        $usedDays = $existingQuota ? $existingQuota->used_days : 0;
                        
                        AnnualQuota::updateOrCreate(
                            [
                                'user_id' => $employeeId,
                                'year' => $request->year,
                            ],
                            [
                                'annual_quota' => $request->quota_days,
                                'used_days' => $usedDays, // ✅ Pertahankan nilai used_days
                                'is_expired' => $request->year < (date('Y') - 1),
                            ]
                        );
                        $successCount++;
                    }
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Pegawai ID $employeeId: " . $e->getMessage();
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Berhasil memperbarui $successCount pegawai, gagal: $failedCount",
            'errors' => $errors,
        ]);
    }
}