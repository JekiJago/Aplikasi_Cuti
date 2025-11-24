@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-xl md:text-2xl font-semibold text-slate-900">
            Dashboard Admin
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Ringkasan data sistem manajemen cuti pegawai.
        </p>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="h-9 w-9 rounded-xl bg-indigo-50 flex items-center justify-center mb-3">
                <span class="text-indigo-600 text-lg">👤</span>
            </div>
            <p class="text-xs text-slate-500 mb-1">Total Pegawai</p>
            <p class="text-xl font-semibold text-slate-900">{{ $stats['employees'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-slate-400">Pegawai aktif</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="h-9 w-9 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
                <span class="text-amber-500 text-lg">⏳</span>
            </div>
            <p class="text-xs text-slate-500 mb-1">Menunggu Persetujuan</p>
            <p class="text-xl font-semibold text-slate-900">{{ $stats['pending'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-slate-400">Pengajuan pending</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="h-9 w-9 rounded-xl bg-emerald-50 flex items-center justify-center mb-3">
                <span class="text-emerald-500 text-lg">✅</span>
            </div>
            <p class="text-xs text-slate-500 mb-1">Cuti Disetujui</p>
            <p class="text-xl font-semibold text-slate-900">{{ $stats['approved'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-slate-400">Total disetujui</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="h-9 w-9 rounded-xl bg-rose-50 flex items-center justify-center mb-3">
                <span class="text-rose-500 text-lg">❌</span>
            </div>
            <p class="text-xs text-slate-500 mb-1">Cuti Ditolak</p>
            <p class="text-xl font-semibold text-slate-900">{{ $stats['rejected'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-slate-400">Total ditolak</p>
        </div>
    </div>

    {{-- GRAFIK + PANEL BAWAH, biarkan sama seperti versi yang sebelumnya sudah jalan --}}
    @include('dashboard.partials.admin-chart-and-lists')
</div>
@endsection
