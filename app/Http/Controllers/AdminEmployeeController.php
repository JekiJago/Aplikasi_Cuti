<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\Cuti;
use App\Models\KuotaTahunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = User::where('role', 'employee')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('pegawai_id', 'like', "%$search%");
                });
            })
            ->with('pegawai.cuti')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.employees.index', compact('employees', 'search'));
    }

    public function create()
    {
        $pegawais = Pegawai::all();
        return view('admin.employees.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:pegawai,nip',
            'nrp' => 'required|string|max:50|unique:pegawai,nrp',
            'previous_year_quota' => 'nullable|integer|min:0|max:12',
            'current_year_quota' => 'required|integer|min:0|max:12',
            'gender' => 'required|in:male,female',
        ]);

        try {
            $user = null;
            
            DB::transaction(function () use ($validated, &$user) {
                // Create pegawai record first
                $pegawai = Pegawai::create([
                    'nip' => $validated['employee_id'],
                    'nrp' => $validated['nrp'],
                    'nama' => $validated['name'],
                    'jenis_kelamin' => $validated['gender'],
                ]);

                // Create user account with NRP as password
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['employee_id'] . '@pegawai.local',
                    'password' => Hash::make($validated['nrp']),
                    'pegawai_id' => $pegawai->nip,
                    'role' => 'employee',
                    'login_type' => 'nip',
                ]);

                // Create cuti (leave quota) record (legacy)
                Cuti::create([
                    'pegawai_id' => $pegawai->id,
                    'kuota_tahunan' => $validated['current_year_quota'],
                    'cuti_dipakai' => 0,
                ]);

                // Create kuota tahunan untuk tahun lalu (jika ada)
                $tahunSekarang = now()->year;
                $tahunLalu = $tahunSekarang - 1;

                if ($validated['previous_year_quota'] > 0) {
                    KuotaTahunan::create([
                        'user_id' => $user->id,
                        'tahun' => $tahunLalu,
                        'kuota' => $validated['previous_year_quota'],
                        'dipakai' => 0,
                        'expired' => false,
                    ]);
                }

                // Create kuota tahunan untuk tahun sekarang
                KuotaTahunan::create([
                    'user_id' => $user->id,
                    'tahun' => $tahunSekarang,
                    'kuota' => $validated['current_year_quota'],
                    'dipakai' => 0,
                    'expired' => false,
                ]);
            });

            return redirect()->route('admin.employees.show', $user->id)
                ->with('success', 'Pegawai berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambah pegawai: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $pegawai = $employee->pegawai;
        $cuti = $pegawai?->cuti;
        
        // Ambil semua kuota tahunan dari database
        $kuotaTahunans = $employee->kuotaTahunans()->get();
        
        // Pastikan selalu ada data untuk tahun berjalan dan tahun sebelumnya
        $currentYear = now()->year;
        $prevYear = $currentYear - 1;
        
        // Cek tahun sebelumnya
        if (!$kuotaTahunans->contains('tahun', $prevYear)) {
            $kuotaTahunans->push((object)[
                'tahun' => $prevYear,
                'kuota' => 0,
                'dipakai' => 0,
                'expired' => false,
            ]);
        }
        
        // Cek tahun berjalan
        if (!$kuotaTahunans->contains('tahun', $currentYear)) {
            $kuotaTahunans->push((object)[
                'tahun' => $currentYear,
                'kuota' => 0,
                'dipakai' => 0,
                'expired' => false,
            ]);
        }
        
        // Urutkan dari tahun terbaru
        $kuotaTahunans = $kuotaTahunans->sortByDesc('tahun');

        return view('admin.employees.show', compact('employee', 'pegawai', 'cuti', 'kuotaTahunans'));
    }

    public function edit($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $pegawais = Pegawai::all();
        
        // Ambil data kuota tahunan untuk tahun berjalan dan tahun sebelumnya
        $currentYear = now()->year;
        $prevYear = $currentYear - 1;
        
        $currentYearQuota = $employee->kuotaTahunans()->where('tahun', $currentYear)->first();
        $currentYearValue = $currentYearQuota ? $currentYearQuota->kuota : 0;
        
        $prevYearQuota = $employee->kuotaTahunans()->where('tahun', $prevYear)->first();
        $prevYearValue = $prevYearQuota ? $prevYearQuota->kuota : 0;

        return view('admin.employees.edit', compact('employee', 'pegawais', 'currentYearValue', 'prevYearValue'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $pegawai = $employee->pegawai;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:pegawai,nip,' . $pegawai->id . ',id',
            'nrp' => 'required|string|max:50|unique:pegawai,nrp,' . $pegawai->id . ',id',
            'annual_leave_quota' => 'required|integer|min:0|max:12',
            'previous_year_quota' => 'nullable|integer|min:0|max:12',
            'gender' => 'required|in:male,female',
        ]);

        try {
            // Update pegawai record
            $pegawai->update([
                'nip' => $validated['employee_id'],
                'nrp' => $validated['nrp'],
                'nama' => $validated['name'],
                'jenis_kelamin' => $validated['gender'],
            ]);

            // Update user name and pegawai_id (in case NIP changed)
            // Also update password hash to match new NRP
            $employee->update([
                'name' => $validated['name'],
                'email' => $validated['employee_id'] . '@pegawai.local',
                'pegawai_id' => $validated['employee_id'], // Updated NIP
                'password' => \Illuminate\Support\Facades\Hash::make($validated['nrp']), // Update password to new NRP
            ]);

            // Update kuota cuti tahun berjalan dan tahun sebelumnya
            $currentYear = now()->year;
            $prevYear = $currentYear - 1;
            
            // Update kuota tahun berjalan (2026)
            $kuotaTahunan = $employee->kuotaTahunans()->where('tahun', $currentYear)->first();
            if ($kuotaTahunan) {
                $kuotaTahunan->update([
                    'kuota' => $validated['annual_leave_quota'],
                ]);
            } else {
                $employee->kuotaTahunans()->create([
                    'tahun' => $currentYear,
                    'kuota' => $validated['annual_leave_quota'],
                    'dipakai' => 0,
                    'expired' => false,
                ]);
            }
            
            // Update kuota tahun lalu (2025) jika ada input
            if ($validated['previous_year_quota'] !== null) {
                $kuotaTahunanlalu = $employee->kuotaTahunans()->where('tahun', $prevYear)->first();
                if ($kuotaTahunanlalu) {
                    $kuotaTahunanlalu->update([
                        'kuota' => $validated['previous_year_quota'],
                    ]);
                } else {
                    $employee->kuotaTahunans()->create([
                        'tahun' => $prevYear,
                        'kuota' => $validated['previous_year_quota'],
                        'dipakai' => 0,
                        'expired' => false,
                    ]);
                }
            }

            return redirect()->route('admin.employees.show', $employee->id)
                ->with('success', 'Data pegawai berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pegawai: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        try {
            $employee->delete();
            return redirect()->route('admin.employees.index')
                ->with('success', 'Pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pegawai: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        $request->validate(['password' => 'required|confirmed|min:6']);
        
        try {
            $employee->update(['password' => Hash::make($request->password)]);
            return back()->with('success', 'Password berhasil direset.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }
}
