<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
            'annual_leave_quota' => 'required|integer|min:0|max:365',
            'gender' => 'required|in:male,female',
        ]);

        try {
            // Create pegawai record first
            $pegawai = Pegawai::create([
                'nip' => $validated['employee_id'],
                'nrp' => $validated['nrp'],
                'nama' => $validated['name'],
                'jenis_kelamin' => $validated['gender'],
            ]);

            // Create user account with NRP as password
            // Note: pegawai_id should reference the nip (not id) based on foreign key
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['employee_id'] . '@pegawai.local',
                'password' => Hash::make($validated['nrp']),
                'pegawai_id' => $pegawai->nip,  // Use nip instead of id
                'role' => 'employee',
                'login_type' => 'nip',
            ]);

            // Create cuti (leave quota) record
            Cuti::create([
                'pegawai_id' => $pegawai->id,
                'kuota_tahunan' => $validated['annual_leave_quota'],
                'cuti_dipakai' => 0,
            ]);

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

        return view('admin.employees.show', compact('employee', 'pegawai', 'cuti'));
    }

    public function edit($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $pegawais = Pegawai::all();

        return view('admin.employees.edit', compact('employee', 'pegawais'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $pegawai = $employee->pegawai;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:pegawai,nip,' . $pegawai->id . ',id',
            'nrp' => 'required|string|max:50|unique:pegawai,nrp,' . $pegawai->id . ',id',
            'annual_leave_quota' => 'required|integer|min:0|max:365',
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
            $employee->update([
                'name' => $validated['name'],
                'email' => $validated['employee_id'] . '@pegawai.local',
                'pegawai_id' => $validated['employee_id'], // Updated NIP
            ]);

            // Update cuti quota
            if ($pegawai->cuti) {
                $pegawai->cuti->update([
                    'kuota_tahunan' => $validated['annual_leave_quota'],
                ]);
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
