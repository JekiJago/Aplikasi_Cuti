@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-4 sm:py-6 md:py-8 px-3 sm:px-4 md:px-6">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center mb-2">
                        <div class="p-2 bg-gradient-to-r from-blue-100 to-teal-100 rounded-xl mr-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800">
                                Dashboard Admin
                            </h1>
                            <p class="text-sm sm:text-base text-gray-600 mt-1">
                                Ringkasan data sistem manajemen cuti pegawai.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- WAKTU REALTIME DISPLAY -->
                <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl sm:rounded-2xl shadow-lg border border-blue-200 p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs font-medium text-gray-700">Waktu Sistem</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-green-500 rounded-full mr-1 animate-pulse"></div>
                            <span class="text-xs font-medium text-green-600">Live</span>
                        </div>
                    </div>
                    <div id="realtime-date" class="text-xs sm:text-sm font-medium text-gray-600 mb-1"></div>
                    <div id="realtime-clock" class="text-xl sm:text-2xl md:text-3xl font-bold text-blue-700 tracking-wider"></div>
                    <div class="flex items-center justify-between mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-blue-100">
                        <span class="text-xs text-gray-500">WIB • 24 Jam</span>
                        <div class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-medium">
                            Real-time
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTICS CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">

            <!-- Total Pegawai Card -->
            <div class="stat-card bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="p-2 sm:p-3 bg-blue-50 rounded-lg sm:rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        @if(($stats['employee_growth'] ?? 0) > 0)
                        <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                            +{{ $stats['employee_growth'] ?? 0 }}%
                        </span>
                        @else
                        <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                            {{ $stats['employee_growth'] ?? 0 }}%
                        </span>
                        @endif
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1 sm:mb-2">Total Pegawai</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">{{ $stats['employees'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 flex items-center">
                    @if(($stats['employee_growth'] ?? 0) > 0)
                    <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Pertumbuhan {{ $stats['employee_growth'] ?? 0 }}%
                    @else
                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Data pegawai
                    @endif
                </p>
            </div>

            <!-- Menunggu Persetujuan Card -->
            <div class="stat-card bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="p-2 sm:p-3 bg-amber-50 rounded-lg sm:rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        @if(($stats['pending'] ?? 0) > 0)
                        <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">
                            Perlu Tindakan
                        </span>
                        @endif
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1 sm:mb-2">Menunggu Persetujuan</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">{{ $stats['pending'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    {{ $stats['pending'] ?? 0 }} pengajuan
                </p>
            </div>

            <!-- Cuti Disetujui Card -->
            <div class="stat-card bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="p-2 sm:p-3 bg-emerald-50 rounded-lg sm:rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        @if(($stats['approval_rate'] ?? 0) > 0)
                        <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                            {{ $stats['approval_rate'] ?? 0 }}%
                        </span>
                        @endif
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1 sm:mb-2">Cuti Disetujui</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">{{ $stats['approved'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    {{ $stats['approved'] ?? 0 }} total
                </p>
            </div>

            <!-- Cuti Ditolak Card -->
            <div class="stat-card bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="p-2 sm:p-3 bg-red-50 rounded-lg sm:rounded-xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        @if(($stats['rejection_rate'] ?? 0) > 0)
                        <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                            {{ $stats['rejection_rate'] ?? 0 }}%
                        </span>
                        @endif
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1 sm:mb-2">Cuti Ditolak</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">{{ $stats['rejected'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    {{ $stats['rejected'] ?? 0 }} total
                </p>
                <div class="pt-3 border-t border-gray-100 mt-3">
                    <p id="last-update-time" class="text-xs text-gray-500">
                        Update terakhir: <span class="font-medium text-blue-600">{{ now()->format('H:i') }}</span>
                    </p>
                </div>
            </div>

        </div>

        <!-- Quick Stats Bar -->
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                    <div class="grid grid-cols-3 gap-4 sm:gap-6 w-full sm:w-auto">
                        <div class="text-center">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Pengajuan</p>
                            <p class="text-lg sm:text-xl font-bold text-gray-900">
                                {{ ($stats['pending'] ?? 0) + ($stats['approved'] ?? 0) + ($stats['rejected'] ?? 0) }}
                            </p>
                        </div>
                        <div class="text-center border-x border-gray-200 px-4">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Rata-rata Hari Cuti</p>
                            <p class="text-lg sm:text-xl font-bold text-gray-900">
                                {{ $stats['avg_leave_days'] ?? 0 }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Aktivitas Bulan Ini</p>
                            <p class="text-lg sm:text-xl font-bold text-gray-900">
                                {{ $stats['monthly_activity'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto px-4 py-2 bg-blue-50 rounded-lg border border-blue-100 text-center sm:text-right">
                        <p id="current-update-time" class="text-xs sm:text-sm text-blue-700 font-medium">
                            Update terakhir: {{ now()->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRAFIK DAN LISTS --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6 sm:mb-8">
            @if(file_exists(resource_path('views/dashboard/partials/admin-chart-and-lists.blade.php')))
                @include('dashboard.partials.admin-chart-and-lists')
            @else
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pengajuan Cuti per Bulan</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Jumlah pengajuan cuti yang disetujui, ditolak, dan masih pending sepanjang tahun {{ now()->year }}
                    </p>
                    
                    <!-- Chart container -->
                    <div class="h-64">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                    
                    <!-- Chart Script -->
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('monthlyChart').getContext('2d');
                        const monthlyStats = @json($monthlyStats);
                        
                        const labels = monthlyStats.map(item => item.label);
                        const approved = monthlyStats.map(item => item.approved);
                        const rejected = monthlyStats.map(item => item.rejected);
                        const pending = monthlyStats.map(item => item.pending);
                        
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        label: 'Disetujui',
                                        data: approved,
                                        backgroundColor: '#10b981',
                                        borderColor: '#059669',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Ditolak',
                                        data: rejected,
                                        backgroundColor: '#ef4444',
                                        borderColor: '#dc2626',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Pending',
                                        data: pending,
                                        backgroundColor: '#f59e0b',
                                        borderColor: '#d97706',
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    }
                                }
                            }
                        });
                    });
                    </script>
                </div>
            @endif
        </div>

        {{-- BOTTOM INFO SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

            {{-- System Status --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Status Sistem
                </h3>

                <div class="space-y-2 sm:space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Server Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-emerald-500 rounded-full mr-1"></span>
                            Online
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Database</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-emerald-500 rounded-full mr-1"></span>
                            Connected
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Total Pengajuan</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['total_requests'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Akses Cepat
                </h3>

                <div class="space-y-2">
                    <a href="{{ route('admin.employees.index') }}" 
                       class="flex items-center justify-between p-2 sm:p-3 rounded-lg hover:bg-blue-50 transition-colors group border border-transparent hover:border-blue-100">
                        <div class="flex items-center">
                            <div class="p-1.5 sm:p-2 bg-blue-100 rounded-lg mr-2 sm:mr-3">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700 group-hover:text-blue-700">Kelola Pegawai</span>
                        </div>
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('admin.leaves.index') }}" 
                       class="flex items-center justify-between p-2 sm:p-3 rounded-lg hover:bg-amber-50 transition-colors group border border-transparent hover:border-amber-100">
                        <div class="flex items-center">
                            <div class="p-1.5 sm:p-2 bg-amber-100 rounded-lg mr-2 sm:mr-3">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700 group-hover:text-amber-700">Pengajuan Cuti</span>
                        </div>
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 group-hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Recent Leaves --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pengajuan Terbaru
                </h3>

                <div class="space-y-2 sm:space-y-3">
                    @forelse($recentLeaves as $leave)
                    <div class="flex items-start">
                        <div class="p-1 {{ $leave->status == 'approved' ? 'bg-emerald-100' : ($leave->status == 'rejected' ? 'bg-red-100' : 'bg-amber-100') }} rounded-lg mr-2 sm:mr-3 mt-0.5">
                            @if($leave->status == 'approved')
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            @elseif($leave->status == 'rejected')
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            @else
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 font-medium">{{ $leave->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full 
                                    {{ $leave->status == 'approved' ? 'bg-emerald-100 text-emerald-800' : 
                                       ($leave->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                                       'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Belum ada pengajuan cuti</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT WAKTU REALTIME -->
<script>
// Format tanggal dan waktu Indonesia
const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 
               'Agustus', 'September', 'Oktober', 'November', 'Desember'];

function updateRealtimeClock() {
    const now = new Date();
    
    // Ambil komponen waktu
    const day = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    
    // Format 24 jam tanpa detik
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    // Format tanggal: "Senin, 25 Desember 2023"
    const dateString = `${day}, ${date} ${month} ${year}`;
    
    // Format jam tanpa detik: "14 : 30"
    const timeString = `${hours} : ${minutes}`;
    
    // Update elemen di halaman
    const dateElement = document.getElementById('realtime-date');
    const clockElement = document.getElementById('realtime-clock');
    const lastUpdateElement = document.getElementById('last-update-time');
    const currentUpdateElement = document.getElementById('current-update-time');
    
    if (dateElement) dateElement.textContent = dateString;
    if (clockElement) clockElement.textContent = timeString;
    
    // Update timestamp di card "Cuti Ditolak"
    if (lastUpdateElement) {
        lastUpdateElement.innerHTML = `Update terakhir: <span class="font-medium text-blue-600">${hours}:${minutes}</span>`;
    }
    
    // Update timestamp di quick stats bar
    if (currentUpdateElement) {
        currentUpdateElement.innerHTML = `Update terakhir: ${hours}:${minutes}`;
    }
}

// Jalankan fungsi pertama kali
updateRealtimeClock();

// Update setiap menit (60000 ms)
setInterval(updateRealtimeClock, 60000);

// Update saat tab/window menjadi aktif kembali
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        updateRealtimeClock();
    }
});
</script>

<style>
    /* Responsive adjustments */
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    /* Ensure tables are responsive */
    table {
        width: 100%;
    }
    
    .responsive-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Responsive text sizes */
    @media (max-width: 640px) {
        .text-2xl {
            font-size: 1.5rem;
        }
        .text-3xl {
            font-size: 1.75rem;
        }
    }
    
    /* Chart container responsive */
    .chart-container {
        position: relative;
        width: 100% !important;
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        .grid-cols-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .grid-cols-4 {
            grid-template-columns: 1fr;
        }
        
        .grid-cols-3 {
            grid-template-columns: 1fr;
        }
    }
    
    /* Better spacing on small screens */
    @media (max-width: 480px) {
        .px-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
    }
    
    /* Smooth transitions */
    * {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection