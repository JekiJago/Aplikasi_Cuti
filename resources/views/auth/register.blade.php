@extends('layouts.guest')

@section('title', 'Register')
@section('heading', 'Registrasi Karyawan')
@section('subheading', 'Daftar untuk mengakses Aplikasi Cuti')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-teal-500 rounded-2xl flex items-center justify-center shadow-xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900">
                Registrasi Karyawan Baru
            </h1>
            <p class="mt-2 text-gray-600 max-w-md mx-auto">
                Lengkapi data di bawah untuk membuat akun dan mengakses sistem manajemen cuti
            </p>
        </div>

        <!-- Registration Card -->
        <div class="mt-6">
            <div class="bg-white py-8 px-4 shadow-2xl rounded-3xl border border-gray-100 sm:px-10">
                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border border-red-200 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Terdapat kesalahan dalam pengisian form
                                </h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ url('/register') }}" class="space-y-6">
                    @csrf

                    <!-- Personal Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Informasi Pribadi
                        </h3>
                        
                        <!-- Nama -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required
                                       placeholder="Masukkan nama lengkap"
                                       class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 
                                              bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required
                                       placeholder="nama@perusahaan.com"
                                       class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 
                                              bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Informasi Karyawan
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nomor Karyawan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Karyawan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="employee_id" 
                                           value="{{ old('employee_id') }}" 
                                           required
                                           placeholder="Contoh: KRY-2024-001"
                                           class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 
                                                  bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                  focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                                </div>
                            </div>

                            <!-- Jabatan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jabatan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="position" 
                                           value="{{ old('position') }}" 
                                           required
                                           placeholder="Contoh: Staff IT"
                                           class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 
                                                  bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                  focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                                </div>
                            </div>
                        </div>

                        <!-- Departemen -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Departemen
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="department" 
                                       value="{{ old('department') }}"
                                       placeholder="Contoh: IT, HRD, Keuangan"
                                       class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 
                                              bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Keamanan Akun
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input type="password" 
                                           name="password" 
                                           required
                                           placeholder="Minimal 8 karakter"
                                           class="block w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 
                                                  bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                  focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                                    <button type="button" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                            onclick="togglePasswordVisibility('password')">
                                        <svg id="eye-icon-password" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           required
                                           placeholder="Ulangi password Anda"
                                           class="block w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 
                                                  bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                                  focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                                    <button type="button" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                            onclick="togglePasswordVisibility('password_confirmation')">
                                        <svg id="eye-icon-confirm" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4">
                            <p class="text-sm font-medium text-blue-800 mb-2">Persyaratan Password:</p>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Minimal 8 karakter
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Gunakan kombinasi huruf dan angka
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center items-center py-3.5 px-4 rounded-lg 
                                       bg-gradient-to-r from-blue-600 to-teal-600 text-white font-semibold 
                                       hover:from-blue-700 hover:to-teal-700 hover:shadow-xl 
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 
                                       transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Buat Akun Sekarang
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Sudah memiliki akun?
                            <a href="{{ route('login') }}" 
                               class="font-medium text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                Masuk ke akun Anda
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer Note -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Dengan mendaftar, Anda menyetujui ketentuan penggunaan sistem.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePasswordVisibility(fieldName) {
        const passwordInput = document.querySelector(`input[name="${fieldName}"]`);
        const eyeIcon = document.getElementById(`eye-icon-${fieldName === 'password' ? 'password' : 'confirm'}`);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }
</script>
@endpush

<style>
    /* Custom focus styles */
    input:focus, button:focus {
        outline: none;
        ring-offset-color: transparent;
    }
    
    /* Smooth transitions */
    * {
        transition-property: background-color, border-color, transform, box-shadow;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Password toggle button styling */
    button[onclick^="togglePasswordVisibility"] {
        background: none;
        border: none;
        cursor: pointer;
    }
    
    button[onclick^="togglePasswordVisibility"]:hover svg {
        color: #4b5563;
    }
    
    /* Required field indicator */
    span.text-red-500 {
        font-weight: bold;
    }
</style>
@endsection