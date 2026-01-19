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
                                    <span class="text-red-500 mr-2">*</span> Login menggunakan NIP dan NRP sebagai password
                                </li>
                                <li class="flex items-start">
                                    <span class="text-red-500 mr-2">*</span> NIP adalah Nomor Induk Pegawai
                                </li>
                                <li class="flex items-start">
                                    <span class="text-red-500 mr-2">*</span> NRP adalah Nomor Registrasi Pegawai yang berfungsi sebagai password
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
                        {{-- NIP --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                NIP (Nomor Induk Pegawai) <span class="text-red-500">*</span>
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
                                    placeholder="Masukkan NIP"
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

                        {{-- NRP --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                NRP (Nomor Registrasi Pegawai) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="text"
                                    name="nrp"
                                    id="nrp"
                                    value="{{ old('nrp') }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('nrp') border-red-500 @enderror"
                                    placeholder="Masukkan NRP"
                                    maxlength="20"
                                    required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-lock mr-1 text-[#F2B705]"></i> Berfungsi sebagai password login
                            </p>
                            @error('nrp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- KUOTA CUTI PER TAHUN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                Sisa Cuti Tahun {{ now()->year - 1 }} (Opsional)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar text-gray-400"></i>
                                </div>
                                <input type="number"
                                    name="previous_year_quota"
                                    value="{{ old('previous_year_quota', 0) }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#0B5E2E] focus:ring-[#0B5E2E]"
                                    placeholder="Contoh: 5"
                                    min="0"
                                    max="365">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1 text-[#0B5E2E]"></i> Sisa cuti tahun {{ now()->year - 1 }} yang belum digunakan
                            </p>
                        </div>

                        {{-- KUOTA CUTI TAHUN SEKARANG --}}
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                Kuota Cuti Tahun {{ now()->year }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-check text-gray-400"></i>
                                </div>
                                <input type="number"
                                    name="current_year_quota"
                                    value="{{ old('current_year_quota', 12) }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#0B5E2E] focus:ring-[#0B5E2E]"
                                    placeholder="Contoh: 12"
                                    min="0"
                                    max="365"
                                    required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1 text-[#0B5E2E]"></i> Kuota cuti tahunan tahun {{ now()->year }}
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
                                        • NIP akan menjadi username untuk login sistem
                                    </li>
                                    <li class="flex items-start">
                                        • NRP akan menjadi password untuk login sistem
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
    // Auto-format NIP and NRP to uppercase
    const employeeIdInput = document.getElementById('employee_id');
    if (employeeIdInput) {
        employeeIdInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    const nrpInput = document.getElementById('nrp');
    if (nrpInput) {
        nrpInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Simple form submission
    const form = document.getElementById('employeeForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            }
            return true;
        });
    }
});
</script>
@endpush