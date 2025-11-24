@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8 px-4">
    <h1 class="text-xl font-semibold text-slate-900 mb-1">Tambah Pegawai</h1>
    <p class="text-sm text-slate-500 mb-6">Masukkan nama dan NIP pegawai baru.</p>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Nama Pegawai <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    NIP / ID Pegawai <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Contoh: 198501012010">
                @error('employee_id')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex items-center justify-end space-x-2">
                <a href="{{ route('admin.employees.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-full border border-slate-200 text-sm text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
