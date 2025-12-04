@extends('layouts.guest')

@section('title', 'Register')
@section('heading', 'Registrasi Karyawan')
@section('subheading', 'Daftar untuk mengakses Aplikasi Cuti')

@section('content')
<form method="POST" action="{{ url('/register') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <x-input type="text" name="name" :value="old('name')" required />
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <x-input type="email" name="email" :value="old('email')" required />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Karyawan</label>
            <x-input type="text" name="employee_id" :value="old('employee_id')" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
            <x-input type="text" name="position" :value="old('position')" />
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
        <x-input type="text" name="department" :value="old('department')" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <x-input type="password" name="password" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <x-input type="password" name="password_confirmation" required />
        </div>
    </div>

    <x-primary-button class="w-full justify-center">
        Daftar
    </x-primary-button>

    <p class="text-center text-xs text-gray-500 mt-2">
        Sudah punya akun?
        <x-link-button href="{{ route('login') }}" type="default" class="text-xs">
            Login
        </x-link-button>
    </p>
</form>
@endsection
