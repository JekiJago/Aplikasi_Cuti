@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-slate-800">Edit Pegawai</h1>

    <x-link-button href="{{ route('admin.employees.index') }}" type="secondary">
        Kembali
    </x-link-button>
</div>

<div class="bg-white shadow rounded-lg p-6">
    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- NAMA -->
        <div>
            <label class="block font-medium text-slate-700 mb-1">Nama Pegawai</label>
            <x-input type="text" name="name" :value="old('name', $employee->name)" required />
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- EMPLOYEE ID -->
        <div>
            <label class="block font-medium text-slate-700 mb-1">NIP / ID Pegawai</label>
            <x-input type="text" name="employee_id" :value="old('employee_id', $employee->employee_id)" required />
            @error('employee_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- GENDER -->
        <div>
                <label class="block font-medium text-slate-700 mb-1">Gender</label>
                <x-select name="gender" required>
                    <option value="">Pilih gender</option>
                    <option value="male" {{ old('gender', $employee->gender) === 'male' ? 'selected' : '' }}>Pria</option>
                    <option value="female" {{ old('gender', $employee->gender) === 'female' ? 'selected' : '' }}>Wanita</option>
                </x-select>
            @error('gender')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- BUTTON SUBMIT -->
        <div class="pt-4">
            <x-primary-button>
                Simpan Perubahan
            </x-primary-button>
        </div>

    </form>
</div>

@endsection
