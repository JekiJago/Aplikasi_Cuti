@extends('layouts.admin')

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

        <!-- NIP -->
        <div>
            <label class="block font-medium text-slate-700 mb-1">NIP</label>
            <input type="text"
                name="nip"
                value="{{ old('nip', $employee->nip) }}"
                required
                class="w-full border rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
            >
            @error('nip')
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
