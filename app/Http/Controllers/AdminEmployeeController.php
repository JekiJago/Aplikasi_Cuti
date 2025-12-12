<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $employees = User::where('role', 'employee')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('employee_id', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('name')
            ->paginate(12);

        return view('admin.employees.index', compact('employees', 'search'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'employee_id' => [
                'required', 
                'string', 
                'max:50', 
                'unique:users,employee_id',
                'regex:/^\d{6,20}$/'
            ],
            'gender'      => ['required', Rule::in(['male', 'female'])],
        ]);

        $employeeId = trim($validated['employee_id']);
        
        // GENERATE PASSWORD: employee_id saja (max 8 karakter)
        $password = User::generateEmployeePassword($employeeId);
        
        // EMAIL OTOMATIS
        $email = sprintf('%s@pegawai.local', $employeeId);

        User::create([
            'name'        => $validated['name'],
            'employee_id' => $employeeId,
            'email'       => $email,
            'role'        => 'employee',
            'gender'      => $validated['gender'],
            'password'    => $password,
            'login_type'  => 'employee_id',
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil ditambahkan.')
            ->with('password_info', [
                'employee_id' => $employeeId,
                'password' => $password,
                'message' => 'Password: ' . $password . ' (sama dengan ID Pegawai)'
            ]);
    }

    public function show($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        // Ambil semua pengajuan cuti sesuai relasi User.php
        $leaves = $employee->leaveRequests()->latest()->get();

        // Summary status
        $summary = [
            'pending'  => $leaves->where('status', 'pending')->count(),
            'approved' => $leaves->where('status', 'approved')->count(),
            'rejected' => $leaves->where('status', 'rejected')->count(),
        ];

        // Data cuti tahunan
        $quota = $employee->annual_leave_quota ?? 12;
        $used = $employee->used_leave_days ?? 0;
        $remaining = $quota - $used;

        // Tidak ada di database → isi 0 saja agar tidak error
        $expiring = 0;

        // Annual summary placeholder
        $annualSummary = [
            'carry_over_expires_at' => null,
        ];

        return view('admin.employees.show', compact(
            'employee',
            'leaves',
            'summary',
            'quota',
            'used',
            'remaining',
            'expiring',
            'annualSummary'
        ));
    }

    public function edit($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

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
        ]);

        $employeeId = trim($validated['employee_id']);

        $employee->update([
            'name'        => $validated['name'],
            'employee_id' => $employeeId,
            'email'       => sprintf('%s@pegawai.local', $employeeId),
            'gender'      => $validated['gender'],
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }
    
    /**
     * Method untuk reset password pegawai
     */
    public function resetPassword(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        $request->validate([
            'password_type' => 'required|in:default,custom',
            'custom_password' => 'required_if:password_type,custom|min:6|max:8',
        ]);

        if ($request->password_type === 'default') {
            // Reset ke default: employee_id saja
            $newPassword = User::generateEmployeePassword($employee->employee_id);
        } else {
            $newPassword = $request->custom_password;
            
            // Validasi maksimal 8 karakter
            if (strlen($newPassword) > 8) {
                return back()->withErrors(['custom_password' => 'Password maksimal 8 karakter']);
            }
        }

        $employee->password = $newPassword;
        $employee->save();

        return back()
            ->with('success', 'Password berhasil direset!')
            ->with('password_info', [
                'employee_id' => $employee->employee_id,
                'password' => $newPassword,
                'message' => 'Password baru: ' . $newPassword
            ]);
    }
}