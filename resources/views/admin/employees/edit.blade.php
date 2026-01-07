@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route('admin.employees.index') }}" 
                           class="inline-flex items-center text-gray-500 hover:text-gray-700 mr-3 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="ml-1 text-sm">Daftar Pegawai</span>
                        </a>
                        <span class="text-gray-300">/</span>
                        <span class="ml-3 text-gray-600">Edit Data</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Data Pegawai</h1>
                    <p class="text-gray-600 mt-2">Perbarui informasi pegawai <span class="font-semibold text-blue-600">{{ $employee->name }}</span></p>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ route('admin.employees.show', $employee->id) }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-gray-300 
                               text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 
                               transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268 2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>
    <a href="{{ route('admin.employees.index') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-gray-300 
                               text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 
                               transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
        Kembali
    </a>
                </div>
            </div>
        </div>

        <!-- PERBAIKAN: Pisahkan form untuk dua fungsi berbeda -->

        <!-- Form Card untuk Edit Data Pegawai -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Form Edit Data Pegawai</h2>
                        <p class="text-sm text-gray-600">ID: <span class="font-mono text-gray-800">{{ $employee->employee_id }}</span></p>
                    </div>
                </div>
</div>

            <!-- Form Content -->
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="p-6 space-y-8" id="editEmployeeForm">
        @csrf
        @method('PUT')

                <!-- Input hidden untuk membedakan action -->
                <input type="hidden" name="action" value="update_employee">

                <!-- Informasi Dasar -->
        <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <svg class="w-5 h-5 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Field -->
                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-gray-700 font-medium">Nama Lengkap <span class="text-red-500">*</span></span>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
            <input type="text"
                name="name"
                value="{{ old('name', $employee->name) }}"
                required
                                        class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 bg-gray-50 
                                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white 
                                               transition-colors placeholder-gray-500 text-gray-800"
                                        placeholder="Masukkan nama lengkap pegawai"
            >
                                </div>
                            </label>
            @error('name')
                                <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
            @enderror
        </div>

                        <!-- NIP Field -->
                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-gray-700 font-medium">NIP / ID Pegawai <span class="text-red-500">*</span></span>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                    </div>
            <input type="text"
                                        name="employee_id"
                                        value="{{ old('employee_id', $employee->employee_id) }}"
                                        required
                                        class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 bg-gray-50 
                                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white 
                                               transition-colors placeholder-gray-500 text-gray-800"
                                        placeholder="Contoh: 198501012010"
                                    >
                                </div>
                            </label>
                            @error('employee_id')
                                <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Gender Field -->
                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-gray-700 font-medium">Jenis Kelamin <span class="text-red-500">*</span></span>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a5.5 5.5 0 11-11 0 5.5 5.5 0 0111 0z"/>
                                        </svg>
                                    </div>
                                    <select name="gender" required
                                            class="block w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 bg-gray-50 
                                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white 
                                                   transition-colors text-gray-800 appearance-none">
                                        <option value="" class="text-gray-500">Pilih jenis kelamin</option>
                                        <option value="male" {{ old('gender', $employee->gender) === 'male' ? 'selected' : '' }} class="text-gray-800">Pria</option>
                                        <option value="female" {{ old('gender', $employee->gender) === 'female' ? 'selected' : '' }} class="text-gray-800">Wanita</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </label>
                            @error('gender')
                                <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Hire Date Field -->
                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-gray-700 font-medium">Tanggal Mulai Kerja <span class="text-red-500">*</span></span>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <input type="date"
                                        name="hire_date"
                                        value="{{ old('hire_date', $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}"
                                        required
                                        class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 bg-gray-50 
                                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white 
                                               transition-colors placeholder-gray-500 text-gray-800"
                                    >
                                </div>
                            </label>
                            @error('hire_date')
                                <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Annual Leave Quota Field -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="block">
                                <span class="text-gray-700 font-medium">Kuota Cuti Tahunan Default <span class="text-red-500">*</span></span>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                        </svg>
                                    </div>
                                    <input type="number"
                                        name="annual_leave_quota"
                                        value="{{ old('annual_leave_quota', $employee->annual_leave_quota ?? 12) }}"
                required
                                        min="0"
                                        max="365"
                                        class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 bg-gray-50 
                                               focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white 
                                               transition-colors text-gray-800"
                                        placeholder="Kuota cuti tahunan default"
                                    >
                                </div>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">
                                Kuota default untuk tahun-tahun mendatang. Nilai ini akan digunakan saat generate kuota baru.
                            </p>
                            @error('annual_leave_quota')
                                <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Ganti Password -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <svg class="w-5 h-5 inline-block mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Ganti Password
                    </h3>
                    
                    <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-4 border border-red-200 mb-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-gray-800">
                                <p class="font-medium">Perhatian!</p>
                                <p class="mt-1">Biarkan kolom password kosong jika tidak ingin mengganti password pegawai.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-gray-700 font-medium">Password Baru</span>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input type="password"
                                        name="password"
                                        class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 bg-gray-50 
                                               focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:bg-white 
                                               transition-colors placeholder-gray-500 text-gray-800"
                                        placeholder="Kosongkan jika tidak ingin ganti"
                                        autocomplete="new-password"
                                    >
                                </div>
                            </label>
                            @error('password')
                                <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
            @enderror
        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-gray-700 font-medium">Konfirmasi Password Baru</span>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <input type="password"
                                        name="password_confirmation"
                                        class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 bg-gray-50 
                                               focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:bg-white 
                                               transition-colors placeholder-gray-500 text-gray-800"
                                        placeholder="Ulangi password baru"
                                        autocomplete="new-password"
                                    >
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('admin.employees.show', $employee->id) }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-lg border border-gray-300 
                                  text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 
                                  transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Detail
                        </a>
                        <a href="{{ route('admin.employees.index') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-lg border border-gray-300 
                                  text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 
                                  transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batalkan
                        </a>
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 rounded-lg 
                                   bg-gradient-to-r from-blue-600 to-teal-600 text-white font-semibold 
                                   hover:from-blue-700 hover:to-teal-700 hover:shadow-lg 
                                   transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan Data Pegawai
            </button>
        </div>
            </form>
        </div>

        <!-- Form Terpisah untuk Penyesuaian Kuota Khusus -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Penyesuaian Kuota Khusus</h2>
                        <p class="text-sm text-gray-600">Atur kuota tahun tertentu secara khusus</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="p-6 space-y-6" id="quotaAdjustmentForm">
                @csrf
                @method('PUT')
                
                <!-- Input hidden untuk action -->
                <input type="hidden" name="action" value="adjust_quota">

                <div class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-purple-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-gray-800">
                            <p class="font-medium">Informasi Penyesuaian Kuota:</p>
                            <p class="mt-1">Gunakan form ini untuk menyesuaikan kuota tahun tertentu secara khusus. Penyesuaian akan tercatat dalam riwayat.</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="quota_adjustment_year" class="block text-sm font-medium text-gray-700 mb-1">
                            Tahun <span class="text-red-500">*</span>
                        </label>
                        <select id="quota_adjustment_year" name="quota_adjustment_year" required
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg bg-gray-50
                                       focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:bg-white">
                            @for($y = date('Y')-2; $y <= date('Y')+2; $y++)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
                                    Tahun {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label for="quota_adjustment_quota" class="block text-sm font-medium text-gray-700 mb-1">
                            Kuota (hari) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="quota_adjustment_quota" name="quota_adjustment_quota" 
                               min="0" max="365" value="{{ $employee->annual_leave_quota ?? 12 }}" required
                               class="block w-full px-3 py-3 border border-gray-300 rounded-lg bg-gray-50
                                      focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:bg-white">
                    </div>
                    
                    <div>
                        <label for="quota_adjustment_reason" class="block text-sm font-medium text-gray-700 mb-1">
                            Alasan Penyesuaian <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="quota_adjustment_reason" name="quota_adjustment_reason" required
                               class="block w-full px-3 py-3 border border-gray-300 rounded-lg bg-gray-50
                                      focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:bg-white"
                               placeholder="Contoh: Penyesuaian kuota tahunan">
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-8 py-3 rounded-lg 
                                   bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold 
                                   hover:from-purple-700 hover:to-indigo-700 hover:shadow-lg 
                                   transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Simpan Penyesuaian Kuota
                    </button>
                </div>
    </form>
        </div>

        <!-- Status Info -->
        <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-teal-50 border border-blue-100 rounded-xl">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-gray-800">
                    <p class="font-medium">Data terakhir diubah:</p>
                    <p class="mt-1 text-gray-600">
                        @if($employee->updated_at)
                            {{ \Carbon\Carbon::parse($employee->updated_at)->timezone('Asia/Jakarta')->format('d F Y, H:i') }}
                        @else
                            Belum pernah diperbarui
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Validasi form edit employee
    document.getElementById('editEmployeeForm').addEventListener('submit', function(e) {
        const password = this.querySelector('input[name="password"]').value;
        const passwordConfirmation = this.querySelector('input[name="password_confirmation"]').value;
        
        if (password && password !== passwordConfirmation) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }
        
        return true;
    });
    
    // Validasi form quota adjustment
    document.getElementById('quotaAdjustmentForm').addEventListener('submit', function(e) {
        const quota = this.querySelector('input[name="quota_adjustment_quota"]').value;
        const reason = this.querySelector('input[name="quota_adjustment_reason"]').value;
        
        if (parseInt(quota) > 365) {
            e.preventDefault();
            alert('Kuota maksimal 365 hari!');
            return false;
        }
        
        if (reason.trim().length < 5) {
            e.preventDefault();
            alert('Alasan penyesuaian minimal 5 karakter!');
            return false;
        }
        
        return true;
    });
</script>
@endpush