@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-6">                
                    <!--Employee Info -->
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
                        Edit Data
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

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Leave Statistics -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 h-full">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Statistik Cuti</h2>
                        <div class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">
                            Tahun {{ now()->year }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <!-- Jatah Tahunan -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-blue-900">Jatah Tahunan</p>
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-blue-800">{{ $quota }} hari</p>
                        </div>

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

                        <!-- Cuti Akan Hangus -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-red-900">Cuti Akan Hangus</p>
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-red-800">{{ $expiring ?? 0 }} hari</p>
                            @if(isset($expiring) && $expiring > 0 && isset($annualSummary['carry_over_expires_at']))
                                <p class="text-xs text-red-700 mt-2">
                                    Berakhir {{ $annualSummary['carry_over_expires_at']->translatedFormat('d F Y') }}
                                </p>
                            @endif
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
                            <div class="h-full rounded-full {{ $progressColor }} transition-all duration-500" 
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
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 h-full">
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

        <!-- Leave History -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-gray-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Riwayat Cuti</h2>
                            <p class="text-sm text-gray-600">Daftar pengajuan cuti {{ $employee->name }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">
                        {{ $leaves->count() }} pengajuan
                    </span>
                </div>
            </div>

            <div class="p-6">
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
@endsection

<style>
    .animate-progress {
        animation: progress 1s ease-out;
    }
    
    @keyframes progress {
        from { width: 0; }
        to { width: var(--progress-width); }
    }
</style>