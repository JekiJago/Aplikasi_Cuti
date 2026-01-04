@extends('layouts.app')

@section('content')
@php
    // Gunakan data dari controller - INI PERUBAHAN PENTING
    $totalActiveLeave = $totalActiveLeave ?? 0;
    $activeLeaveDetails = $activeLeaveDetails ?? [];
    $currentYear = $currentYear ?? now()->year;
    $prevYear = $prevYear ?? $currentYear - 1;
    $currentYearUsed = $currentYearUsed ?? 0;
    $currentYearQuota = $currentYearQuota ?? 0;
    $usedFromPrev = $usedFromPrev ?? 0;
    $usedFromCurrent = $usedFromCurrent ?? 0;
    
    // Cari detail tahun sebelumnya dan berjalan dari activeLeaveDetails
    $prevYearDetail = collect($activeLeaveDetails)->firstWhere('year', $prevYear);
    $currentYearDetail = collect($activeLeaveDetails)->firstWhere('year', $currentYear);
    
    $prevYearRemaining = $prevYearDetail['remaining'] ?? 0;
    $currentYearRemaining = $currentYearDetail['remaining'] ?? 0;
    $isPrevExpired = $prevYearDetail['is_expired'] ?? false;
    
    // Warna untuk card Sisa Cuti Aktif
    if ($totalActiveLeave >= 20) {
        $bgColor = 'bg-gradient-to-br from-emerald-50 to-teal-100';
        $borderColor = 'border-emerald-200';
        $textColor = 'text-emerald-800';
        $numberColor = 'text-emerald-700';
        $iconColor = 'text-emerald-600';
        $iconBg = 'bg-emerald-100';
    } elseif ($totalActiveLeave >= 10) {
        $bgColor = 'bg-gradient-to-br from-blue-50 to-indigo-100';
        $borderColor = 'border-blue-200';
        $textColor = 'text-blue-800';
        $numberColor = 'text-blue-700';
        $iconColor = 'text-blue-600';
        $iconBg = 'bg-blue-100';
    } elseif ($totalActiveLeave >= 5) {
        $bgColor = 'bg-gradient-to-br from-amber-50 to-yellow-100';
        $borderColor = 'border-amber-200';
        $textColor = 'text-amber-800';
        $numberColor = 'text-amber-700';
        $iconColor = 'text-amber-600';
        $iconBg = 'bg-amber-100';
    } else {
        $bgColor = 'bg-gradient-to-br from-orange-50 to-red-100';
        $borderColor = 'border-orange-200';
        $textColor = 'text-orange-800';
        $numberColor = 'text-orange-700';
        $iconColor = 'text-orange-600';
        $iconBg = 'bg-orange-100';
    }
    
    // Warna untuk card Cuti Terpakai
    $percentUsed = $currentYearQuota > 0 ? round(($currentYearUsed / $currentYearQuota) * 100) : 0;
    if ($percentUsed <= 50) {
        $usedBgColor = 'bg-gradient-to-br from-emerald-50 to-teal-100';
        $usedBorderColor = 'border-emerald-200';
        $usedTextColor = 'text-emerald-800';
        $usedNumberColor = 'text-emerald-700';
        $usedIconColor = 'text-emerald-600';
        $usedIconBg = 'bg-emerald-100';
        $progressColor = 'bg-emerald-500';
    } elseif ($percentUsed <= 75) {
        $usedBgColor = 'bg-gradient-to-br from-amber-50 to-yellow-100';
        $usedBorderColor = 'border-amber-200';
        $usedTextColor = 'text-amber-800';
        $usedNumberColor = 'text-amber-700';
        $usedIconColor = 'text-amber-600';
        $usedIconBg = 'bg-amber-100';
        $progressColor = 'bg-amber-500';
    } else {
        $usedBgColor = 'bg-gradient-to-br from-orange-50 to-red-100';
        $usedBorderColor = 'border-orange-200';
        $usedTextColor = 'text-orange-800';
        $usedNumberColor = 'text-orange-700';
        $usedIconColor = 'text-orange-600';
        $usedIconBg = 'bg-orange-100';
        $progressColor = 'bg-orange-500';
    }
@endphp

<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-6">                
                    <!-- Employee Info -->
                    <div class="space-y-2">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $employee->name }}</h1>
                            <div class="flex flex-wrap items-center gap-3 mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                    {{ $employee->employee_id }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-sm font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Pegawai Aktif
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-medium capitalize">
                                    {{ $employee->gender === 'male' ? 'Pria' : 'Wanita' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="flex flex-wrap gap-4 pt-2">
                            <div class="flex items-center">
                                <div class="p-1.5 bg-blue-50 rounded-lg mr-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Total Pengajuan</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ $leaves->count() }} kali</p>
                                </div>
                            </div>
                            <!-- SISA CUTI AKTIF -->
                            <div class="flex items-center">
                                <div class="p-1.5 bg-emerald-50 rounded-lg mr-2">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Sisa Cuti Aktif</p>
                                    <p class="text-sm font-semibold text-emerald-700">{{ $totalActiveLeave }} hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.employees.edit', $employee) }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-gray-300 
                              text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 
                              transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Data & Kuota
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

        <!-- Main Content with Tabs -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200">
                <nav class="flex" aria-label="Tabs">
                    <button type="button" data-tab="summary" 
                            class="tab-button flex-1 py-4 px-6 text-center font-medium text-sm border-b-2 border-blue-500 text-blue-600 bg-blue-50">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Ringkasan
                    </button>
                    <button type="button" data-tab="leave-history"
                            class="tab-button flex-1 py-4 px-6 text-center font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Riwayat Cuti
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Tab 1: Ringkasan -->
                <div class="tab-content" id="summary-tab">
                    <!-- Stats Grid - 2 Card System dengan FIFO -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Card 1: SISA CUTI AKTIF (2 Tahun dengan FIFO) -->
                        <div>
                            <div class="bg-white rounded-xl border {{ $borderColor }} p-6 h-full {{ $bgColor }}">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold {{ $textColor }}">Sisa Cuti Aktif</h3>
                                    <div class="p-3 {{ $iconBg }} rounded-xl shadow-sm">
                                        <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <p class="text-4xl font-bold {{ $numberColor }} mb-2">{{ $totalActiveLeave }} hari</p>
                                <p class="text-sm {{ $textColor }} mb-4">
                                    Total sisa cuti yang masih dapat digunakan
                                    <span class="block text-xs opacity-75 mt-1">(Sistem FIFO: kuota {{ $prevYear }} dipakai dulu)</span>
                                </p>
                                
                                <!-- Breakdown per tahun -->
                                <div class="mt-6 pt-4 border-t {{ $borderColor }}">
                                    <p class="text-sm font-medium {{ $textColor }} mb-3">Detail kuota:</p>
                                    <div class="space-y-4">
                                        <!-- Tahun Sebelumnya (2024) -->
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 {{ $isPrevExpired ? 'bg-red-500' : 'bg-blue-500' }} rounded-full mr-2"></div>
                                                    <span class="text-sm font-medium {{ $textColor }}">
                                                        Cuti {{ $prevYear }}:
                                                        @if($isPrevExpired)
                                                            <span class="text-xs text-red-600 ml-1">(hangus)</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <span class="text-sm font-semibold {{ $isPrevExpired ? 'text-red-600' : $numberColor }}">
                                                    {{ $prevYearRemaining }} hari
                                                </span>
                                            </div>
                                            <div class="ml-5 text-xs {{ $textColor }} opacity-80">
                                                Kuota: {{ $prevYearDetail['total'] ?? 0 }} hari • Terpakai: {{ $prevYearDetail['used'] ?? 0 }} hari
                                                @if($usedFromPrev > 0)
                                                    <span class="block text-green-600 font-medium mt-1">
                                                        ✓ {{ $usedFromPrev }} hari digunakan tahun {{ $currentYear }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Tahun Berjalan (2025) -->
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                                    <span class="text-sm font-medium {{ $textColor }}">Cuti {{ $currentYear }}:</span>
                                                </div>
                                                <span class="text-sm font-semibold {{ $numberColor }}">
                                                    {{ $currentYearRemaining }} hari
                                                </span>
                                            </div>
                                            <div class="ml-5 text-xs {{ $textColor }} opacity-80">
                                                Kuota: {{ $currentYearDetail['total'] ?? 0 }} hari • Terpakai: {{ $currentYearDetail['used'] ?? 0 }} hari
                                                @if($usedFromCurrent > 0)
                                                    <span class="block text-green-600 font-medium mt-1">
                                                        ✓ {{ $usedFromCurrent }} hari digunakan tahun {{ $currentYear }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Info Sistem FIFO -->
                                    <div class="mt-4 p-3 bg-white bg-opacity-50 rounded-lg border {{ $borderColor }}">
                                        <p class="text-xs {{ $textColor }}">
                                            <span class="font-semibold">Sistem FIFO (First In First Out):</span> 
                                            <span class="block mt-1">
                                                1. Kuota {{ $prevYear }} dipakai terlebih dahulu
                                                <br>2. Baru kuota {{ $currentYear }} dipakai
                                                <br>3. Cuti {{ $prevYear }} hangus: 31 Des {{ $currentYear }}
                                            </span>
                                            @if($isPrevExpired)
                                                <span class="text-red-600 block mt-1">
                                                    ⚠️ Cuti {{ $prevYear }} sudah hangus sejak 1 Jan {{ $prevYear + 2 }}
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: CUTI TERPAKAI (Tahun Berjalan) -->
                        <div>
                            <div class="bg-white rounded-xl border {{ $usedBorderColor }} p-6 h-full {{ $usedBgColor }}">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold {{ $usedTextColor }}">Cuti Terpakai {{ $currentYear }}</h3>
                                    <div class="p-3 {{ $usedIconBg }} rounded-xl shadow-sm">
                                        <svg class="w-6 h-6 {{ $usedIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <p class="text-4xl font-bold {{ $usedNumberColor }} mb-2">{{ $currentYearUsed }} hari</p>
                                <p class="text-sm {{ $usedTextColor }} mb-4">
                                    Total cuti yang telah digunakan tahun {{ $currentYear }}
                                    <span class="block text-xs opacity-75 mt-1">
                                        @if($usedFromPrev > 0)
                                            ({{ $usedFromPrev }} hari dari kuota {{ $prevYear }} + {{ $usedFromCurrent }} hari dari kuota {{ $currentYear }})
                                        @else
                                            (Semua dari kuota {{ $currentYear }})
                                        @endif
                                    </span>
                                </p>
                                
                                <!-- Progress Bar & Detail -->
                                <div class="mt-6 pt-4 border-t {{ $usedBorderColor }}">
                                    <!-- Progress Bar -->
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm {{ $usedTextColor }} mb-2">
                                            <span>Tingkat Penggunaan Kuota {{ $currentYear }}</span>
                                            <span class="font-semibold">{{ $percentUsed }}%</span>
                                        </div>
                                        <div class="w-full h-3 bg-white bg-opacity-50 rounded-full overflow-hidden border {{ $usedBorderColor }}">
                                            <div class="h-full rounded-full {{ $progressColor }} transition-all duration-500" 
                                                 style="width: {{ min(100, $percentUsed) }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs {{ $usedTextColor }} mt-1">
                                            <span>0%</span>
                                            <span>50%</span>
                                            <span>75%</span>
                                            <span>100%</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Detail Sumber Cuti (FIFO Breakdown) -->
                                    <div class="space-y-3">
                                        <p class="text-sm font-medium {{ $usedTextColor }} mb-2">Sumber cuti terpakai:</p>
                                        
                                        @if($usedFromPrev > 0)
                                        <div class="flex justify-between items-center p-2 bg-blue-50 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-sm text-blue-700">Dari kuota {{ $prevYear }}</span>
                                            </div>
                                            <span class="text-sm font-semibold text-blue-700">{{ $usedFromPrev }} hari</span>
                                        </div>
                                        @endif
                                        
                                        @if($usedFromCurrent > 0)
                                        <div class="flex justify-between items-center p-2 bg-green-50 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-sm text-green-700">Dari kuota {{ $currentYear }}</span>
                                            </div>
                                            <span class="text-sm font-semibold text-green-700">{{ $usedFromCurrent }} hari</span>
                                        </div>
                                        @endif
                                        
                                        <!-- Info FIFO -->
                                        <div class="mt-3 p-3 bg-white bg-opacity-50 rounded-lg border {{ $usedBorderColor }}">
                                            <p class="text-xs {{ $usedTextColor }}">
                                                <span class="font-semibold">Catatan FIFO:</span> 
                                                Sistem memotong kuota {{ $prevYear }} terlebih dahulu sebelum menggunakan kuota {{ $currentYear }}.
                                                @if($usedFromPrev > 0)
                                                    <span class="block mt-1 text-green-600">
                                                        ✓ {{ $usedFromPrev }} hari sudah dipotong dari kuota {{ $prevYear }}
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div>
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-6">Ringkasan Status Pengajuan</h2>
                            
                            <div class="space-y-4">
                                <!-- Pending -->
                                <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-xl p-4 border border-amber-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-amber-100 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-amber-900">Menunggu</p>
                                                <p class="text-xs text-amber-700">Menunggu persetujuan</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-amber-800">{{ $summary['pending'] ?? 0 }}</span>
                                    </div>
                                </div>

                                <!-- Approved -->
                                <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-emerald-100 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-emerald-900">Disetujui</p>
                                                <p class="text-xs text-emerald-700">Cuti telah disetujui</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-emerald-800">{{ $summary['approved'] ?? 0 }}</span>
                                    </div>
                                </div>

                                <!-- Rejected -->
                                <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-red-100 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-red-900">Ditolak</p>
                                                <p class="text-xs text-red-700">Cuti tidak disetujui</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-red-800">{{ $summary['rejected'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-700">Total Pengajuan</p>
                                    <p class="text-lg font-bold text-gray-800">{{ ($summary['pending'] ?? 0) + ($summary['approved'] ?? 0) + ($summary['rejected'] ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Riwayat Cuti -->
                <div class="tab-content hidden" id="leave-history-tab">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Riwayat Pengajuan Cuti</h2>

                    @forelse($leaves as $leave)
                        @php
                            $statusConfig = [
                                'pending' => [
                                    'bg' => 'bg-amber-50', 
                                    'text' => 'text-amber-800',
                                    'border' => 'border-amber-200',
                                    'icon' => '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                    'label' => 'Menunggu'
                                ],
                                'approved' => [
                                    'bg' => 'bg-emerald-50', 
                                    'text' => 'text-emerald-800',
                                    'border' => 'border-emerald-200',
                                    'icon' => '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                                    'label' => 'Disetujui'
                                ],
                                'rejected' => [
                                    'bg' => 'bg-red-50', 
                                    'text' => 'text-red-800',
                                    'border' => 'border-red-200',
                                    'icon' => '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                                    'label' => 'Ditolak'
                                ]
                            ][$leave->status] ?? [
                                'bg' => 'bg-gray-50', 
                                'text' => 'text-gray-800',
                                'border' => 'border-gray-200',
                                'icon' => '',
                                'label' => ucfirst($leave->status)
                            ];
                        @endphp
                        
                        <div class="rounded-xl border {{ $statusConfig['border'] }} p-4 mb-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <!-- Left Section -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                                {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}
                                            </h3>
                                            <p class="text-sm text-gray-600 mb-3">{{ $leave->reason }}</p>
                                            
                                            <div class="flex flex-wrap gap-4">
                                                <div class="flex items-center">
                                                    <div class="p-1.5 bg-gray-100 rounded-lg mr-2">
                                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500">Periode</p>
                                                        <p class="text-sm font-medium text-gray-800">
                                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center">
                                                    <div class="p-1.5 bg-gray-100 rounded-lg mr-2">
                                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500">Durasi</p>
                                                        <p class="text-sm font-medium text-gray-800">{{ $leave->days }} hari</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                            {!! $statusConfig['icon'] !!}
                                            {{ $statusConfig['label'] }}
                                        </span>
                                    </div>
                                    
                                    <!-- Metadata -->
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Diajukan: {{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y, H:i') }}
                                            </div>
                                            @if($leave->status === 'approved' || $leave->status === 'rejected')
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                    Diperbarui: {{ \Carbon\Carbon::parse($leave->updated_at)->format('d M Y, H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="mx-auto w-20 h-20 text-gray-300 mb-4">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada riwayat cuti</p>
                            <p class="text-gray-400 text-sm mt-1">Pegawai ini belum pernah mengajukan cuti</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.dataset.tab;
                
                // Update tab buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50');
                    btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                });
                
                this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                this.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
                
                // Show selected tab content
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                document.getElementById(`${tabId}-tab`).classList.remove('hidden');
            });
        });
    });
</script>
@endpush