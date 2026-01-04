<!-- Stats Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Leave Statistics -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 p-6 h-full">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Statistik Cuti</h2>
                <div class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">
                    Tahun {{ $currentYear }}
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                <!-- Cuti Terpakai -->
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 border border-amber-200">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-amber-900">Cuti Terpakai</p>
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-amber-800">{{ $used }} hari</p>
                </div>

                <!-- Sisa Cuti Aktif -->
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-emerald-900">Sisa Cuti Aktif</p>
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-emerald-800">{{ $remaining }} hari</p>
                </div>
            </div>

            <!-- Usage Progress -->
            @php
                $percent = $quota > 0 ? round(($used / $quota) * 100) : 0;
                $progressColor = $percent <= 50 ? 'bg-emerald-500' : 
                                ($percent <= 75 ? 'bg-amber-500' : 'bg-red-500');
            @endphp
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-700">Tingkat Penggunaan Cuti</p>
                    <p class="text-sm font-semibold {{ $percent <= 50 ? 'text-emerald-600' : ($percent <= 75 ? 'text-amber-600' : 'text-red-600') }}">
                        {{ $percent }}%
                    </p>
                </div>
                <div class="w-full h-3 rounded-full bg-gray-200 overflow-hidden">
                    <div class="h-full rounded-full {{ $progressColor }} transition-all duration-500 animate-progress" 
                         style="width: {{ min(100, $percent) }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>0%</span>
                    <span>50%</span>
                    <span>100%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div>
        <div class="bg-white rounded-xl border border-gray-200 p-6 h-full">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Ringkasan Status</h2>
            
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