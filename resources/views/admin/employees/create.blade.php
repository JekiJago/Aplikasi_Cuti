@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Tambah Pegawai Baru</h1>
        <p class="text-base text-slate-600">Isi formulir di bawah untuk menambahkan pegawai ke sistem.</p>
    </div>

    <div class="card-elevated p-8">
        <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="form-label form-label-required">Nama Pegawai</label>
                <x-input type="text" name="name" :value="old('name')" placeholder="Masukkan nama lengkap" />
                @error('name')
                    <p class="mt-2 text-xs text-rose-600 flex items-center"><span class="mr-1">⚠️</span>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label form-label-required">Gender</label>
                <x-select name="gender">
                    <option value="">Pilih gender</option>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Pria</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Wanita</option>
                </x-select>
                @error('gender')
                    <p class="mt-2 text-xs text-rose-600 flex items-center"><span class="mr-1">⚠️</span>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label form-label-required">NIP / ID Pegawai</label>
                <x-input type="text" name="employee_id" :value="old('employee_id')" placeholder="Contoh: 198501012010" />
                @error('employee_id')
                    <p class="mt-2 text-xs text-rose-600 flex items-center"><span class="mr-1">⚠️</span>{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-3">
                <x-link-button href="{{ route('admin.employees.index') }}" type="secondary">
                    Batal
                </x-link-button>
                <x-primary-button>
                    Simpan Pegawai
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
