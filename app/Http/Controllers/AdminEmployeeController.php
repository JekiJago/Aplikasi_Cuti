<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminEmployeeController extends Controller
{
    // LIST PEGAWAI
    public function index()
    {
        $employees = User::where('role', 'employee')
        ->orderBy('name')
        ->paginate(10);

        return view('admin.employees.index', compact('employees'));
    }

    // FORM TAMBAH PEGAWAI
    public function create()
    {
        return view('admin.employees.create');
    }

    // SIMPAN DATA PEGAWAI
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip'  => 'required|string|unique:users,nip'
        ]);

        User::create([
            'name' => $request->name,
            'nip'  => $request->nip,
            'role' => 'employee',
            'password' => bcrypt('password123'), // default pass
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil ditambahkan');
    }

    // DETAIL PEGAWAI
    public function show($id)
    {
        $employee = User::findOrFail($id);
        return view('admin.employees.show', compact('employee'));
    }

    // FORM EDIT PEGAWAI
    public function edit($id)
    {
        $employee = User::findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    // UPDATE DATA PEGAWAI
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nip'  => 'required|string|unique:users,nip,' . $employee->id
        ]);

        $employee->update([
            'name' => $request->name,
            'nip'  => $request->nip,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil diperbarui.');
    }

    // HAPUS PEGAWAI (DELETE)
    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }
}
