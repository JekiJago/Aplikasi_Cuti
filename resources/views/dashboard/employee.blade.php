@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $user = auth()->user();
    $jatahTahunan   = $user->annual_leave_quota ?? 0;
    $sisaCuti       = $stats['remaining'] ?? 0;
    $cutiTerpakai   = $stats['used'] ?? 0;
    $cutiAkanHangus = $stats['expiring'] ?? 0; // sementara 0, nanti bisa dihitung
@endphp

{{-- HERO BIRU --}}
<section class="mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-2xl px-6 py-6 md:py-7 shadow-sm">
        <p class="text-sm font-medium mb-1">Selamat Datang,</p>
        <p class="text-2xl md:text-3xl font-semibold mb-1">
            {{ $user->name }}!
        </p>
        @if($user->employee_id)
            <p class="text-xs md:text-sm text-blue-100 mb-3">
                NIP: {{ $user->employee_id }}
            </p>
        @endif
        <p class="text-xs md:text-sm text-blue-50">
            Kelola pengajuan cuti Anda dengan mudah melalui Portal Pegawai.
        </p>
    </div>
</section>

{{-- KARTU STATISTIK --}}
<section class="mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Jatah Tahunan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <div class="h-9 w-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <span class="text-indigo-600 text-lg">📅</span>
                </div>
            </div>
            <p class="text-xs text-slate-500 mb-1">Jatah Cuti Tahunan</p>
            <p class="text-xl font-semibold text-slate-900">{{ $jatahTahunan }} hari</p>
            <p class="mt-1 text-xs text-slate-400">Total jatah per tahun</p>
        </div>

        {{-- Sisa Cuti Aktif --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <div class="h-9 w-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <span class="text-emerald-600 text-lg">✅</span>
                </div>
            </div>
            <p class="text-xs text-slate-500 mb-1">Sisa Cuti Aktif</p>
            <p class="text-xl font-semibold text-slate-900">{{ $sisaCuti }} hari</p>
            <p class="mt-1 text-xs text-slate-400">Tahun berjalan</p>
        </div>

        {{-- Cuti Terpakai --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <div class="h-9 w-9 rounded-xl bg-violet-50 flex items-center justify-center">
                    <span class="text-violet-600 text-lg">⏱</span>
                </div>
            </div>
            <p class="text-xs text-slate-500 mb-1">Cuti Terpakai</p>
            <p class="text-xl font-semibold text-slate-900">{{ $cutiTerpakai }} hari</p>
            <p class="mt-1 text-xs text-slate-400">Sudah digunakan</p>
        </div>

        {{-- Cuti Akan Hangus --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <div class="h-9 w-9 rounded-xl bg-amber-50 flex items-center justify-center">
                    <span class="text-amber-600 text-lg">⚠️</span>
                </div>
            </div>
            <p class="text-xs text-slate-500 mb-1">Cuti Akan Hangus</p>
            <p class="text-xl font-semibold text-slate-900">{{ $cutiAkanHangus }} hari</p>
            <p class="mt-1 text-xs text-slate-400">Periode berikutnya</p>
        </div>
    </div>
</section>

{{-- RIWAYAT PENGAJUAN TERAKHIR --}}
<section class="space-y-3">
    <div class="flex items-center justify-between">
        <h2 class="text-sm font-semibold text-slate-900">
            Riwayat Pengajuan Cuti Terakhir
        </h2>
        <a href="{{ route('leave-requests.index') }}"
           class="text-xs text-blue-600 hover:text-blue-700 hover:underline">
            Lihat semua
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 divide-y">
        @forelse($userLeaves as $leave)
            @php
                $statusColor = match($leave->status) {
                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                    'pending'  => 'bg-amber-50 text-amber-700 border-amber-100',
                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-100',
                    default    => 'bg-slate-50 text-slate-700 border-slate-100',
                };
            @endphp
            <div class="px-5 py-4 flex items-start justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-1">
                        <p class="text-sm font-medium text-slate-900">
                            {{ ucfirst(str_replace('_',' ', $leave->leave_type)) }}
                        </p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full border text-[10px] font-medium {{ $statusColor }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">
                        {{ $leave->start_date->format('d F Y') }}
                        – {{ $leave->end_date->format('d F Y') }}
                        • {{ $leave->days }} hari
                    </p>
                    <p class="mt-1 text-xs text-slate-400 line-clamp-1">
                        {{ $leave->reason }}
                    </p>
                </div>

                <div class="text-right text-xs text-slate-400 ml-4">
                    <p>{{ $leave->created_at->format('d M') }}</p>
                    <a href="{{ route('leave-requests.show', $leave->id) }}"
                       class="mt-2 inline-flex text-[11px] text-blue-600 hover:text-blue-700 hover:underline">
                        Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="px-5 py-6 text-center text-xs text-slate-500">
                Belum ada pengajuan cuti. Klik
                <a href="{{ route('leave-requests.create') }}" class="text-blue-600 hover:underline">
                    Ajukan Cuti
                </a>
                untuk membuat pengajuan pertama Anda.
            </div>
        @endforelse
    </div>
</section>
@endsection
