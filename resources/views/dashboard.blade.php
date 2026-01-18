@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-2">
                <div class="p-2 bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] rounded-xl mr-3">
                    <svg class="w-6 h-6 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-[#083D1D]">Dashboard Pegawai</h1>
            </div>
            <p class="text-gray-600 ml-11">Ringkasan informasi cuti dan pengajuan Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Kuota Tahunan -->
            <div class="bg-gradient-to-br from-white to-[#F9FAF7] rounded-2xl shadow-xl border border-[#DCE5DF] p-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] rounded-xl shadow-sm">
                        <svg class="w-7 h-7 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-[#0B5E2E] bg-[#F9FAF7] px-2 py-1 rounded-full border border-[#DCE5DF]">
                        Tahunan
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-2">Kuota Tahunan</p>
                <p class="text-3xl font-bold text-[#083D1D] mb-1">
                    {{ $annualSummary['quota_per_year'] ?? 0 }} hari
                </p>
                <p class="text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-[#0B5E2E]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Alokasi standar per tahun
                </p>
            </div>

            <!-- Sisa Tahun Berjalan -->
            <div class="bg-gradient-to-br from-white to-[#F9FAF7] rounded-2xl shadow-xl border border-[#DCE5DF] p-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-xl shadow-sm">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full border border-green-200">
                        Tersedia
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-2">Sisa Tahun Berjalan</p>
                <p class="text-3xl font-bold text-green-600 mb-1">
                    {{ $annualSummary['current_year_available'] ?? 0 }} hari
                </p>
                <p class="text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Sisa kuota tahun {{ date('Y') }}
                </p>
            </div>

            <!-- Total Dapat Dipakai -->
            <div class="bg-gradient-to-br from-white to-[#F9FAF7] rounded-2xl shadow-xl border border-[#DCE5DF] p-6 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] rounded-xl shadow-sm">
                        <svg class="w-7 h-7 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    @if(($annualSummary['total_available'] ?? 0) > 0)
                        <span class="text-xs font-medium text-[#083D1D] bg-[#F9FAF7] px-2 py-1 rounded-full border border-[#DCE5DF]">
                            {{ $annualSummary['total_available'] }} hari
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mb-2">Total Dapat Dipakai</p>
                <p class="text-3xl font-bold text-[#0B5E2E] mb-1">
                    {{ $annualSummary['total_available'] ?? 0 }} hari
                </p>
                <p class="text-xs text-gray-500">
                    @if(optional($annualSummary['carry_over_expires_at'] ?? null))
                        <svg class="w-3 h-3 inline mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Termasuk carry-over hingga {{ $annualSummary['carry_over_expires_at']->format('d M Y') }}
                    @else
                        Kuota tahunan tersedia
                    @endif
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Ringkasan Tahunan -->
            <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] overflow-hidden">
                <div class="bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] px-6 py-4 border-b border-[#DCE5DF]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-[#083D1D] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-[#083D1D]">Ringkasan Tahunan</h3>
                        </div>
                        <span class="text-sm text-[#083D1D]">
                            {{ count($annualSummary['details'] ?? []) }} tahun
                        </span>
                    </div>
                </div>
                <div class="divide-y divide-[#DCE5DF]">
                    @forelse(($annualSummary['details'] ?? []) as $year => $detail)
                        <div class="px-6 py-4 hover:bg-[#F9FAF7] transition-colors duration-150">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 bg-[#F9FAF7] rounded-lg mr-4 border border-[#DCE5DF]">
                                        <span class="text-sm font-bold text-[#0B5E2E]">{{ $year }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#083D1D]">{{ $detail['available'] }} hari tersisa</p>
                                        <p class="text-xs text-gray-500 mt-1">Terpakai: {{ $detail['used'] }} hari</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($detail['available'] > 0)
                                        <span class="text-xs px-2 py-1 rounded-full bg-green-50 text-green-700 border border-green-200">
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700 border border-gray-300">
                                            Habis
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Progress Bar -->
                            @php
                                $total = $detail['used'] + $detail['available'];
                                $usedPercentage = $total > 0 ? round(($detail['used'] / $total) * 100) : 0;
                            @endphp
                            <div class="mt-3">
                                <div class="flex justify-between text-xs text-[#083D1D] mb-1">
                                    <span>Penggunaan</span>
                                    <span>{{ $usedPercentage }}%</span>
                                </div>
                                <div class="w-full h-2 bg-[#F9FAF7] rounded-full overflow-hidden border border-[#DCE5DF]">
                                    <div class="h-full bg-gradient-to-r from-[#F2B705] to-[#0B5E2E] rounded-full transition-all duration-500" 
                                         style="width: {{ $usedPercentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <div class="mx-auto w-16 h-16 text-gray-300 mb-4">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-[#083D1D] font-medium">Belum ada data tahunan</p>
                            <p class="text-gray-400 text-sm mt-1">Data akan tersedia setelah pengajuan pertama</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pengajuan Terbaru -->
            <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] overflow-hidden">
                <div class="bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] px-6 py-4 border-b border-[#DCE5DF]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-[#083D1D] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-[#083D1D]">Pengajuan Terbaru</h3>
                        </div>
                        <a href="{{ route('leave-requests.index') }}" 
                           class="text-sm text-[#0B5E2E] hover:text-[#083D1D] hover:underline transition-colors">
                            Lihat semua
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-[#DCE5DF]">
                    @forelse($recentLeaves as $leave)
                        <div class="px-6 py-4 hover:bg-[#F9FAF7] transition-colors duration-150">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="font-medium text-[#083D1D] capitalize">{{ str_replace('_', ' ', $leave->leave_type) }}</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $leave->start_date?->format('d M Y') }} - {{ $leave->end_date?->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="text-xs px-3 py-1.5 rounded-full font-medium border
                                    @class([
                                        'bg-[#F9FAF7] text-amber-700 border-[#DCE5DF]' => $leave->status === 'pending',
                                        'bg-green-50 text-green-700 border-green-200' => $leave->status === 'approved',
                                        'bg-red-50 text-red-700 border-red-200' => $leave->status === 'rejected',
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
                            <p class="text-[#083D1D] font-medium">Belum ada pengajuan cuti</p>
                            <p class="text-gray-400 text-sm mt-1">Mulai dengan mengajukan cuti baru</p>
                            <a href="{{ route('leave-requests.create') }}"
                               class="inline-flex items-center mt-4 px-4 py-2 rounded-lg 
                                      bg-gradient-to-r from-[#F2B705] to-[#0B5E2E] text-white text-sm font-medium 
                                      hover:from-[#F2B705]/90 hover:to-[#0B5E2E]/90 hover:shadow-lg transition-all duration-200">
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
        <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] p-6">
            <h3 class="text-lg font-semibold text-[#083D1D] mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Akses Cepat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('leave-requests.create') }}" 
                   class="bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] border border-[#DCE5DF] rounded-xl p-4 hover:from-[#F2B705]/20 hover:to-[#0B5E2E]/20 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-2 bg-white rounded-lg shadow-sm mr-3 border border-[#DCE5DF]">
                            <svg class="w-5 h-5 text-[#0B5E2E] group-hover:text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-[#083D1D]">Ajukan Cuti</p>
                            <p class="text-sm text-gray-600">Buat pengajuan cuti baru</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('leave-requests.index') }}" 
                   class="bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] border border-[#DCE5DF] rounded-xl p-4 hover:from-[#F2B705]/20 hover:to-[#0B5E2E]/20 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-2 bg-white rounded-lg shadow-sm mr-3 border border-[#DCE5DF]">
                            <svg class="w-5 h-5 text-[#0B5E2E] group-hover:text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-[#083D1D]">Riwayat Cuti</p>
                            <p class="text-sm text-gray-600">Lihat semua pengajuan</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('profile.edit') }}" 
                   class="bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] border border-[#DCE5DF] rounded-xl p-4 hover:from-[#F2B705]/20 hover:to-[#0B5E2E]/20 hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-center">
                        <div class="p-2 bg-white rounded-lg shadow-sm mr-3 border border-[#DCE5DF]">
                            <svg class="w-5 h-5 text-[#0B5E2E] group-hover:text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-[#083D1D]">Profil Saya</p>
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