@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-2">
                <div class="p-2 bg-gradient-to-r from-blue-100 to-teal-100 rounded-xl mr-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Pegawai</h1>
            </div>
            <p class="text-gray-600 ml-11">Ringkasan informasi cuti dan pengajuan Anda</p>
        </div>

        <!-- Stats Cards - 4 Kartu -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Kartu Cuti Menunggu -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-100 p-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-amber-100 to-amber-50 rounded-xl shadow-sm">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">
                        Menunggu
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-2">Cuti Menunggu</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">
                    {{ $leaveStats['pending'] ?? 0 }}
                </p>
                <p class="text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Dalam proses persetujuan
                </p>
            </div>

            <!-- Kartu Cuti Disetujui -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-100 p-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-emerald-100 to-emerald-50 rounded-xl shadow-sm">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                        Disetujui
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-2">Cuti Disetujui</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">
                    {{ $leaveStats['approved'] ?? 0 }}
                </p>
                <p class="text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Telah mendapatkan persetujuan
                </p>
            </div>

            <!-- Kartu Sisa Cuti Tahun {{ $currentYear }} -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-blue-100 p-6 hover:shadow-2xl transition-shadow duration-300 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-blue-100 to-blue-50 rounded-xl shadow-sm">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                        Tahun Berjalan
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-2">Sisa Cuti {{ $currentYear }}</p>
                <p class="text-3xl font-bold text-blue-600 mb-1">
                    {{ $currentYearSummary['current_year_available'] ?? 0 }} hari
                </p>
                <div class="text-xs text-gray-500">
                    @if(isset($currentYearSummary['details'][$currentYear]))
                        @php
                            $detail = $currentYearSummary['details'][$currentYear];
                            $used = $detail['used'] ?? 0;
                            $quota = $detail['quota'] ?? 0;
                        @endphp
                        <div class="flex items-center justify-between mb-1">
                            <span>Terpakai: {{ $used }} hari</span>
                            <span>Kuota: {{ $quota }} hari</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-blue-500 to-teal-500 h-1.5 rounded-full" 
                                 style="width: {{ $quota > 0 ? min(100, round(($used / $quota) * 100)) : 0 }}%">
                            </div>
                        </div>
                    @else
                        <span class="text-amber-600">Kuota belum diatur</span>
                    @endif
                </div>
            </div>

            <!-- Kartu Sisa Cuti Tahun {{ $previousYear }} -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-teal-100 p-6 hover:shadow-2xl transition-shadow duration-300 border-l-4 border-teal-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-teal-100 to-teal-50 rounded-xl shadow-sm">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-teal-600 bg-teal-50 px-2 py-1 rounded-full">
                        Tahun Sebelumnya
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-2">Sisa Cuti {{ $previousYear }}</p>
                <p class="text-3xl font-bold text-teal-600 mb-1">
                    {{ $previousYearSummary['current_year_available'] ?? 0 }} hari
                </p>
                <div class="text-xs text-gray-500">
                    @if(isset($previousYearSummary['details'][$previousYear]))
                        @php
                            $detail = $previousYearSummary['details'][$previousYear];
                            $used = $detail['used'] ?? 0;
                            $quota = $detail['quota'] ?? 0;
                            $isExpired = $detail['is_expired'] ?? false;
                        @endphp
                        <div class="flex items-center justify-between mb-1">
                            <span>Terpakai: {{ $used }} hari</span>
                            <span>Kuota: {{ $quota }} hari</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-teal-500 to-emerald-500 h-1.5 rounded-full" 
                                 style="width: {{ $quota > 0 ? min(100, round(($used / $quota) * 100)) : 0 }}%">
                            </div>
                        </div>
                        @if($isExpired)
                            <div class="mt-1 text-amber-600">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Kuota telah kadaluarsa
                            </div>
                        @endif
                    @else
                        <span class="text-amber-600">Kuota belum diatur</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kartu Total Dapat Dipakai -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-emerald-100 p-6 hover:shadow-2xl transition-shadow duration-300 border-l-4 border-emerald-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-emerald-100 to-emerald-50 rounded-xl shadow-sm">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                        Total Tersedia
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-1">Tahun {{ $currentYear }}</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $currentYearSummary['current_year_available'] ?? 0 }}<span class="text-sm">hr</span>
                        </p>
                        @if(isset($currentYearSummary['details'][$currentYear]))
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $currentYearSummary['details'][$currentYear]['used'] ?? 0 }}/{{ $currentYearSummary['details'][$currentYear]['quota'] ?? 0 }} hari
                            </p>
                        @endif
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-1">Tahun {{ $previousYear }}</p>
                        <p class="text-2xl font-bold text-teal-600">
                            {{ $previousYearSummary['current_year_available'] ?? 0 }}<span class="text-sm">hr</span>
                        </p>
                        @if(isset($previousYearSummary['details'][$previousYear]))
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $previousYearSummary['details'][$previousYear]['used'] ?? 0 }}/{{ $previousYearSummary['details'][$previousYear]['quota'] ?? 0 }} hari
                            </p>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    @php
                        $totalCurrent = $currentYearSummary['current_year_available'] ?? 0;
                        $totalPrevious = $previousYearSummary['current_year_available'] ?? 0;
                        $totalAvailable = $totalCurrent + $totalPrevious;
                        $maxQuota = max(1, 
                            (($currentYearSummary['details'][$currentYear]['quota'] ?? 12) +
                            ($previousYearSummary['details'][$previousYear]['quota'] ?? 12))
                        );
                        $percentage = $maxQuota > 0 ? min(100, round(($totalAvailable / $maxQuota) * 100)) : 0;
                    @endphp
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Total Kuota Tersedia</span>
                        <span class="font-bold text-emerald-600">
                            {{ $totalAvailable }} hari
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 via-teal-500 to-emerald-500 h-2 rounded-full" 
                             style="width: {{ $percentage }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Termasuk sisa kuota tahun sebelumnya yang masih berlaku
                    </p>
                </div>
            </div>

            <!-- Kartu Cuti Ditolak -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-100 p-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-red-100 to-red-50 rounded-xl shadow-sm">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                        Ditolak
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-2">Cuti Ditolak</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">
                    {{ $leaveStats['rejected'] ?? 0 }}
                </p>
                <p class="text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Tidak mendapatkan persetujuan
                </p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total Pengajuan:</span>
                        <span class="font-bold text-gray-800">
                            {{ $leaveStats['pending'] + $leaveStats['approved'] + $leaveStats['rejected'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-2">
                        <span class="text-gray-600">Persentase Disetujui:</span>
                        <span class="font-bold text-emerald-600">
                            @php
                                $total = $leaveStats['pending'] + $leaveStats['approved'] + $leaveStats['rejected'];
                                $rate = $total > 0 ? round(($leaveStats['approved'] / $total) * 100) : 0;
                            @endphp
                            {{ $rate }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Ringkasan 2 Tahun -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-800">Ringkasan Kuota 2 Tahun</h3>
                        </div>
                        <span class="text-sm text-gray-600">
                            {{ $previousYear }} - {{ $currentYear }}
                        </span>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @php
                        $yearsData = [
                            $currentYear => $currentYearSummary['details'][$currentYear] ?? null,
                            $previousYear => $previousYearSummary['details'][$previousYear] ?? null,
                        ];
                    @endphp
                    
                    @foreach($yearsData as $year => $detail)
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 
                                        @if($year == $currentYear) bg-blue-50 text-blue-600
                                        @else bg-teal-50 text-teal-600
                                        @endif 
                                        rounded-lg mr-4">
                                        <span class="text-sm font-bold">{{ $year }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            @if($detail)
                                                {{ $detail['available'] }} hari tersisa
                                                @if($detail['is_expired'] ?? false)
                                                    <span class="ml-2 text-xs text-amber-600">(Kadaluarsa)</span>
                                                @endif
                                            @else
                                                Kuota belum diatur
                                            @endif
                                        </p>
                                        @if($detail)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Terpakai: {{ $detail['used'] }} hari | 
                                                Kuota: {{ $detail['quota'] }} hari |
                                                Sisa: {{ $detail['available'] }} hari
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($detail)
                                        @if($detail['available'] > 0)
                                            <span class="text-xs px-2 py-1 rounded-full 
                                                @if($year == $currentYear) bg-blue-50 text-blue-700
                                                @else bg-teal-50 text-teal-700
                                                @endif">
                                                Tersedia
                                            </span>
                                        @else
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">
                                                Habis
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-700">
                                            Belum diatur
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Progress Bar -->
                            @if($detail && $detail['quota'] > 0)
                                @php
                                    $usedPercentage = round(($detail['used'] / $detail['quota']) * 100);
                                @endphp
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Penggunaan</span>
                                        <span>{{ $usedPercentage }}%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full 
                                            @if($year == $currentYear) bg-gradient-to-r from-blue-500 to-teal-500
                                            @else bg-gradient-to-r from-teal-500 to-emerald-500
                                            @endif 
                                            rounded-full transition-all duration-500" 
                                             style="width: {{ $usedPercentage }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pengajuan Terbaru -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-800">Pengajuan Terbaru</h3>
                        </div>
                        <a href="{{ route('leave-requests.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                            Lihat semua
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentLeaves as $leave)
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $leave->leave_type) }}</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $leave->start_date?->format('d M Y') }} - {{ $leave->end_date?->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="text-xs px-3 py-1.5 rounded-full font-medium
                                    @class([
                                        'bg-amber-50 text-amber-700 border border-amber-200' => $leave->status === 'pending',
                                        'bg-emerald-50 text-emerald-700 border border-emerald-200' => $leave->status === 'approved',
                                        'bg-red-50 text-red-700 border border-red-200' => $leave->status === 'rejected',
                                    ])">
                                    {{ match($leave->status) {
                                        'approved' => 'Disetujui',
                                        'pending'  => 'Menunggu',
                                        'rejected' => 'Ditolak',
                                        default    => ucfirst($leave->status),
                                    } }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $leave->days }} hari</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    Diajukan: {{ $leave->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <div class="mx-auto w-16 h-16 text-gray-300 mb-4">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada pengajuan cuti</p>
                            <p class="text-gray-400 text-sm mt-1">Mulai dengan mengajukan cuti baru</p>
                            <a href="{{ route('leave-requests.create') }}"
                               class="inline-flex items-center mt-4 px-4 py-2 rounded-lg 
                                      bg-gradient-to-r from-blue-600 to-teal-600 text-white text-sm font-medium 
                                      hover:from-blue-700 hover:to-teal-700 hover:shadow-lg transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Ajukan Cuti
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Akses Cepat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('leave-requests.create') }}" 
                   class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 hover:from-blue-100 hover:to-blue-200 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                            <svg class="w-5 h-5 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Ajukan Cuti</p>
                            <p class="text-sm text-gray-600">Buat pengajuan cuti baru</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('leave-requests.index') }}" 
                   class="bg-gradient-to-r from-teal-50 to-teal-100 border border-teal-200 rounded-xl p-4 hover:from-teal-100 hover:to-teal-200 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                            <svg class="w-5 h-5 text-teal-600 group-hover:text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Riwayat Cuti</p>
                            <p class="text-sm text-gray-600">Lihat semua pengajuan</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('profile.edit') }}" 
                   class="bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl p-4 hover:from-emerald-100 hover:to-emerald-200 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                            <svg class="w-5 h-5 text-emerald-600 group-hover:text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Profil Saya</p>
                            <p class="text-sm text-gray-600">Kelola data pribadi</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .hover\:shadow-2xl {
        transition: box-shadow 0.3s ease-in-out;
    }
    
    .transition-shadow {
        transition-property: box-shadow;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Smooth transitions */
    * {
        transition-property: background-color, border-color, color, transform, box-shadow;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
</style>
@endsection