@extends('layouts.app')

@section('title', 'Pengajuan Cuti')
@section('page-title', 'Pengajuan Cuti Saya')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center text-[#083D1D] hover:text-[#0B5E2E] mr-3 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold text-[#083D1D]">Riwayat Pengajuan Cuti</h1>
                    </div>
                    <p class="text-gray-600 ml-8">
                        Kelola dan lacak semua pengajuan cuti Anda
                    </p>
                </div>
                
                <a href="{{ route('leave-requests.create') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-lg 
                          bg-gradient-to-r from-[#F2B705] to-[#0B5E2E] text-white font-semibold 
                          hover:from-[#F2B705]/90 hover:to-[#0B5E2E]/90 hover:shadow-lg 
                          transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajukan Cuti Baru
                </a>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-[#F9FAF7] rounded-lg">
                        <svg class="w-5 h-5 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-[#0B5E2E]">Total</span>
                </div>
                <p class="text-2xl font-bold text-[#083D1D]">{{ $leaveRequests->total() }}</p>
                <p class="text-xs text-gray-500 mt-1">Pengajuan</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-[#F9FAF7] rounded-lg">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-amber-600">Menunggu</span>
                </div>
                <p class="text-2xl font-bold text-[#083D1D]">{{ $leaveRequests->where('status', 'pending')->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Pending</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-[#F9FAF7] rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-600">Disetujui</span>
                </div>
                <p class="text-2xl font-bold text-[#083D1D]">{{ $leaveRequests->where('status', 'approved')->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Approved</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-[#F9FAF7] rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-red-600">Ditolak</span>
                </div>
                <p class="text-2xl font-bold text-[#083D1D]">{{ $leaveRequests->where('status', 'rejected')->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Rejected</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] p-6 mb-8">
            <div class="flex items-center mb-4">
                <svg class="w-5 h-5 text-[#083D1D] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <h3 class="text-lg font-semibold text-[#083D1D]">Filter Pengajuan</h3>
            </div>
            
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#083D1D] mb-2">Status</label>
                    <select name="status" 
                            class="block w-full pl-3 pr-10 py-3 rounded-lg border border-[#DCE5DF] 
                                   bg-[#F9FAF7] focus:ring-2 focus:ring-[#F2B705] focus:border-[#0B5E2E] 
                                   focus:bg-white transition-colors text-[#083D1D]">
                        <option value="">Semua Status</option>
                        @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $label)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[#083D1D] mb-2">Dari Tanggal</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="date" name="from" value="{{ request('from') }}"
                               class="block w-full pl-10 pr-3 py-3 rounded-lg border border-[#DCE5DF] 
                                      bg-[#F9FAF7] focus:ring-2 focus:ring-[#F2B705] focus:border-[#0B5E2E] 
                                      focus:bg-white transition-colors text-[#083D1D]">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[#083D1D] mb-2">Sampai Tanggal</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="date" name="to" value="{{ request('to') }}"
                               class="block w-full pl-10 pr-3 py-3 rounded-lg border border-[#DCE5DF] 
                                      bg-[#F9FAF7] focus:ring-2 focus:ring-[#F2B705] focus:border-[#0B5E2E] 
                                      focus:bg-white transition-colors text-[#083D1D]">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[#083D1D] mb-2">Jenis Cuti</label>
                    <select name="leave_type" 
                            class="block w-full pl-3 pr-10 py-3 rounded-lg border border-[#DCE5DF] 
                                   bg-[#F9FAF7] focus:ring-2 focus:ring-[#F2B705] focus:border-[#0B5E2E] 
                                   focus:bg-white transition-colors text-[#083D1D]">
                        <option value="">Semua Jenis</option>
                        @foreach([
                            'tahunan'        => 'Cuti Tahunan',
                            'urusan_penting' => 'Urusan Penting',
                            'cuti_besar'     => 'Cuti Besar',
                            'cuti_non_aktif' => 'Non Aktif',
                            'cuti_bersalin'  => 'Bersalin',
                            'cuti_sakit'     => 'Sakit',
                        ] as $val => $label)
                            <option value="{{ $val }}" {{ request('leave_type') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <div class="flex space-x-3 w-full">
                        <button type="submit"
                                class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-lg 
                                       bg-gradient-to-r from-[#F2B705] to-[#0B5E2E] text-white font-semibold 
                                       hover:from-[#F2B705]/90 hover:to-[#0B5E2E]/90 hover:shadow-lg 
                                       transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('leave-requests.index') }}"
                           class="inline-flex items-center justify-center px-4 py-3 rounded-lg border border-[#DCE5DF] 
                                  text-[#083D1D] font-medium hover:bg-[#F9FAF7] hover:border-[#0B5E2E] 
                                  transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-[#DCE5DF] bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-[#083D1D] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-[#083D1D]">Daftar Pengajuan</h3>
                    </div>
                    <div class="text-sm text-[#083D1D]">
                        Menampilkan {{ $leaveRequests->firstItem() ?? 0 }}-{{ $leaveRequests->lastItem() ?? 0 }} dari {{ $leaveRequests->total() }}
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#F9FAF7]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#083D1D] uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Periode
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#083D1D] uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Jenis
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#083D1D] uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Durasi
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#083D1D] uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Status
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#083D1D] uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                    Aksi
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#DCE5DF]">
                        @forelse($leaveRequests as $leave)
                            <tr class="hover:bg-[#F9FAF7] transition-colors duration-150">
                                <!-- Date Range -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-[#083D1D]">
                                        {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Diajukan: {{ $leave->created_at->format('d M Y') }}
                                    </div>
                                </td>

                                <!-- Leave Type -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-[#083D1D] capitalize">
                                        {{ str_replace('_', ' ', $leave->leave_type) }}
                                    </div>
                                    @if($leave->reason)
                                        <div class="text-xs text-gray-500 truncate max-w-xs mt-1">
                                            {{ \Illuminate\Support\Str::limit($leave->reason, 50) }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Duration -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="px-3 py-1 rounded-full bg-[#F9FAF7] text-[#0B5E2E] text-sm font-medium">
                                            {{ $leave->days }} hari
                                        </span>
                                        @if($leave->attachment)
                                            <svg class="w-4 h-4 ml-2 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = match($leave->status) {
                                            'approved' => [
                                                'bg' => 'bg-green-50', 
                                                'text' => 'text-green-800', 
                                                'border' => 'border border-green-200',
                                                'icon' => '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                                            ],
                                            'pending'  => [
                                                'bg' => 'bg-[#F9FAF7]', 
                                                'text' => 'text-amber-800', 
                                                'border' => 'border border-[#DCE5DF]',
                                                'icon' => '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                                            ],
                                            'rejected' => [
                                                'bg' => 'bg-red-50', 
                                                'text' => 'text-red-800', 
                                                'border' => 'border border-red-200',
                                                'icon' => '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                                            ],
                                            default    => [
                                                'bg' => 'bg-[#F9FAF7]', 
                                                'text' => 'text-[#083D1D]', 
                                                'border' => 'border border-[#DCE5DF]',
                                                'icon' => ''
                                            ],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                        {!! $statusConfig['icon'] !!}
                                        {{ match($leave->status) {
                                            'approved' => 'Disetujui',
                                            'pending'  => 'Menunggu',
                                            'rejected' => 'Ditolak',
                                            default    => ucfirst($leave->status),
                                        } }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('leave-requests.show', $leave->id) }}"
                                           class="inline-flex items-center px-4 py-2 rounded-lg border border-[#DCE5DF] 
                                                  text-[#083D1D] font-medium hover:bg-[#F9FAF7] hover:border-[#0B5E2E] 
                                                  transition-colors duration-200 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>

                                        @if($leave->status === 'pending')
                                            <form action="{{ route('leave-requests.destroy', $leave->id) }}" 
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 rounded-lg border border-red-300 
                                                               text-red-600 font-medium hover:bg-red-50 hover:border-red-400 
                                                               transition-colors duration-200 text-sm">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="mx-auto w-24 h-24 text-gray-300 mb-4">
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
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($leaveRequests->hasPages())
                <div class="px-6 py-4 border-t border-[#DCE5DF]">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-[#083D1D]">
                            Menampilkan 
                            <span class="font-medium">{{ $leaveRequests->firstItem() }}</span>
                            sampai 
                            <span class="font-medium">{{ $leaveRequests->lastItem() }}</span>
                            dari 
                            <span class="font-medium">{{ $leaveRequests->total() }}</span>
                            pengajuan
                        </div>
                        <div class="flex space-x-2">
                            {{ $leaveRequests->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Custom pagination styling */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination li {
        margin: 0 2px;
    }
    
    .pagination a, .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .pagination a {
        color: #083D1D;
        background-color: white;
        border: 1px solid #DCE5DF;
    }
    
    .pagination a:hover {
        background-color: #F9FAF7;
        border-color: #0B5E2E;
    }
    
    .pagination .active span {
        background-color: #0B5E2E;
        color: white;
        border-color: #0B5E2E;
    }
    
    .pagination .disabled span {
        color: #9ca3af;
        background-color: #F9FAF7;
        border-color: #DCE5DF;
        cursor: not-allowed;
    }
</style>
@endsection