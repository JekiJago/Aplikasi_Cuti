@extends('layouts.app')

@section('title', 'Tambah Pegawai Baru')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-6">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
            {{-- Background Header Card --}}
            <div class="absolute top-0 left-0 w-full h-40 bg-gradient-to-r from-[#0B5E2E] to-[#083D1D] opacity-5"></div>

            {{-- HEADER --}}
            <div class="relative px-6 pt-8 pb-6 border-b border-[#DCE5DF] bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-[#0B5E2E] to-[#083D1D] p-3 rounded-xl shadow-md mr-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M15 12c0-1.654-1.346-3-3-3s-3 1.346-3 3 1.346 3 3 3 3-1.346 3-3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-[#083D1D]">
                            <i class="fas fa-user-plus mr-2 text-[#0B5E2E]"></i>Tambah Pegawai Baru
                        </h2>
                        <p class="text-gray-600 mt-1">
                            Lengkapi data pegawai dengan benar dan lengkap
                        </p>
                    </div>
                </div>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-8 bg-white">
                {{-- INFORMASI --}}
                <div class="mb-8 p-4 bg-yellow-50 border-l-4 border-[#F2B705] rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-[#F2B705] mt-0.5 mr-3 text-lg"></i>
                        <div>
                            <h4 class="font-semibold text-[#083D1D]">Informasi Penting</h4>
                            <ul class="mt-2 text-sm text-[#083D1D] space-y-1">
                                <li class="flex items-start">
                                    <span class="text-red-500 mr-2">*</span> Semua field bertanda wajib diisi
                                </li>
                                <li class="flex items-start">
                                    <span class="text-red-500 mr-2">*</span> Password ditentukan oleh Admin
                                </li>
                                <li class="flex items-start">
                                    <span class="text-red-500 mr-2">*</span> Login menggunakan ID Pegawai
                                </li>
                                <li class="flex items-start">
                                    <span class="text-red-500 mr-2">*</span> Tanggal masuk kerja menentukan periode kerja pegawai
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- FORM --}}
                <form action="{{ route('admin.employees.store') }}" method="POST" id="employeeForm">
                    @csrf

                    {{-- SECTION: DATA PRIBADI --}}
                    <div class="mb-6">
                        <h5 class="text-lg font-semibold text-[#083D1D] border-l-4 border-[#F2B705] pl-3 py-2 bg-yellow-50 rounded-r">
                            <i class="fas fa-user mr-2 text-[#F2B705]"></i> Data Pribadi
                        </h5>
                    </div>

                    {{-- NAMA --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#083D1D] mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('name') border-red-500 @enderror"
                                placeholder="Contoh: Ahmad Rizki Pratama"
                                required>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- GRID INPUTS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- ID PEGAWAI --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                ID Pegawai <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-badge text-gray-400"></i>
                                </div>
                                <input type="text"
                                    name="employee_id"
                                    id="employee_id"
                                    value="{{ old('employee_id') }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('employee_id') border-red-500 @enderror"
                                    placeholder="Masukkan ID Pegawai"
                                    maxlength="20"
                                    required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1 text-[#F2B705]"></i> Digunakan sebagai username login
                            </p>
                            @error('employee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- TANGGAL MASUK KERJA --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                Tanggal Masuk Kerja <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="date"
                                    name="hire_date"
                                    id="hire_date"
                                    value="{{ old('hire_date', date('Y-m-d')) }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('hire_date') border-red-500 @enderror"
                                    required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1 text-[#F2B705]"></i> Format: <span id="dateFormatDisplay">Tanggal Bulan Tahun</span>
                            </p>
                            @error('hire_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- KUOTA CUTI TAHUNAN DEFAULT --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                Kuota Cuti Tahunan Default <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-check text-gray-400"></i>
                                </div>
                                <input type="number"
                                    name="annual_leave_quota"
                                    value="{{ old('annual_leave_quota', 12) }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('annual_leave_quota') border-red-500 @enderror"
                                    placeholder="Contoh: 12"
                                    min="0"
                                    max="365"
                                    required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1 text-[#F2B705]"></i> Jumlah hari cuti tahunan default
                            </p>
                            @error('annual_leave_quota')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- PASSWORD --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password"
                                    name="password"
                                    id="password"
                                    class="pl-10 pr-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('password') border-red-500 @enderror"
                                    placeholder="Minimal 6 karakter"
                                    minlength="6"
                                    required>
                                <button type="button"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password"
                                    data-target="password">
                                    <i class="fas fa-eye text-gray-400 hover:text-[#F2B705]"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-shield-alt mr-1 text-[#F2B705]"></i> Password akan dienkripsi secara otomatis
                            </p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- KONFIRMASI PASSWORD --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="pl-10 pr-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705]"
                                    placeholder="Ulangi password"
                                    minlength="6"
                                    required>
                                <button type="button"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password"
                                    data-target="password_confirmation">
                                    <i class="fas fa-eye text-gray-400 hover:text-[#F2B705]"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-check-circle mr-1 text-[#F2B705]"></i> Pastikan password sama dengan di atas
                            </p>
                        </div>
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#083D1D] mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <div class="flex space-x-6">
                            <div class="flex items-center">
                                <input type="radio"
                                    name="gender"
                                    id="gender_male"
                                    value="male"
                                    {{ old('gender') == 'male' ? 'checked' : '' }}
                                    class="h-4 w-4 text-[#F2B705] focus:ring-[#F2B705] border-[#DCE5DF]"
                                    required>
                                <label for="gender_male" class="ml-2 text-[#083D1D]">
                                    <i class="fas fa-mars mr-1 text-[#0B5E2E]"></i> Laki-laki
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio"
                                    name="gender"
                                    id="gender_female"
                                    value="female"
                                    {{ old('gender') == 'female' ? 'checked' : '' }}
                                    class="h-4 w-4 text-[#F2B705] focus:ring-[#F2B705] border-[#DCE5DF]"
                                    required>
                                <label for="gender_female" class="ml-2 text-[#083D1D]">
                                    <i class="fas fa-venus mr-1 text-pink-600"></i> Perempuan
                                </label>
                            </div>
                        </div>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PREVIEW TANGGAL --}}
                    <div class="mb-8 p-4 bg-yellow-50 border border-[#F2B705] rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-check text-[#F2B705] text-xl mr-3"></i>
                            <div>
                                <h6 class="font-semibold text-[#083D1D]">Preview Tanggal Masuk Kerja</h6>
                                <p class="text-gray-600 text-sm mt-1">
                                    Pegawai akan tercatat mulai bekerja pada: 
                                    <span id="hireDatePreview" class="font-bold text-[#0B5E2E]">{{ date('d F Y') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: INFO LOGIN --}}
                    <div class="mb-6">
                        <h5 class="text-lg font-semibold text-[#083D1D] border-l-4 border-[#F2B705] pl-3 py-2 bg-yellow-50 rounded-r">
                            <i class="fas fa-key mr-2 text-[#F2B705]"></i> Informasi Login
                        </h5>
                    </div>

                    <div class="mb-8 p-4 bg-yellow-50 border-l-4 border-[#F2B705] rounded-r-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-[#F2B705] mt-0.5 mr-3 text-lg"></i>
                            <div>
                                <h6 class="font-semibold text-[#083D1D]">Catatan Penting:</h6>
                                <ul class="mt-2 text-sm text-[#083D1D] space-y-1">
                                    <li class="flex items-start">
                                        • ID Pegawai akan menjadi username untuk login sistem
                                    </li>
                                    <li class="flex items-start">
                                        • Password dapat diubah oleh pegawai setelah login pertama
                                    </li>
                                    <li class="flex items-start">
                                        • Simpan informasi login dengan aman
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-[#DCE5DF]">
                        {{-- ACTION BUTTONS --}}
                        <div class="flex flex-col sm:flex-row justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('admin.employees.index') }}"
                                class="px-6 py-3 border border-[#DCE5DF] rounded-lg text-sm font-medium text-[#083D1D] bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#F2B705] focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-[#F2B705] to-[#E6A800] text-[#083D1D] font-medium rounded-lg hover:from-[#E6A800] hover:to-[#D99A00] focus:outline-none focus:ring-2 focus:ring-[#F2B705] focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center"
                                id="submitBtn">
                                <i class="fas fa-save mr-2"></i> Simpan Pegawai
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom styles untuk memperbaiki tampilan */
    .toggle-password {
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .toggle-password:hover i {
        color: #F2B705 !important;
    }
    
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.2s;
    }
    
    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }
    
    /* Custom focus ring dengan warna Kejaksaan */
    input:focus, select:focus, textarea:focus {
        --tw-ring-color: rgba(242, 183, 5, 0.5) !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.target);
            const icon = btn.querySelector('i');
            
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
    
    // Format tanggal untuk preview
    const hireDateInput = document.getElementById('hire_date');
    const hireDatePreview = document.getElementById('hireDatePreview');
    const dateFormatDisplay = document.getElementById('dateFormatDisplay');
    
    // Format tanggal Indonesia
    function formatDateIndonesian(dateString) {
        const date = new Date(dateString);
        const options = { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        };
        return date.toLocaleDateString('id-ID', options);
    }
    
    // Update preview saat tanggal berubah
    if (hireDateInput && hireDatePreview) {
        // Set initial preview
        hireDatePreview.textContent = formatDateIndonesian(hireDateInput.value);
        dateFormatDisplay.textContent = formatDateIndonesian(hireDateInput.value);
        
        // Update preview saat tanggal diubah
        hireDateInput.addEventListener('change', function() {
            if (this.value) {
                const formattedDate = formatDateIndonesian(this.value);
                hireDatePreview.textContent = formattedDate;
                dateFormatDisplay.textContent = formattedDate;
            }
        });
    }
    
    // Validasi form
    const form = document.getElementById('employeeForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validasi password match
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                // SweetAlert2 atau alert biasa
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok',
                        text: 'Password dan Konfirmasi Password harus sama!',
                        confirmButtonColor: '#F2B705',
                        confirmButtonText: 'Baik'
                    });
                } else {
                    alert('Password dan Konfirmasi Password harus sama!');
                }
                document.getElementById('password').focus();
                return false;
            }
            
            // Validasi minimal 6 karakter
            if (password.length < 6) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Terlalu Pendek',
                        text: 'Password minimal 6 karakter!',
                        confirmButtonColor: '#F2B705',
                        confirmButtonText: 'Baik'
                    });
                } else {
                    alert('Password minimal 6 karakter!');
                }
                return false;
            }
            
            // Validasi annual_leave_quota
            const annualQuota = document.querySelector('input[name="annual_leave_quota"]');
            if (annualQuota && (annualQuota.value < 0 || annualQuota.value > 365)) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kuota Tidak Valid',
                        text: 'Kuota cuti tahunan harus antara 0-365 hari!',
                        confirmButtonColor: '#F2B705',
                        confirmButtonText: 'Baik'
                    });
                } else {
                    alert('Kuota cuti tahunan harus antara 0-365 hari!');
                }
                annualQuota.focus();
                return false;
            }
            
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            
            // Validasi tanggal tidak di masa depan (opsional)
            const hireDate = new Date(hireDateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (hireDate > today) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanggal Masa Depan',
                        html: 'Tanggal masuk kerja lebih dari hari ini.<br>Apakah Anda yakin?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Periksa Kembali',
                        confirmButtonColor: '#F2B705',
                        cancelButtonColor: '#6b7280'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Pegawai';
                            form.submit();
                        } else {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Pegawai';
                            hireDateInput.focus();
                        }
                    });
                } else {
                    if (!confirm('Tanggal masuk kerja lebih dari hari ini. Lanjutkan?')) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Pegawai';
                        hireDateInput.focus();
                        return false;
                    }
                }
                return false;
            }
            
            return true;
        });
    }
    
    // Auto-format employee ID to uppercase
    const employeeIdInput = document.getElementById('employee_id');
    if (employeeIdInput) {
        employeeIdInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
});
</script>
@endpush