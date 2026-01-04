@extends('layouts.app')

@section('title', 'Pengaturan Hari Libur')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center text-gray-500 hover:text-gray-700 mr-3 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="ml-1 text-sm">Dashboard</span>
                        </a>
                        <span class="text-gray-300">/</span>
                        <span class="ml-3 text-gray-600">Pengaturan</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Hari Libur Nasional</h1>
                    <p class="text-gray-600 mt-2">Kelola daftar hari libur yang tidak dihitung sebagai hari cuti</p>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border border-red-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Terjadi kesalahan:</p>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Kelola Hari Libur Nasional</h2>
                        <p class="text-sm text-gray-600">Total: <span class="font-mono text-gray-800">{{ $holidays->count() }} hari libur</span></p>
                    </div>
                </div>
            </div>

            <!-- Card Content -->
            <div class="p-6 space-y-8">
                <!-- Add New Holiday Form -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <svg class="w-5 h-5 inline-block mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Hari Libur Baru
                    </h3>
                    
                    <div class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-gray-800">
                                <p class="font-medium">Informasi Penting:</p>
                                <p class="mt-1">Hari libur yang ditambahkan akan otomatis dikecualikan dari perhitungan hari cuti pegawai.</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.settings.holidays.store') }}" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date Field -->
                            <div class="space-y-2">
                                <label class="block">
                                    <span class="text-gray-700 font-medium">Tanggal Libur <span class="text-red-500">*</span></span>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <input type="date" name="date" required
                                               class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 bg-gray-50 
                                                      focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:bg-white 
                                                      transition-colors placeholder-gray-500 text-gray-800"
                                               value="{{ old('date') }}">
                                    </div>
                                </label>
                                @error('date')
                                    <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <!-- Description Field -->
                            <div class="space-y-2">
                                <label class="block">
                                    <span class="text-gray-700 font-medium">Keterangan Libur <span class="text-red-500">*</span></span>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                        </div>
                                        <input type="text" name="description" required
                                               class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 bg-gray-50 
                                                      focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:bg-white 
                                                      transition-colors placeholder-gray-500 text-gray-800"
                                               placeholder="Contoh: Hari Raya Idul Fitri"
                                               value="{{ old('description') }}">
                                    </div>
                                </label>
                                @error('description')
                                    <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Submit Button - Warna Diubah menjadi Purple -->
                        <div class="pt-4">
                            <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-lg 
                                bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold 
                                hover:from-blue-700 hover:to-cyan-700 hover:shadow-lg 
                                transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Hari Libur
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Holidays List -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        <svg class="w-5 h-5 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Daftar Hari Libur
                        <span class="text-sm font-normal text-gray-600 ml-2">({{ $holidays->count() }} hari)</span>
                    </h3>
                    
                    @if($holidays->count() > 0)
                        <div class="space-y-3">
                            @foreach($holidays as $holiday)
                                <div class="flex items-center justify-between rounded-xl border border-gray-200 p-4 hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <!-- Format tanggal Indonesia: Senin, 15 Januari 2025 -->
                                            <p class="font-medium text-gray-900">
                                                @php
                                                    // Set locale Carbon ke Indonesia
                                                    \Carbon\Carbon::setLocale('id');
                                                    $date = \Carbon\Carbon::parse($holiday->date);
                                                @endphp
                                                {{ $date->isoFormat('dddd, D MMMM YYYY') }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">{{ $holiday->description }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <!-- Edit Button dengan Modal -->
                                        <button type="button" 
                                                onclick="openEditModal({{ $holiday->id }}, '{{ $holiday->date }}', '{{ addslashes($holiday->description) }}')"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg border border-yellow-200 
                                                       bg-yellow-50 text-yellow-700 hover:bg-yellow-100 hover:border-yellow-300 
                                                       transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </button>
                                        
                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('admin.settings.holidays.destroy', $holiday->id) }}"
                                              onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg border border-red-200 
                                                           bg-red-50 text-red-700 hover:bg-red-100 hover:border-red-300 
                                                           transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Info jumlah data -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-600">
                                    Menampilkan semua hari libur ({{ $holidays->count() }} hari)
                                </p>
                                <div class="text-xs text-gray-500">
                                    Diurutkan berdasarkan tanggal
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto w-20 h-20 text-gray-300 mb-4">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada hari libur yang terdaftar</p>
                            <p class="text-gray-400 text-sm mt-1">Tambahkan hari libur menggunakan form di atas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Info Card 1 -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-100 rounded-xl border border-purple-200 p-5">
                <div class="flex items-start">
                    <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Cara Kerja Hari Libur</h4>
                        <ul class="mt-2 space-y-1 text-xs text-gray-600">
                            <li>• Hari libur tidak dihitung dalam perhitungan hari cuti</li>
                            <li>• Jika cuti jatuh pada hari libur, akan dilewati</li>
                            <li>• Libur akhir pekan (Sabtu-Minggu) sudah otomatis</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Info Card 2 -->
            <div class="bg-gradient-to-r from-blue-50 to-teal-100 rounded-xl border border-blue-200 p-5">
                <div class="flex items-start">
                    <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Tips Penggunaan</h4>
                        <ul class="mt-2 space-y-1 text-xs text-gray-600">
                            <li>• Tambahkan semua hari libur nasional resmi</li>
                            <li>• Periksa kalender pemerintah untuk update</li>
                            <li>• Hapus libur yang sudah tidak berlaku</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Holiday -->
<div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Hari Libur</h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <!-- Date Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Libur <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="date" name="date" id="editDate" required
                                   class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-300 
                                          focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                    </div>
                    
                    <!-- Description Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan Libur <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <input type="text" name="description" id="editDescription" required
                                   class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-300 
                                          focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                   placeholder="Contoh: Hari Raya Idul Fitri">
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 
                                   transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
    class="px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 
           text-white font-semibold rounded-lg
           hover:from-green-700 hover:to-emerald-700 
           hover:shadow-md transition-all duration-200">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set min date to today for new holidays
        const dateInput = document.querySelector('input[name="date"]');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
            dateInput.value = today;
        }
        
        // Form validation
        const form = document.querySelector('form[action*="store"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                const date = document.querySelector('input[name="date"]').value;
                const description = document.querySelector('input[name="description"]').value.trim();
                
                if (!date || !description) {
                    e.preventDefault();
                    alert('Harap isi semua field yang diperlukan!');
                    return false;
                }
                
                // Validate date is not in the past
                const selectedDate = new Date(date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    e.preventDefault();
                    alert('Tanggal libur tidak boleh di masa lalu!');
                    return false;
                }
                
                return true;
            });
        }
    });
    
    // Confirmation for delete
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus hari libur ini?');
    }
    
    // Edit Modal Functions
    function openEditModal(id, date, description) {
        // Set form action
        const form = document.getElementById('editForm');
        form.action = `/admin/settings/holidays/${id}`;
        
        // Fill form values
        document.getElementById('editDate').value = date;
        document.getElementById('editDescription').value = description.replace(/\\/g, '');
        
        // Show modal with animation
        const modal = document.getElementById('editModal');
        const modalContent = document.getElementById('modalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    function closeEditModal() {
        const modal = document.getElementById('editModal');
        const modalContent = document.getElementById('modalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
        }
    });
    
    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target.id === 'editModal') {
            closeEditModal();
        }
    });
    
    // Edit form validation
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const date = document.getElementById('editDate').value;
            const description = document.getElementById('editDescription').value.trim();
            
            if (!date || !description) {
                e.preventDefault();
                alert('Harap isi semua field yang diperlukan!');
                return false;
            }
            
            return true;
        });
    }
</script>
@endpush