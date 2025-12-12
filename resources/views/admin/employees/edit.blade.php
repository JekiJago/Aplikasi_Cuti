@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-2xl mx-auto">
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
                
                <a href="{{ route('admin.employees.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-gray-300 
                           text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 
                           transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Form Edit Pegawai</h2>
                        <p class="text-sm text-gray-600">ID: <span class="font-mono text-gray-800">{{ $employee->employee_id }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

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

                <!-- Divider -->
                <div class="border-t border-gray-200 pt-6"></div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 pt-2">
                    <a href="{{ route('admin.employees.index') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-lg border border-gray-300 
                              text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 
                              transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batalkan
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 rounded-lg 
                                   bg-gradient-to-r from-blue-600 to-teal-600 text-white font-semibold 
                                   hover:from-blue-700 hover:to-teal-700 hover:shadow-lg 
                                   transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
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