@extends('layouts.guest')

@section('title', 'Register')
@section('heading', 'Registrasi Karyawan')
@section('subheading', 'Daftar untuk mengakses Aplikasi Cuti')

@section('content')
<form method="POST" action="{{ url('/register') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Karyawan</label>
            <input type="text" name="employee_id" value="{{ old('employee_id') }}" required
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
            <input type="text" name="position" value="{{ old('position') }}" required
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
        <input type="text" name="department" value="{{ old('department') }}"
               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
    </div>

    <button type="submit"
            class="w-full py-2 px-4 bg-blue-700 text-white text-sm font-semibold rounded-lg hover:bg-blue-800">
        Daftar
    </button>

    <p class="text-center text-xs text-gray-500 mt-2">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
    </p>
</form>
@endsection
