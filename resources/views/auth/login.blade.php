@extends('layouts.guest')

@section('title', 'Login - Sistem Manajemen Cuti Tahunan')
@section('heading', 'Login')
@section('subheading', 'Masuk ke Aplikasi Cuti Tahunan')

@section('full-content')
<div class="min-h-screen flex flex-col lg:flex-row bg-white">
    <!-- LEFT PANEL - ILLUSTRATION -->
    <div class="split-left lg:w-1/2 bg-gradient-to-br from-[#0B5E2E] via-[#083D1D] to-[#0B5E2E] relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <!-- Content Container -->
        <div class="relative z-10 h-full flex flex-col justify-center items-center px-8 lg:px-16 py-12">
            <!-- Logo Kejaksaan - Desktop Version -->
            <!-- DEBUG: Path logo = {{ asset('assets/logo-kejaksaan.png') }} -->
            <div class="mb-10">
                <!-- Versi 1: Simple -->
                <img src="{{ asset('assets/logo-kejaksaan.png') }}" 
                     alt="Logo Kejaksaan" 
                     class="w-32 h-32 object-contain">
                
                <!-- Atau Versi 2: Dengan background -->
                <!-- 
                <div class="w-32 h-32 bg-white rounded-3xl p-4 shadow-2xl flex items-center justify-center">
                    <img src="{{ asset('assets/logo-kejaksaan.png') }}" 
                         alt="Logo Kejaksaan" 
                         class="w-full h-full object-contain">
                </div>
                -->
            </div>
            
            <!-- Heading -->
            <h1 class="text-4xl lg:text-5xl font-bold text-white text-center mb-6">
                Sistem Manajemen Cuti Tahunan
            </h1>
            
            <!-- Description -->
            <p class="text-lg lg:text-xl text-white/90 text-center mb-10 max-w-xl">
                Sistem terintegrasi untuk pengelolaan cuti tahunan pegawai Kejaksaan
            </p>
            
            <!-- Features List -->
            <div class="space-y-4 max-w-md">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-check text-white text-sm"></i>
                    </div>
                    <span class="text-white text-lg">Pengajuan cuti tahunan online</span>
                </div>
                
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-4">
                        <i class="fas fa-tasks text-white text-sm"></i>
                    </div>
                    <span class="text-white text-lg">Monitoring kuota cuti</span>
                </div>
                
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-4">
                        <i class="fas fa-file-contract text-white text-sm"></i>
                    </div>
                    <span class="text-white text-lg">Persetujuan berjenjang</span>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- RIGHT PANEL - LOGIN FORM -->
    <div class="flex-1 flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-md">
            <!-- Logo Kejaksaan - Mobile Version -->
            <div class="lg:hidden flex justify-center mb-8">
                <!-- Simple version -->
                <img src="{{ asset('assets/logo-kejaksaan.png') }}" 
                     alt="Logo Kejaksaan" 
                     class="w-24 h-24 object-contain">
                
                <!-- Atau dengan background -->
                <!--
                <div class="w-24 h-24 bg-white rounded-2xl p-3 shadow-lg flex items-center justify-center">
                    <img src="{{ asset('assets/logo-kejaksaan.png') }}" 
                         alt="Logo Kejaksaan" 
                         class="w-full h-full object-contain">
                </div>
                -->
            </div>
            
            <!-- Mobile Header -->
            <div class="lg:hidden text-center mb-8">
                <h1 class="text-3xl font-bold text-[#083D1D]">Sistem Manajemen Cuti Tahunan</h1>
                <p class="mt-2 text-gray-600">Masuk untuk mengelola pengajuan cuti tahunan</p>
            </div>
            
            <!-- Card -->
            <div class="bg-white py-8 px-6 shadow-lg rounded-2xl border border-[#DCE5DF]">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 animate-fade-in">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Terdapat kesalahan
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if (session('status'))
                    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 animate-fade-in">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-800">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-6">
                    @csrf

                    <!-- Hidden login type -->
                    <input type="hidden" name="login_type" id="login_type" value="auto">

                    <!-- Login Field -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-[#083D1D] mb-2">
                            <i class="fas fa-user mr-2"></i>NIP
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text"
                                   id="login"
                                   name="login"
                                   value="{{ old('login') }}"
                                   required
                                   autofocus
                                   placeholder="Masukkan NIP Anda"
                                   class="pl-10 w-full px-4 py-3 rounded-lg border border-[#DCE5DF] bg-white
                                          focus:ring-1 focus:ring-[#0B5E2E] focus:border-[#0B5E2E]
                                          transition duration-200">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#083D1D] mb-2">
                            <i class="fas fa-lock mr-2"></i>NRP (maksimal 10 karakter)
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   maxlength="10"
                                   placeholder="Masukkan NRP Anda"
                                   class="pl-10 pr-12 w-full px-4 py-3 rounded-lg border border-[#DCE5DF] bg-white
                                          focus:ring-1 focus:ring-[#0B5E2E] focus:border-[#0B5E2E]
                                          transition duration-200">

                            <!-- Toggle Password Button -->
                            <button type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#0B5E2E] transition-colors duration-200">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        
                        <!-- Character Counter Only -->
                        <div class="mt-2">
                            <div class="flex justify-between text-xs">
                                <span id="charCount" class="text-gray-500">0/10 karakter</span>
                            </div>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox"
                                   name="remember"
                                   id="remember"
                                   class="h-4 w-4 rounded border-[#DCE5DF] text-[#0B5E2E] focus:ring-[#0B5E2E]">
                            <label for="remember" class="ml-2 text-sm text-[#083D1D]">Ingat saya</label>
                        </div>
                        
                        @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="text-[#0B5E2E] hover:text-[#083D1D] font-medium transition-colors duration-200">
                                Lupa kata sandi?
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            id="submitBtn"
                            class="w-full py-3 rounded-lg bg-[#0B5E2E] hover:bg-[#083D1D]
                                   text-white font-semibold 
                                   transition-all duration-200 
                                   shadow hover:shadow-md flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i> 
                        <span id="submitText">Masuk ke Sistem</span>
                        <i id="loadingIcon" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                    </button>
                </form>

                <!-- Divider -->
                <div class="mt-6 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-[#DCE5DF]"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Sistem Resmi Kejaksaan</span>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Untuk bantuan teknis, hubungi 
                        <span class="font-medium text-[#0B5E2E]">administrator</span>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-xs text-[#083D1D]">
                    &copy; {{ date('Y') }} Sistem Manajemen Cuti Tahunan Kejaksaan
                </p>
            </div>
            
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const loginTypeInput = document.getElementById('login_type');
    const toggleBtn = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingIcon = document.getElementById('loadingIcon');

    // Remove spaces from login input
    loginInput.addEventListener('input', function() {
        this.value = this.value.replace(/\s/g, '');
    });

    // Toggle password visibility
    toggleBtn.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeIcon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
        passwordInput.focus();
    });

    // Character counter only
    passwordInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length}/10 karakter`;
    });

    // Form submission
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const loginValue = loginInput.value.trim();
        const passwordValue = passwordInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Basic validation
        if (!loginValue) {
            e.preventDefault();
            showAlert('Username atau email wajib diisi', 'error');
            loginInput.focus();
            return;
        }

        if (!passwordValue) {
            e.preventDefault();
            showAlert('Kata sandi wajib diisi', 'error');
            passwordInput.focus();
            return;
        }

        if (passwordValue.length > 10) {
            e.preventDefault();
            showAlert('Kata sandi maksimal 10 karakter', 'error');
            passwordInput.focus();
            return;
        }

        // Determine login type
        loginTypeInput.value = emailRegex.test(loginValue) ? 'email' : 'employee_id';
        
        // Show loading state
        submitText.textContent = 'Memproses...';
        loadingIcon.classList.remove('hidden');
        submitBtn.disabled = true;
        
        // Auto re-enable after 5 seconds (in case of error)
        setTimeout(function() {
            submitText.textContent = 'Masuk ke Sistem';
            loadingIcon.classList.add('hidden');
            submitBtn.disabled = false;
        }, 5000);
    });

    // Helper function to show alerts
    function showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlert = document.querySelector('.custom-alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `custom-alert fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg animate-fade-in ${type === 'error' ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-[#F9FAF7] border border-[#DCE5DF] text-[#083D1D]'}`;
        alert.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add to document
        document.body.appendChild(alert);
        
        // Remove after 3 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => alert.remove(), 300);
            }
        }, 3000);
    }
});
</script>

<style>
    /* Custom alert animation */
    .custom-alert {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* Border untuk debugging */
    .debug-border {
        border: 2px solid red;
    }
</style>
@endpush
@endsection