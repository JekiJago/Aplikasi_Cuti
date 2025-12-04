@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 space-y-6">
    {{-- Header + info pegawai --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-semibold">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-lg font-semibold text-slate-900">{{ $employee->name }}</h1>
                <p class="text-sm text-slate-500">NIP: {{ $employee->employee_id }}</p>
                <p class="text-sm text-slate-500 flex items-center gap-1 mt-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Pegawai Aktif
                </p>
            </div>
        </div>
        <div>
            <x-link-button href="{{ route('admin.employees.index') }}" type="secondary">
                &larr; Kembali ke Data Pegawai
            </x-link-button>
        </div>
    </div>

    {{-- Statistik + Ringkasan Pengajuan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Statistik Cuti --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 lg:col-span-2">
            <p class="text-sm font-semibold text-slate-900 mb-3">Statistik Cuti</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <div class="rounded-xl bg-indigo-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Jatah Tahunan</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $quota }} hari</p>
                </div>
                <div class="rounded-xl bg-rose-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Cuti Terpakai</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $used }} hari</p>
                </div>
                <div class="rounded-xl bg-emerald-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Sisa Cuti Aktif</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $remaining }} hari</p>
                </div>
                <div class="rounded-xl bg-amber-50 px-4 py-3">
                    <p class="text-xs text-slate-500">Cuti Akan Hangus</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $expiring }} hari</p>
                    @if($expiring > 0 && ($annualSummary['carry_over_expires_at'] ?? null))
                        <p class="text-[11px] text-slate-600 mt-0.5">Sampai {{ $annualSummary['carry_over_expires_at']->translatedFormat('d F Y') }}</p>
                    @endif
                </div>
            </div>

            @php
                $percent = $quota > 0 ? round(($used / $quota) * 100) : 0;
            @endphp
            <div class="mt-2">
                <p class="text-xs text-slate-500 mb-1">Penggunaan Cuti</p>
                <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width: {{ min(100, $percent) }}%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-1 text-right">{{ $percent }}%</p>
            </div>
        </div>

        {{-- Ringkasan Pengajuan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-sm font-semibold text-slate-900 mb-3">Ringkasan Pengajuan</p>

            <div class="space-y-2 text-sm">
                <div class="flex items-center justify-between rounded-xl bg-amber-50 px-4 py-2">
                    <span class="text-slate-800">Pending</span>
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-white text-xs font-semibold text-amber-600">
                        {{ $summary['pending'] }}
                    </span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-emerald-50 px-4 py-2">
                    <span class="text-slate-800">Disetujui</span>
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-white text-xs font-semibold text-emerald-600">
                        {{ $summary['approved'] }}
                    </span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-rose-50 px-4 py-2">
                    <span class="text-slate-800">Ditolak</span>
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-white text-xs font-semibold text-rose-600">
                        {{ $summary['rejected'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Cuti Lengkap --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <p class="text-sm font-semibold text-slate-900 mb-3 flex items-center gap-2">
            <span>⏱</span> Riwayat Cuti Lengkap
        </p>

        <div class="space-y-3">
            @forelse($leaves as $leave)
                @php
                    $statusLabel = [
                        'pending'  => ['bg' => 'bg-amber-50',  'text' => 'text-amber-700',  'label' => 'Pending'],
                        'approved' => ['bg' => 'bg-emerald-50','text' => 'text-emerald-700','label' => 'Disetujui'],
                        'rejected' => ['bg' => 'bg-rose-50',   'text' => 'text-rose-700',   'label' => 'Ditolak'],
                    ][$leave->status] ?? ['bg' => 'bg-slate-50','text' => 'text-slate-700','label' => ucfirst($leave->status)];
                @endphp
                <div class="rounded-2xl border border-slate-100 px-4 py-3">
                    <div class="flex items-center justify-between mb-1">
                        <div>
                            <p class="text-sm font-medium text-slate-900">
                                {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ $leave->reason }}
                            </p>
                        </div>
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $statusLabel['bg'] }} {{ $statusLabel['text'] }}">
                            {{ $statusLabel['label'] }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs text-slate-500 mt-2">
                        <div>
                            <p class="uppercase tracking-wide">Tanggal</p>
                            <p class="text-slate-900 text-sm">
                                {{ $leave->start_date->format('d/m/Y') }} - {{ $leave->end_date->format('d/m/Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="uppercase tracking-wide">Durasi</p>
                            <p class="text-slate-900 text-sm">{{ $leave->days }} hari</p>
                        </div>
                        <div>
                            <p class="uppercase tracking-wide">Diajukan</p>
                            <p class="text-slate-900 text-sm">{{ $leave->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">
                    Belum ada pengajuan cuti untuk pegawai ini.
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
