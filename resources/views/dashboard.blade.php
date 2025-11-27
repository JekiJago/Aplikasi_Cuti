@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-4">
                <p class="text-xs text-slate-500 mb-1">Kuota Tahunan</p>
                <p class="text-2xl font-semibold text-slate-900">
                    {{ $annualSummary['quota_per_year'] ?? 0 }} hari
                </p>
            </div>
            <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-4">
                <p class="text-xs text-slate-500 mb-1">Sisa Tahun Berjalan</p>
                <p class="text-2xl font-semibold text-emerald-600">
                    {{ $annualSummary['current_year_available'] ?? 0 }} hari
                </p>
            </div>
            <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-4">
                <p class="text-xs text-slate-500 mb-1">Total Dapat Dipakai</p>
                <p class="text-2xl font-semibold text-indigo-600">
                    {{ $annualSummary['total_available'] ?? 0 }} hari
                </p>
                <p class="text-[11px] text-slate-400 mt-1">
                    Termasuk carry-over hingga {{ optional($annualSummary['carry_over_expires_at'] ?? null)->format('d M Y') }}
                </p>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-slate-100">
            <div class="p-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Ringkasan Tahunan</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach(($annualSummary['details'] ?? []) as $year => $detail)
                    <div class="flex items-center justify-between px-4 py-3 text-sm">
                        <div>
                            <p class="font-medium text-slate-900">{{ $year }}</p>
                            <p class="text-xs text-slate-500">Terpakai: {{ $detail['used'] }} hari</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-indigo-600">{{ $detail['available'] }} hari tersisa</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-slate-100">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">Pengajuan Terbaru</h3>
                <a href="{{ route('leave-requests.index') }}" class="text-xs text-blue-600 hover:underline">
                    Lihat semua
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentLeaves as $leave)
                    <div class="px-4 py-3 text-sm flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-900 capitalize">{{ str_replace('_', ' ', $leave->leave_type) }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $leave->start_date?->format('d M Y') }} - {{ $leave->end_date?->format('d M Y') }}
                                ({{ $leave->days }} hari)
                            </p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full
                            @class([
                                'bg-amber-50 text-amber-600' => $leave->status === 'pending',
                                'bg-emerald-50 text-emerald-600' => $leave->status === 'approved',
                                'bg-rose-50 text-rose-600' => $leave->status === 'rejected',
                            ])">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </div>
                @empty
                    <p class="px-4 py-6 text-center text-sm text-slate-500">
                        Belum ada pengajuan cuti terbaru.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
