@extends('layouts.guest')

@section('title', 'Login')
@section('heading', 'Login')
@section('subheading', 'Masuk ke Aplikasi Cuti')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo/Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-teal-500 rounded-2xl flex items-center justify-center shadow-xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900">
                Sistem Manajemen Cuti
            </h1>
            <p class="mt-2 text-gray-600">
                Masuk untuk mengelola pengajuan cuti pegawai
            </p>
        </div>

        <!-- Login Card -->
        <div class="mt-8">
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
                                @foreach ($errors->all() as $error)
                                    <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                    @csrf
                    
                    <!-- Login Type Selector - RADIO BUTTONS -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih Jenis Login *
                        </label>
                        
                        <!-- Hidden input untuk login_type -->
                        <input type="hidden" name="login_type" id="login_type_input" value="{{ old('login_type', 'employee_id') }}" required>
                        
                        <div class="grid grid-cols-2 gap-3">
                        
                        @error('login_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Login Field -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 mb-2" id="login_label">
                            {{ old('login_type', 'employee_id') == 'employee_id' ? 'ID Pegawai *' : 'Email Admin *' }}
                        </label>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas {{ old('login_type', 'employee_id') == 'employee_id' ? 'fa-id-card' : 'fa-envelope' }} text-gray-400"></i>
                            </div>
                            
                            <input type="{{ old('login_type', 'employee_id') == 'employee_id' ? 'text' : 'email' }}" 
                                   id="login"
                                   name="login" 
                                   value="{{ old('login') }}" 
                                   required 
                                   autofocus
                                   placeholder="{{ old('login_type', 'employee_id') == 'employee_id' ? 'Masukkan ID Pegawai' : 'Masukkan email admin' }}"
                                   class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 
                                          bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                        </div>
                        <p class="mt-1 text-xs text-gray-500" id="login_hint">
                            @if(old('login_type', 'employee_id') == 'employee_id')
                                Contoh: 19850101, USR001, 2023001
                            @else
                                Contoh: admin@gmail.com
                            @endif
                        </p>
                        @error('login')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Kata Sandi (Maksimal 8 karakter) *
                        </label>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            
                            <input type="password" 
                                   id="password"
                                   name="password" 
                                   required
                                   maxlength="8"
                                   placeholder="Masukkan password"
                                   class="block w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 
                                          bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                            
                            <button type="button" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    onclick="togglePasswordVisibility()"
                                    aria-label="Toggle password visibility">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500" id="password_hint">
                            @if(old('login_type', 'employee_id') == 'employee_id')
                                Password sama dengan ID Pegawai (contoh: 19850101)
                            @else
                                Password: admin123
                            @endif
                        </p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="remember" 
                                   {{ old('remember') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 
                                          focus:ring-offset-0 transition-colors">
                            <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center items-center py-3.5 px-4 rounded-lg 
                                       bg-gradient-to-r from-blue-600 to-teal-600 text-white font-semibold 
                                       hover:from-blue-700 hover:to-teal-700 hover:shadow-xl 
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 
                                       transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span id="submit_button_text">
                                {{ old('login_type', 'employee_id') == 'employee_id' ? 'Masuk sebagai Pegawai' : 'Masuk sebagai Admin' }}
                            </span>
                        </button>
                    </div>


@push('scripts')
<script>
    // Fungsi untuk memilih jenis login
    function selectLoginType(type) {
        // Update hidden input
        document.getElementById('login_type_input').value = type;
        
        // Update tampilan button
        const employeeBtn = document.getElementById('employee_btn');
        const adminBtn = document.getElementById('admin_btn');
        const loginLabel = document.getElementById('login_label');
        const loginInput = document.getElementById('login');
        const loginIcon = document.querySelector('#login + div .fas');
        const loginHint = document.getElementById('login_hint');
        const passwordHint = document.getElementById('password_hint');
        const submitButtonText = document.getElementById('submit_button_text');
        const demoEmployee = document.getElementById('demo_employee');
        const demoAdmin = document.getElementById('demo_admin');
        
        // Reset semua button
        employeeBtn.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-700');
        employeeBtn.classList.add('border-gray-300', 'text-gray-700');
        adminBtn.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-700');
        adminBtn.classList.add('border-gray-300', 'text-gray-700');
        
        if (type === 'employee_id') {
            // Style untuk Pegawai terpilih
            employeeBtn.classList.remove('border-gray-300', 'text-gray-700');
            employeeBtn.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-700');
            
            // Update label dan placeholder
            loginLabel.innerHTML = 'ID Pegawai *';
            loginIcon.className = 'fas fa-id-card text-gray-400';
            loginInput.placeholder = 'Masukkan ID Pegawai';
            loginInput.type = 'text';
            loginInput.name = 'login';
            loginHint.innerHTML = 'Contoh: 19850101, USR001, 2023001';
            passwordHint.innerHTML = 'Password sama dengan ID Pegawai (contoh: 19850101)';
            submitButtonText.innerHTML = 'Masuk sebagai Pegawai';
            
            // Tampilkan demo credentials yang sesuai
            demoEmployee.classList.remove('hidden');
            demoAdmin.classList.add('hidden');
            
            // Validasi input untuk ID Pegawai (boleh angka dan huruf)
            loginInput.value = loginInput.value.toUpperCase(); // Buat uppercase
        } else {
            // Style untuk Admin terpilih
            adminBtn.classList.remove('border-gray-300', 'text-gray-700');
            adminBtn.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-700');
            
            // Update label dan placeholder
            loginLabel.innerHTML = 'Email Admin *';
            loginIcon.className = 'fas fa-envelope text-gray-400';
            loginInput.placeholder = 'Masukkan email admin';
            loginInput.type = 'email';
            loginInput.name = 'login';
            loginHint.innerHTML = 'Contoh: admin@gmail.com';
            passwordHint.innerHTML = 'Password: admin123';
            submitButtonText.innerHTML = 'Masuk sebagai Admin';
            
            // Tampilkan demo credentials yang sesuai
            demoAdmin.classList.remove('hidden');
            demoEmployee.classList.add('hidden');
        }
        
        // Focus ke input login
        loginInput.focus();
    }
    
    // Fungsi toggle password visibility
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.querySelector('#password + button i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
    
    // Format input ID Pegawai (uppercase dan tanpa spasi)
    document.getElementById('login').addEventListener('input', function() {
        const loginType = document.getElementById('login_type_input').value;
        
        if (loginType === 'employee_id') {
            // Untuk ID Pegawai: uppercase dan tanpa spasi
            this.value = this.value.toUpperCase().replace(/\s/g, '');
        }
    });
    
    // Validasi form sebelum submit
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const loginType = document.getElementById('login_type_input').value;
        const loginInput = document.getElementById('login').value.trim();
        const passwordInput = document.getElementById('password').value;
        
        // Validasi login type
        if (!loginType) {
            e.preventDefault();
            alert('Silakan pilih jenis login terlebih dahulu!');
            return false;
        }
        
        // Validasi input login
        if (!loginInput) {
            e.preventDefault();
            alert('Silakan isi ' + (loginType === 'email' ? 'email' : 'ID Pegawai') + '!');
            return false;
        }
        
        // Validasi khusus untuk employee_id (min 3 karakter, bisa angka dan huruf)
        if (loginType === 'employee_id') {
            if (loginInput.length < 3) {
                e.preventDefault();
                alert('ID Pegawai minimal 3 karakter!');
                return false;
            }
        }
        
        // Validasi khusus untuk email
        if (loginType === 'email') {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(loginInput)) {
                e.preventDefault();
                alert('Format email tidak valid!');
                return false;
            }
        }
        
        // Validasi password max 8 karakter
        if (passwordInput.length > 8) {
            e.preventDefault();
            alert('Password maksimal 8 karakter!');
            return false;
        }
        
        // Validasi password tidak boleh kosong
        if (!passwordInput.trim()) {
            e.preventDefault();
            alert('Password harus diisi!');
            return false;
        }
    });
    
    // Inisialisasi awal
    document.addEventListener('DOMContentLoaded', function() {
        // Set login type berdasarkan old value
        const oldLoginType = "{{ old('login_type', 'employee_id') }}";
        if (oldLoginType === 'email') {
            selectLoginType('email');
        } else {
            selectLoginType('employee_id');
        }
        
        // Set old login value
        const oldLogin = "{{ old('login') }}";
        if (oldLogin) {
            document.getElementById('login').value = oldLogin;
        }
        
        // Tambahkan event listener untuk perubahan manual pada input
        document.getElementById('login').addEventListener('blur', function() {
            const loginType = document.getElementById('login_type_input').value;
            if (loginType === 'employee_id') {
                this.value = this.value.toUpperCase().replace(/\s/g, '');
            }
        });
    });
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
        transition-property: background-color, border-color, color, transform, box-shadow;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Password toggle button styling */
    button[onclick="togglePasswordVisibility()"] {
        background: none;
        border: none;
        cursor: pointer;
        outline: none;
    }
    
    /* Custom focus for checkbox */
    input[type="checkbox"]:focus {
        ring-width: 2px;
        ring-color: #3b82f6;
        ring-offset-width: 0;
    }
    
    /* Font Awesome icons */
    .fa, .fas {
        width: 1em;
        text-align: center;
    }
    
    /* Style untuk button selector */
    #employee_btn, #admin_btn {
        cursor: pointer;
        border-width: 2px;
    }
    
    #employee_btn:hover:not(.border-blue-600),
    #admin_btn:hover:not(.border-blue-600) {
        border-color: #60a5fa;
        background-color: #eff6ff;
    }
</style>
@endsection