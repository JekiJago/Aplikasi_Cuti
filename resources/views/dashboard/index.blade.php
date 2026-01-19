@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
@php
    // Definisi warna resmi Kejaksaan
    $primaryColor = '#F2B705';      // Kuning Emas
    $secondaryColor = '#0B5E2E';    // Hijau Kejaksaan
    $darkColor = '#083D1D';         // Hijau tua
    $backgroundColor = '#F9FAF7';   // Putih / Abu muda
    $borderColor = '#DCE5DF';       // Abu kehijauan
@endphp

<div class="min-h-screen bg-gradient-to-b from-[#F9FAF7] to-[#DCE5DF]/30 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-2">
                <div class="p-2 bg-gradient-to-r from-[#F2B705]/10 to-[#0B5E2E]/10 rounded-xl mr-3">
                    <svg class="w-6 h-6 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-[#083D1D]">Dashboard Pegawai</h1>
            </div>
            <p class="text-[#083D1D]/70 ml-11">Ringkasan informasi cuti dan pengajuan Anda</p>
        </div>

        <!-- Stats Cards - 4 Kartu -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Kartu Cuti Menunggu -->
            <div class="bg-gradient-to-br from-white to-[#F9FAF7] rounded-2xl shadow-xl border border-[#DCE5DF] p-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-[#F2B705]/10 to-[#F2B705]/5 rounded-xl shadow-sm border border-[#F2B705]/20">
                        <svg class="w-7 h-7 text-[#F2B705]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-[#083D1D] bg-[#F2B705]/10 px-2 py-1 rounded-full border border-[#F2B705]/20">
                        Menunggu
                    </span>
                </div>
                <p class="text-sm text-[#083D1D]/70 mb-2">Cuti Menunggu</p>
                <p class="text-3xl font-bold text-[#083D1D] mb-1">
                    {{ $leaveStats['pending'] ?? 0 }}
                </p>
                <p class="text-xs text-[#083D1D]/70 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-[#F2B705]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Dalam proses persetujuan
                </p>
            </div>

            <!-- Kartu Cuti Disetujui -->
            <div class="bg-gradient-to-br from-white to-[#F9FAF7] rounded-2xl shadow-xl border border-[#DCE5DF] p-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-[#0B5E2E]/10 to-[#0B5E2E]/5 rounded-xl shadow-sm border border-[#0B5E2E]/20">
                        <svg class="w-7 h-7 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-[#083D1D] bg-[#0B5E2E]/10 px-2 py-1 rounded-full border border-[#0B5E2E]/20">
                        Disetujui
                    </span>
                </div>
                <p class="text-sm text-[#083D1D]/70 mb-2">Cuti Disetujui</p>
                <p class="text-3xl font-bold text-[#083D1D] mb-1">
                    {{ $leaveStats['approved'] ?? 0 }}
                </p>
                <p class="text-xs text-[#083D1D]/70 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-[#0B5E2E]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Telah mendapatkan persetujuan
                </p>
            </div>

            <!-- Kartu Sisa Cuti Tahun {{ $currentYear }} -->
            <div class="bg-gradient-to-br from-white to-[#F9FAF7] rounded-2xl shadow-xl border border-[#0B5E2E]/20 p-6 hover:shadow-2xl transition-shadow duration-300 border-l-4 border-[#0B5E2E]">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-[#0B5E2E]/10 to-[#0B5E2E]/5 rounded-xl shadow-sm border border-[#0B5E2E]/20">
                        <svg class="w-7 h-7 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-[#0B5E2E] bg-[#0B5E2E]/10 px-2 py-1 rounded-full border border-[#0B5E2E]/20">
                        Tahun Berjalan
                    </span>
                </div>
                <p class="text-sm text-[#083D1D]/70 mb-2">Sisa Cuti {{ $currentYear }}</p>
                <p class="text-3xl font-bold text-[#0B5E2E] mb-1">
                    {{ $kuotaDetail['tahun_sekarang']['sisa'] ?? 0 }} hari
                </p>
                <div class="text-xs text-[#083D1D]/70">
                    @if($kuotaDetail['tahun_sekarang'])
                        @php
                            $detail = $kuotaDetail['tahun_sekarang'];
                        @endphp
                        <div class="flex items-center justify-between mb-1">
                            <span>Terpakai: {{ $detail['dipakai'] }} hari</span>
                            <span>Kuota: {{ $detail['kuota'] }} hari</span>
                        </div>
                        <div class="w-full bg-[#DCE5DF] rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-[#0B5E2E] to-[#083D1D] h-1.5 rounded-full" 
                                 style="width: {{ $detail['kuota'] > 0 ? min(100, round(($detail['dipakai'] / $detail['kuota']) * 100)) : 0 }}%">
                            </div>
                        </div>
                    @else
                        <span class="text-[#F2B705]">Kuota belum diatur</span>
                    @endif
                </div>
            </div>

            <!-- Kartu Sisa Cuti Tahun {{ $previousYear }} -->
            <div class="bg-gradient-to-br from-white to-[#F9FAF7] rounded-2xl shadow-xl border border-[#0B5E2E]/10 p-6 hover:shadow-2xl transition-shadow duration-300 border-l-4 border-[#083D1D]">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-[#083D1D]/10 to-[#083D1D]/5 rounded-xl shadow-sm border border-[#083D1D]/20">
                        <svg class="w-7 h-7 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-[#083D1D] bg-[#083D1D]/10 px-2 py-1 rounded-full border border-[#083D1D]/20">
                        Tahun Sebelumnya
                    </span>
                </div>
                <p class="text-sm text-[#083D1D]/70 mb-2">Sisa Cuti {{ $previousYear }}</p>
                <p class="text-3xl font-bold text-[#083D1D] mb-1">
                    {{ $kuotaDetail['tahun_lalu']['sisa'] ?? 0 }} hari
                </p>
                <div class="text-xs text-[#083D1D]/70">
                    @if($kuotaDetail['tahun_lalu'])
                        @php
                            $detail = $kuotaDetail['tahun_lalu'];
                        @endphp
                        <div class="flex items-center justify-between mb-1">
                            <span>Terpakai: {{ $detail['dipakai'] }} hari</span>
                            <span>Kuota: {{ $detail['kuota'] }} hari</span>
                        </div>
                        <div class="w-full bg-[#DCE5DF] rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-[#083D1D] to-[#0B5E2E] h-1.5 rounded-full" 
                                 style="width: {{ $detail['kuota'] > 0 ? min(100, round(($detail['dipakai'] / $detail['kuota']) * 100)) : 0 }}%">
                            </div>
                        </div>
                    @else
                        <span class="text-[#F2B705]">Kuota belum diatur</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kartu Total Dapat Dipakai -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-br from-white to-[#F9FAF7] rounded-2xl shadow-xl border border-[#0B5E2E]/20 p-6 hover:shadow-2xl transition-shadow duration-300 border-l-4 border-[#0B5E2E]">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-[#0B5E2E]/10 to-[#0B5E2E]/5 rounded-xl shadow-sm border border-[#0B5E2E]/20">
                        <svg class="w-7 h-7 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-[#083D1D] bg-[#0B5E2E]/10 px-2 py-1 rounded-full border border-[#0B5E2E]/20">
                        Total Tersedia
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-[#083D1D]/70 mb-1">Tahun {{ $currentYear }}</p>
                        <p class="text-2xl font-bold text-[#0B5E2E]">
                            {{ $kuotaDetail['tahun_sekarang']['sisa'] ?? 0 }}<span class="text-sm">hr</span>
                        </p>
                        @if($kuotaDetail['tahun_sekarang'])
                            <p class="text-xs text-[#083D1D]/70 mt-1">
                                {{ $kuotaDetail['tahun_sekarang']['dipakai'] }}/{{ $kuotaDetail['tahun_sekarang']['kuota'] }} hari
                            </p>
                        @endif
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-[#083D1D]/70 mb-1">Tahun {{ $previousYear }}</p>
                        <p class="text-2xl font-bold text-[#083D1D]">
                            {{ $kuotaDetail['tahun_lalu']['sisa'] ?? 0 }}<span class="text-sm">hr</span>
                        </p>
                        @if($kuotaDetail['tahun_lalu'])
                            <p class="text-xs text-[#083D1D]/70 mt-1">
                                {{ $kuotaDetail['tahun_lalu']['dipakai'] }}/{{ $kuotaDetail['tahun_lalu']['kuota'] }} hari
                            </p>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    @php
                        $totalAvailable = $kuotaDetail['total_tersedia'];
                        $totalQuota = ($kuotaDetail['tahun_sekarang']['kuota'] ?? 0) + 
                                     ($kuotaDetail['tahun_lalu']['kuota'] ?? 0);
                        $percentage = $totalQuota > 0 ? min(100, round(($totalAvailable / $totalQuota) * 100)) : 0;
                    @endphp
                    <div class="flex justify-between text-xs text-[#083D1D]/70 mb-1">
                        <span>Total Kuota Tersedia</span>
                        <span class="font-bold text-[#0B5E2E]">
                            {{ $totalAvailable }} hari
                        </span>
                    </div>
                    <div class="w-full bg-[#DCE5DF] rounded-full h-2">
                        <div class="bg-gradient-to-r from-[#0B5E2E] via-[#083D1D] to-[#0B5E2E] h-2 rounded-full" 
                             style="width: {{ $percentage }}%">
                        </div>
                    </div>
                    <p class="text-xs text-[#083D1D]/70 mt-2">
                        Termasuk sisa kuota tahun sebelumnya yang masih berlaku
                    </p>
                </div>
            </div>

            <!-- Kartu Cuti Ditolak -->
            <div class="bg-gradient-to-br from-white to-[#F9FAF7] rounded-2xl shadow-xl border border-[#DCE5DF] p-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-red-100 to-red-50 rounded-xl shadow-sm border border-red-200">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full border border-red-200">
                        Ditolak
                    </span>
                </div>
                <p class="text-sm text-[#083D1D]/70 mb-2">Cuti Ditolak</p>
                <p class="text-3xl font-bold text-[#083D1D] mb-1">
                    {{ $leaveStats['rejected'] ?? 0 }}
                </p>
                <p class="text-xs text-[#083D1D]/70 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Tidak mendapatkan persetujuan
                </p>
                <div class="mt-4 pt-4 border-t border-[#DCE5DF]">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-[#083D1D]/70">Total Pengajuan:</span>
                        <span class="font-bold text-[#083D1D]">
                            {{ $leaveStats['pending'] + $leaveStats['approved'] + $leaveStats['rejected'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-2">
                        <span class="text-[#083D1D]/70">Persentase Disetujui:</span>
                        <span class="font-bold text-[#0B5E2E]">
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
            </div>
        </div>
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