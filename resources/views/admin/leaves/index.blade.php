@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan Cuti')
@section('page-title', 'Verifikasi Pengajuan Cuti')

@section('content')
<div class="mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-1">Verifikasi Pengajuan Cuti</h1>
        <p class="text-slate-600">Kelola dan verifikasi semua pengajuan cuti karyawan</p>
    </div>
</div>

<form method="GET" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                @foreach(['pending' => '⏳ Pending', 'approved' => '✅ Disetujui', 'rejected' => '❌ Ditolak'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Departemen</label>
            <input type="text" name="department" value="{{ request('department') }}"
                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Cari departemen">
        </div>
        <div class="md:col-span-2 flex items-end gap-3">
            <button type="submit"
                    class="flex-1 px-6 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200 font-medium shadow-sm inline-flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Filter
            </button>
            @if(request('status') || request('department'))
                <a href="{{ route('admin.leaves.index') }}"
                   class="px-6 py-2.5 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 transition-colors duration-200 font-medium">
                    Reset
                </a>
            @endif
        </div>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Karyawan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($leaves as $leave)
                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-xs font-bold text-indigo-700">
                                        {{ strtoupper(substr($leave->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $leave->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $leave->user->employee_id }} • {{ $leave->user->department ?: '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-900">
                            <div class="text-sm font-medium">
                                {{ $leave->start_date->format('d M Y') }}
                            </div>
                            <div class="text-xs text-slate-500">
                                s/d {{ $leave->end_date->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold capitalize">
                                {{ $leave->leave_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = match($leave->status) {
                                    'approved' => ['icon' => '✅', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Disetujui'],
                                    'pending'  => ['icon' => '⏳', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                    'rejected' => ['icon' => '❌', 'bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                    default    => ['icon' => '❓', 'bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => ucfirst($leave->status)],
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} text-xs font-semibold">
                                {{ $statusConfig['icon'] }} {{ $statusConfig['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.leaves.show', $leave->id) }}"
                               class="inline-flex items-center px-4 py-2 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition-colors duration-200 font-medium text-xs">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Review
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-slate-500 font-medium">Belum ada pengajuan cuti</p>
                                <p class="text-slate-400 text-sm">Tidak ada pengajuan cuti yang sesuai dengan filter</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leaves->hasPages())
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            {{ $leaves->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection