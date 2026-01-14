@extends('layouts.app')

@section('title', 'Ajukan Cuti')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center mb-3">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center text-[#083D1D] hover:text-[#0B5E2E] mr-4 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold text-[#083D1D]">
                    Form Pengajuan Cuti
                </h1>
            </div>
            <p class="text-gray-600 ml-9">
                Isi formulir di bawah untuk mengajukan permohonan cuti.
            </p>
        </div>

        <!-- TAMBAHKAN INI: Estimasi Kuota -->
        @if(session('quota_estimation'))
        <div class="mb-6 bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] border border-[#DCE5DF] rounded-2xl p-6 shadow-sm">
            <div class="flex items-start mb-4">
                <div class="p-2 bg-white rounded-xl shadow-sm mr-4">
                    <svg class="w-6 h-6 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-[#083D1D] mb-1">Estimasi Penggunaan Kuota Cuti</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Jika cuti tahunan disetujui, kuota akan dikurangi dengan prioritas:
                    </p>
                    
                    <div class="space-y-3">
                        <div class="bg-white rounded-xl p-4 border border-[#DCE5DF]">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-700">Total Hari Cuti</span>
                                <span class="text-xl font-bold text-[#0B5E2E]">{{ session('quota_estimation.total_days') }} hari</span>
                            </div>
                        </div>
                        
                        @if(!empty(session('quota_estimation.quota_usage')))
                        <div class="bg-white rounded-xl p-4 border border-[#DCE5DF]">
                            <p class="font-medium text-gray-700 mb-3">Distribusi Penggunaan Kuota:</p>
                            <div class="space-y-2">
                                @foreach(session('quota_estimation.quota_usage') as $usage)
                                <div class="flex items-center justify-between py-2 px-3 bg-[#F9FAF7] rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full 
                                            @if($usage['year'] < now()->year) bg-amber-500 
                                            @elseif($usage['year'] == now()->year) bg-[#0B5E2E] 
                                            @else bg-[#F2B705] @endif mr-3"></div>
                                        <span class="text-sm text-gray-700">{{ $usage['label'] }}</span>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full 
                                        @if($usage['year'] < now()->year) bg-amber-100 text-amber-800 
                                        @elseif($usage['year'] == now()->year) bg-green-100 text-[#083D1D] 
                                        @else bg-[#F9FAF7] text-[#083D1D] border border-[#DCE5DF] @endif">
                                        Kuota {{ $usage['year'] }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if(session('quota_estimation.remaining_after') > 0)
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-[#083D1D]">Sisa Kuota Setelah Cuti</p>
                                    <p class="text-sm text-green-600">Kuota yang masih tersedia untuk pengajuan berikutnya</p>
                                </div>
                                <span class="text-2xl font-bold text-[#0B5E2E]">{{ session('quota_estimation.remaining_after') }} hari</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="text-xs text-gray-500 border-t border-[#DCE5DF] pt-4 mt-4">
                <p class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Prioritas penggunaan: Sisa 2024 → Kuota 2025 → Kuota 2026 (jika diperlukan)
                </p>
            </div>
        </div>
        @endif

        @if(session('quota_info'))
        <div class="mb-6 bg-[#F9FAF7] border border-[#DCE5DF] rounded-xl p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-[#0B5E2E] mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-[#083D1D]">{{ session('quota_info') }}</p>
            </div>
        </div>
        @endif

        <!-- Quota Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach ($quotaCards as $card)
                <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] p-5 hover:shadow-2xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 rounded-lg {{ $card['remaining'] > 0 ? 'bg-[#F9FAF7]' : 'bg-red-50' }}">
                            <svg class="w-5 h-5 {{ $card['remaining'] > 0 ? 'text-[#0B5E2E]' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $card['remaining'] > 0 ? 'bg-green-50 text-[#083D1D]' : 'bg-red-50 text-red-700' }}">
                            {{ $card['remaining'] > 0 ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mb-1">{{ $card['label'] }}</p>
                    <p class="text-3xl font-bold {{ $card['remaining'] > 0 ? 'text-[#083D1D]' : 'text-red-600' }} mb-2">
                        {{ $card['remaining'] }} <span class="text-lg">{{ $card['unit'] }}</span>
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $card['note'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] px-6 py-4 border-b border-[#DCE5DF]">
                <div class="flex items-center">
                    <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                        <svg class="w-6 h-6 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-[#083D1D]">Informasi Cuti</h2>
                        <p class="text-sm text-gray-600">Lengkapi data pengajuan cuti Anda</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form method="POST"
                  action="{{ route('leave-requests.store') }}"
                  enctype="multipart/form-data"
                  id="leaveForm"
                  class="p-6 space-y-6">
                @csrf

                <!-- Leave Type -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-[#083D1D]">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Jenis Cuti <span class="text-red-500 ml-1">*</span>
                        </div>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                            </svg>
                        </div>
                        <select name="leave_type" required
                                class="block w-full pl-10 pr-10 py-3 rounded-lg border border-[#DCE5DF] 
                                       bg-[#F9FAF7] focus:ring-2 focus:ring-[#F2B705] focus:border-[#0B5E2E] 
                                       focus:bg-white transition-colors text-[#083D1D] appearance-none">
                            <option value="" class="text-gray-500">Pilih jenis cuti</option>
                            @foreach ([
                                'tahunan'        => 'Cuti Tahunan',
                                'urusan_penting' => 'Cuti Urusan Penting',
                                'cuti_besar'     => 'Cuti Besar',
                                'cuti_non_aktif' => 'Cuti Non Aktif',
                                'cuti_bersalin'  => 'Cuti Bersalin',
                                'cuti_sakit'     => 'Cuti Sakit',
                            ] as $val => $label)
                                <option value="{{ $val }}" {{ old('leave_type') === $val ? 'selected' : '' }} class="text-[#083D1D]">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('leave_type')
                        <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Start Date -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#083D1D]">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Tanggal Mulai <span class="text-red-500 ml-1">*</span>
                            </div>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="date" name="start_date" id="start_date"
                                   value="{{ old('start_date') }}" required
                                   class="block w-full pl-10 pr-3 py-3 rounded-lg border border-[#DCE5DF] 
                                          bg-[#F9FAF7] focus:ring-2 focus:ring-[#F2B705] focus:border-[#0B5E2E] 
                                          focus:bg-white transition-colors text-[#083D1D]">
                        </div>
                        @error('start_date')
                            <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#083D1D]">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Tanggal Selesai <span class="text-red-500 ml-1">*</span>
                            </div>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="date" name="end_date" id="end_date"
                                   value="{{ old('end_date') }}" required
                                   class="block w-full pl-10 pr-3 py-3 rounded-lg border border-[#DCE5DF] 
                                          bg-[#F9FAF7] focus:ring-2 focus:ring-[#F2B705] focus:border-[#0B5E2E] 
                                          focus:bg-white transition-colors text-[#083D1D]">
                        </div>
                        @error('end_date')
                            <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Date Helper -->
                <div class="bg-[#F9FAF7] border border-[#DCE5DF] rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-[#0B5E2E] mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-[#083D1D]">
                            <p class="font-medium">Durasi Cuti: <span id="duration-display" class="font-bold text-[#0B5E2E]">0 hari</span></p>
                            <p class="text-gray-600 mt-1" id="date-range-display">Pilih tanggal mulai dan selesai untuk menghitung durasi</p>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-[#083D1D]">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Alasan / Detail Cuti <span class="text-red-500 ml-1">*</span>
                        </div>
                    </label>
                    <div class="relative">
                        <textarea name="reason" id="reason" rows="5" required
                                  class="block w-full px-4 py-3 rounded-lg border border-[#DCE5DF] 
                                         bg-[#F9FAF7] focus:ring-2 focus:ring-[#F2B705] focus:border-[#0B5E2E] 
                                         focus:bg-white transition-colors placeholder-gray-500 text-[#083D1D]"
                                  placeholder="Jelaskan alasan pengajuan cuti Anda dengan jelas...">{{ old('reason') }}</textarea>
                    </div>
                    @error('reason')
                        <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Attachment -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-[#083D1D]">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Dokumen Pendukung (opsional)
                        </div>
                    </label>
                    <div class="border-2 border-dashed border-[#DCE5DF] rounded-xl p-6 text-center hover:border-[#0B5E2E] hover:bg-[#F9FAF7] transition-colors duration-200">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm text-gray-600 mb-2">
                                <span class="font-medium text-[#0B5E2E]">Klik untuk upload</span> atau drag & drop
                            </p>
                            <input type="file" name="attachment" id="attachment"
                                   class="hidden"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <label for="attachment" 
                                   class="cursor-pointer inline-flex items-center px-4 py-2 border border-[#DCE5DF] rounded-lg text-sm font-medium text-[#083D1D] bg-white hover:bg-[#F9FAF7] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F2B705] transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Pilih File
                            </label>
                            <p class="text-xs text-gray-500 mt-3" id="file-name">
                                Maksimal 5MB (PDF, JPG, JPEG, PNG)
                            </p>
                        </div>
                    </div>
                    @error('attachment')
                        <div class="flex items-start space-x-2 text-red-600 text-sm mt-1 bg-red-50 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="pt-6 border-t border-[#DCE5DF]">
                    <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                        <button type="button" id="resetBtn"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-lg border border-[#DCE5DF] 
                                       text-[#083D1D] font-medium hover:bg-[#F9FAF7] hover:border-[#0B5E2E] 
                                       transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span id="resetText">Reset Form</span>
                        </button>
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 rounded-lg 
                                       bg-gradient-to-r from-[#F2B705] to-[#0B5E2E] text-white font-semibold 
                                       hover:from-[#F2B705]/90 hover:to-[#0B5E2E]/90 hover:shadow-lg 
                                       transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Kirim Pengajuan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] border border-[#DCE5DF] rounded-2xl p-6">
            <div class="flex items-start">
                <div class="p-2 bg-white rounded-lg shadow-sm mr-4">
                    <svg class="w-6 h-6 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-[#083D1D] mb-2">Informasi Penting</h3>
                    <ul class="text-sm text-[#083D1D] space-y-2">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-[#0B5E2E] mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Pengajuan akan diproses dalam 1-3 hari kerja
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-[#0B5E2E] mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Pastikan alasan cuti dijelaskan dengan jelas
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-[#0B5E2E] mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Upload dokumen pendukung untuk cuti sakit atau urusan penting
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const durationDisplay = document.getElementById('duration-display');
        const dateRangeDisplay = document.getElementById('date-range-display');
        const fileInput = document.getElementById('attachment');
        const fileNameDisplay = document.getElementById('file-name');
        const resetBtn = document.getElementById('resetBtn');
        const resetText = document.getElementById('resetText');
        const leaveForm = document.getElementById('leaveForm');
        
        // Function to calculate duration
        function calculateDuration() {
            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);
            
            if (startDateInput.value && endDateInput.value && start <= end) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                durationDisplay.textContent = diffDays + ' hari';
                
                // Format dates for display
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                const startFormatted = start.toLocaleDateString('id-ID', options);
                const endFormatted = end.toLocaleDateString('id-ID', options);
                
                dateRangeDisplay.textContent = `${startFormatted} - ${endFormatted}`;
            } else if (startDateInput.value && endDateInput.value && start > end) {
                durationDisplay.textContent = 'Tanggal tidak valid';
                dateRangeDisplay.textContent = 'Tanggal selesai harus setelah tanggal mulai';
                durationDisplay.classList.add('text-red-600');
            } else {
                durationDisplay.textContent = '0 hari';
                dateRangeDisplay.textContent = 'Pilih tanggal mulai dan selesai untuk menghitung durasi';
                durationDisplay.classList.remove('text-red-600');
            }
        }
        
        // Event listeners for date inputs
        startDateInput.addEventListener('change', calculateDuration);
        endDateInput.addEventListener('change', calculateDuration);
        
        // File upload display
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                fileNameDisplay.textContent = `${file.name} (${fileSize} MB)`;
            } else {
                fileNameDisplay.textContent = 'Maksimal 5MB (PDF, JPG, JPEG, PNG)';
            }
        });
        
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        startDateInput.min = today;
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
        });
        
        // Reset form function
        function resetForm() {
            // Reset the form
            leaveForm.reset();
            
            // Manually clear file input (HTML reset doesn't clear file inputs)
            fileInput.value = '';
            fileNameDisplay.textContent = 'Maksimal 5MB (PDF, JPG, JPEG, PNG)';
            
            // Reset duration display
            durationDisplay.textContent = '0 hari';
            dateRangeDisplay.textContent = 'Pilih tanggal mulai dan selesai untuk menghitung durasi';
            durationDisplay.classList.remove('text-red-600');
            
            // Clear validation error displays
            const errorDisplays = document.querySelectorAll('.text-red-600.text-sm');
            errorDisplays.forEach(error => {
                if (error.closest('form')) {
                    error.remove();
                }
            });
            
            // Clear border errors
            const inputs = leaveForm.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.classList.remove('border-red-500');
                input.classList.add('border-[#DCE5DF]');
            });
            
            // Provide visual feedback
            const originalHtml = resetBtn.innerHTML;
            resetBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Form telah direset
            `;
            
            resetBtn.classList.remove('border-[#DCE5DF]', 'text-[#083D1D]', 'hover:bg-[#F9FAF7]');
            resetBtn.classList.add('border-[#0B5E2E]', 'text-[#083D1D]', 'bg-[#F9FAF7]');
            
            // Revert after 2 seconds
            setTimeout(() => {
                resetBtn.innerHTML = originalHtml;
                resetBtn.classList.remove('border-[#0B5E2E]', 'text-[#083D1D]', 'bg-[#F9FAF7]');
                resetBtn.classList.add('border-[#DCE5DF]', 'text-[#083D1D]', 'hover:bg-[#F9FAF7]');
            }, 2000);
            
            // Focus on first input for better UX
            document.querySelector('select[name="leave_type"]').focus();
        }
        
        // Add click event to reset button
        resetBtn.addEventListener('click', resetForm);
    });
</script>
@endpush

<style>
    /* Custom file upload styling */
    input[type="file"] {
        cursor: pointer;
    }
    
    /* Smooth transitions */
    * {
        transition-property: background-color, border-color, color, transform, box-shadow;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Date picker styling */
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 0.6;
        filter: invert(0.5);
    }
    
    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }
    
    /* Validation styling */
    .border-red-500 {
        border-color: #ef4444 !important;
    }
    
    .text-red-600 {
        color: #dc2626;
    }
    
    .bg-red-50 {
        background-color: #fef2f2;
    }
</style>
@endsection