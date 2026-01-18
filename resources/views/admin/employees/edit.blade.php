@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-6">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
            {{-- Background Header Card --}}
            <div class="absolute top-0 left-0 w-full h-40 bg-gradient-to-r from-[#0B5E2E] to-[#083D1D] opacity-5"></div>

            {{-- HEADER --}}
            <div class="relative px-6 pt-8 pb-6 border-b border-[#DCE5DF] bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-[#0B5E2E] to-[#083D1D] p-3 rounded-xl shadow-md mr-4">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15 12c0-1.654-1.346-3-3-3s-3 1.346-3 3 1.346 3 3 3 3-1.346 3-3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-[#083D1D]">
                                <i class="fas fa-edit mr-2 text-[#0B5E2E]"></i>Edit Data Pegawai
                            </h2>
                            <p class="text-gray-600 mt-1">
                                Perbarui informasi pegawai {{ $employee->name }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.employees.show', $employee->id) }}" 
                       class="text-[#083D1D] hover:text-[#0B5E2E] transition">
                        <i class="fas fa-times text-2xl"></i>
                    </a>
                </div>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-8 bg-white">
                {{-- FORM --}}
                <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" id="editEmployeeForm">
                    @csrf
                    @method('PUT')

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
                                value="{{ old('name', $employee->name) }}"
                                class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap"
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
                                    value="{{ old('employee_id', $employee->pegawai->nip ?? '') }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('employee_id') border-red-500 @enderror"
                                    placeholder="Masukkan NIP"
                                    required>
                            </div>
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
                                    value="{{ old('nrp', $employee->pegawai->nrp ?? '') }}"
                                    class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('nrp') border-red-500 @enderror"
                                    placeholder="Masukkan NRP"
                                    required>
                            </div>
                            @error('nrp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#083D1D] mb-3">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio" 
                                    name="gender" 
                                    value="male" 
                                    id="gender_male"
                                    {{ old('gender', $employee->pegawai->jenis_kelamin ?? '') === 'male' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#F2B705] focus:ring-[#F2B705]"
                                    required>
                                <label for="gender_male" class="ml-3 text-[#083D1D]">Laki-laki</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" 
                                    name="gender" 
                                    value="female" 
                                    id="gender_female"
                                    {{ old('gender', $employee->pegawai->jenis_kelamin ?? '') === 'female' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#F2B705] focus:ring-[#F2B705]"
                                    required>
                                <label for="gender_female" class="ml-3 text-[#083D1D]">Perempuan</label>
                            </div>
                        </div>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- KUOTA CUTI --}}
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-[#083D1D] mb-2">
                            Kuota Cuti Tahunan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            @php
                                $quotaValue = 12; // default
                                if ($employee->pegawai && $employee->pegawai->cuti) {
                                    $quotaValue = $employee->pegawai->cuti->kuota_tahunan;
                                }
                            @endphp
                            <input type="number"
                                name="annual_leave_quota"
                                value="{{ old('annual_leave_quota', $quotaValue) }}"
                                min="0"
                                max="365"
                                class="pl-10 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] shadow-sm focus:border-[#F2B705] focus:ring-[#F2B705] @error('annual_leave_quota') border-red-500 @enderror"
                                placeholder="Masukkan kuota cuti tahunan"
                                required>
                        </div>
                        @error('annual_leave_quota')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.employees.show', $employee->id) }}"
                           class="px-6 py-2.5 rounded-lg border border-[#DCE5DF] text-[#083D1D] font-medium hover:bg-gray-50 transition">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit"
                                class="px-8 py-2.5 rounded-lg bg-gradient-to-r from-[#F2B705] to-[#E6A800] text-[#083D1D] font-semibold hover:from-[#E6A800] hover:to-[#D99A00] transition shadow-md">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('editEmployeeForm').addEventListener('submit', function(e) {
        const genderSelect = this.querySelector('input[name="gender"]:checked');
        if (!genderSelect) {
            e.preventDefault();
            alert('Silakan pilih jenis kelamin!');
            return false;
        }
        return true;
    });
</script>
@endpush
