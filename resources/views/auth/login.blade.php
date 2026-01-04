@extends('layouts.guest')

@section('title', 'Login')
@section('heading', 'Login')
@section('subheading', 'Masuk ke Aplikasi Cuti')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-teal-500 rounded-2xl flex items-center justify-center shadow-xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900">Sistem Manajemen Cuti</h1>
            <p class="mt-2 text-gray-600">Masuk untuk mengelola pengajuan cuti pegawai</p>
        </div>

        <!-- Card -->
        <div class="bg-white py-8 px-6 shadow-2xl rounded-3xl border border-gray-100">

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-6">
                @csrf

                <!-- hidden login type -->
                <input type="hidden" name="login_type" id="login_type" value="auto">

                <!-- Login -->
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700 mb-2">
                        Username / Email
                    </label>

                    <input type="text"
                           id="login"
                           name="login"
                           value="{{ old('login') }}"
                           required
                           autofocus
                           placeholder="ID Pegawai atau Email Admin"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Kata Sandi (maksimal 8 karakter)
                    </label>

                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               maxlength="8"
                               placeholder="Maksimal 8 karakter"
                               class="w-full px-4 py-3 pr-14 rounded-lg border border-gray-300 bg-gray-50
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white">

                        <!-- ICON MATA (SUDAH DIGESER KE KANAN) -->
                        <button type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-4 flex items-center text-gray-500">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember -->
                <div class="flex items-center">
                    <input type="checkbox"
                           name="remember"
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full py-3 rounded-lg bg-gradient-to-r from-blue-600 to-teal-600
                               text-white font-semibold hover:from-blue-700 hover:to-teal-700 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Butuh bantuan login? Hubungi administrator
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const loginTypeInput = document.getElementById('login_type');
    const toggleBtn = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    // hapus spasi
    loginInput.addEventListener('input', () => {
        loginInput.value = loginInput.value.replace(/\s/g, '');
    });

    // toggle password
    toggleBtn.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeIcon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
    });

    // detect login type
    document.getElementById('loginForm').addEventListener('submit', (e) => {
        const value = loginInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!value || !passwordInput.value) {
            e.preventDefault();
            alert('Login dan password wajib diisi');
            return;
        }

        if (passwordInput.value.length > 8) {
            e.preventDefault();
            alert('Password maksimal 8 karakter');
            return;
        }

        loginTypeInput.value = emailRegex.test(value) ? 'email' : 'employee_id';
    });
});
</script>
@endpush
@endsection
