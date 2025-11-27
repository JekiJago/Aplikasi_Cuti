<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminEmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')
            ->orderBy('name')
            ->paginate(12);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'string', 'max:50', 'unique:users,employee_id'],
            'gender'      => ['required', Rule::in(['male', 'female'])],
        ]);

        $employeeId = trim($validated['employee_id']);
        $email      = sprintf('%s@pegawai.local', Str::slug($employeeId));

        User::create([
            'name'        => $validated['name'],
            'employee_id' => $employeeId,
            'email'       => $email,
            'role'        => 'employee',
            'gender'      => $validated['gender'],
            'password'    => $employeeId, // auto hash via accessor
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function show($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        return view('admin.employees.show', compact('employee'));
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
            'employee_id' => ['required', 'string', 'max:50', 'unique:users,employee_id,' . $employee->id],
            'gender'      => ['required', Rule::in(['male', 'female'])],
        ]);

        $employeeId = trim($validated['employee_id']);

        $employee->update([
            'name'        => $validated['name'],
            'employee_id' => $employeeId,
            'email'       => sprintf('%s@pegawai.local', Str::slug($employeeId)),
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
}


