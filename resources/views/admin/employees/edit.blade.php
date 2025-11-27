@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-slate-800">Edit Pegawai</h1>

    <a href="{{ route('admin.employees.index') }}"
        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
        Kembali
    </a>
</div>

<div class="bg-white shadow rounded-lg p-6">
    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- NAMA -->
        <div>
            <label class="block font-medium text-slate-700 mb-1">Nama Pegawai</label>
            <input type="text"
                name="name"
                value="{{ old('name', $employee->name) }}"
                required
                class="w-full border rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
            >
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- EMPLOYEE ID -->
        <div>
            <label class="block font-medium text-slate-700 mb-1">NIP / ID Pegawai</label>
            <input type="text"
                name="employee_id"
                value="{{ old('employee_id', $employee->employee_id) }}"
                required
                class="w-full border rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
            >
            @error('employee_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- GENDER -->
        <div>
            <label class="block font-medium text-slate-700 mb-1">Gender</label>
            <select name="gender" required
                    class="w-full border rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200 bg-white">
                <option value="">Pilih gender</option>
                <option value="male" {{ old('gender', $employee->gender) === 'male' ? 'selected' : '' }}>Pria</option>
                <option value="female" {{ old('gender', $employee->gender) === 'female' ? 'selected' : '' }}>Wanita</option>
            </select>
            @error('gender')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- BUTTON SUBMIT -->
        <div class="pt-4">
            <button
                type="submit"
                class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition font-medium"
            >
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

@endsection
