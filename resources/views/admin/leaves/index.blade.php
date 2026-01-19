@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan Cuti')
@section('page-title', 'Verifikasi Pengajuan Cuti')

@section('content')
@php
    // Definisi warna resmi Kejaksaan
    $primaryColor = '#F2B705';      // Kuning Emas
    $secondaryColor = '#0B5E2E';    // Hijau Kejaksaan
    $darkColor = '#083D1D';         // Hijau tua
    $backgroundColor = '#F9FAF7';   // Putih / Abu muda
    $borderColor = '#DCE5DF';       // Abu kehijauan
@endphp

<div class="mb-8">
    <div>
        <h1 class="text-3xl font-bold text-[#083D1D] mb-1">Verifikasi Pengajuan Cuti</h1>
        <p class="text-[#083D1D]/70">Kelola dan verifikasi semua pengajuan cuti karyawan</p>
    </div>
</div>

<!-- FILTER FORM YANG DIUBAH: GANTI DEPARTEMEN JADI NAMA/NIP -->
<form method="GET" class="bg-[#F9FAF7] rounded-xl shadow-sm border border-[#DCE5DF] p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold text-[#083D1D] mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2.5 border border-[#DCE5DF] rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#F2B705] focus:border-transparent bg-white">
                <option value="">Semua Status</option>
                @foreach(['pending' => '⏳ Pending', 'approved' => '✅ Disetujui', 'rejected' => '❌ Ditolak'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- GANTI INI: dari departemen jadi nama/NIP -->
        <div>
            <label class="block text-sm font-semibold text-[#083D1D] mb-2">Cari Pegawai (Nama/NIP)</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full px-4 py-2.5 border border-[#DCE5DF] rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#F2B705] focus:border-transparent bg-white"
                   placeholder="Nama atau NIP pegawai">
        </div>
        <div class="md:col-span-2 flex items-end gap-3">
            <button type="submit"
                    class="flex-1 px-6 py-2.5 rounded-lg bg-[#0B5E2E] text-white hover:bg-[#083D1D] transition-colors duration-200 font-medium shadow-sm inline-flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Filter
            </button>
            @if(request('status') || request('search'))
                <a href="{{ route('admin.leaves.index') }}"
                   class="px-6 py-2.5 rounded-lg bg-[#DCE5DF] text-[#083D1D] hover:bg-[#DCE5DF]/80 transition-colors duration-200 font-medium">
                    Reset
                </a>
            @endif
        </div>
    </div>
</form>

<!-- TABEL TETAP SAMA PERSIS -->
<div class="bg-[#F9FAF7] rounded-xl shadow-sm border border-[#DCE5DF] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF]/50 border-b border-[#DCE5DF]">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#083D1D] uppercase tracking-wider">Karyawan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#083D1D] uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#083D1D] uppercase tracking-wider">Alasan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#083D1D] uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-[#083D1D] uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#DCE5DF]">
                @forelse($leaves as $leave)
                    <tr class="hover:bg-[#F9FAF7] transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-9 h-9 rounded-full bg-[#0B5E2E]/10 flex items-center justify-center border border-[#0B5E2E]/20">
                                    <span class="text-xs font-bold text-[#0B5E2E]">
                                        {{ strtoupper(substr($leave->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-[#083D1D]">{{ $leave->user->name }}</p>
                                    <p class="text-xs text-[#083D1D]/70">{{ $leave->user->employee_id }} • {{ $leave->user->department ?: '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[#083D1D]">
                            <div class="text-sm font-medium">
                                {{ $leave->start_date->format('d M Y') }}
                            </div>
                            <div class="text-xs text-[#083D1D]/70">
                                s/d {{ $leave->end_date->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#F2B705]/10 text-[#083D1D] text-xs font-semibold border border-[#F2B705]/20">
                                    @if($leave->alasan)
                                        {{ Str::limit($leave->alasan, 30) }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = match($leave->status) {
                                    'approved' => ['icon' => '✅', 'bg' => 'bg-[#0B5E2E]/10', 'text' => 'text-[#083D1D]', 'label' => 'Disetujui'],
                                    'pending'  => ['icon' => '⏳', 'bg' => 'bg-[#F2B705]/10', 'text' => 'text-[#083D1D]', 'label' => 'Pending'],
                                    'rejected' => ['icon' => '❌', 'bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                    default    => ['icon' => '❓', 'bg' => 'bg-[#DCE5DF]', 'text' => 'text-[#083D1D]', 'label' => ucfirst($leave->status)],
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} text-xs font-semibold border border-[#DCE5DF]">
                                {{ $statusConfig['icon'] }} {{ $statusConfig['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.leaves.show', $leave->id) }}"
                               class="inline-flex items-center px-4 py-2 rounded-lg border border-[#0B5E2E]/30 text-[#083D1D] hover:bg-[#0B5E2E]/5 transition-colors duration-200 font-medium text-xs">
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
                                <svg class="w-12 h-12 text-[#DCE5DF] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-[#083D1D]/70 font-medium">Belum ada pengajuan cuti</p>
                                <p class="text-[#083D1D]/50 text-sm">Tidak ada pengajuan cuti yang sesuai dengan filter</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leaves->hasPages())
        <div class="bg-[#F9FAF7] px-6 py-4 border-t border-[#DCE5DF]">
            {{ $leaves->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection