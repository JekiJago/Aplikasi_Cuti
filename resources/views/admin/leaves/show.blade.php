@extends('layouts.app')

@section('title', 'Review Pengajuan Cuti')
@section('page-title', 'Review Pengajuan Cuti')

@section('content')
@php
    // Definisi warna resmi Kejaksaan
    $primaryColor = '#F2B705';      // Kuning Emas
    $secondaryColor = '#0B5E2E';    // Hijau Kejaksaan
    $darkColor = '#083D1D';         // Hijau tua
    $backgroundColor = '#F9FAF7';   // Putih / Abu muda
    $borderColor = '#DCE5DF';       // Abu kehijauan
@endphp

<div class="max-w-4xl mx-auto">
    {{-- HEADER CARD --}}
    <div class="bg-[#F9FAF7] rounded-xl shadow-sm p-6 mb-6 border border-[#DCE5DF]">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#083D1D]">Portal Pegawai</h1>
                <p class="text-[#083D1D]/70">Sistem Manajemen Cuti</p>
            </div>
            <a href="{{ route('admin.leaves.index') }}" 
               class="px-4 py-2 text-sm bg-[#DCE5DF] hover:bg-[#DCE5DF]/80 text-[#083D1D] rounded-lg transition-colors">
                ← Kembali ke Daftar
            </a>
        </div>

        {{-- INFO KARYAWAN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="space-y-2">
                <p class="text-xs text-[#083D1D]/70">Karyawan</p>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-[#0B5E2E]/10 flex items-center justify-center border border-[#0B5E2E]/20">
                        <span class="text-lg font-bold text-[#0B5E2E]">
                            {{ strtoupper(substr($leave->user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-bold text-lg text-[#083D1D]">{{ $leave->user->name }}</p>
                        <p class="text-sm text-[#083D1D]/70">{{ $leave->user->employee_id }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <p class="font-bold text-lg text-[#083D1D]">{{ ucfirst($leave->leave_type) }}</p>
                
                <p class="text-xs text-[#083D1D]/70 mt-4">Status</p>
                @php
                    $statusConfig = match($leave->status) {
                        'approved' => ['class' => 'bg-[#0B5E2E]/10 text-[#083D1D] border-[#0B5E2E]/30', 'icon' => '✅', 'label' => 'DISETUJUI'],
                        'pending'  => ['class' => 'bg-[#F2B705]/10 text-[#083D1D] border-[#F2B705]/30', 'icon' => '⏳', 'label' => 'MENUNGGU'],
                        'rejected' => ['class' => 'bg-red-100 text-red-700 border-red-300', 'icon' => '❌', 'label' => 'DITOLAK'],
                        'cancelled' => ['class' => 'bg-[#DCE5DF] text-[#083D1D] border-[#DCE5DF]', 'icon' => '🚫', 'label' => 'DIBATALKAN'],
                        default    => ['class' => 'bg-[#DCE5DF] text-[#083D1D] border-[#DCE5DF]', 'icon' => '❓', 'label' => strtoupper($leave->status)],
                    };
                @endphp
                <div class="inline-flex items-center px-4 py-2 rounded-lg border {{ $statusConfig['class'] }}">
                    <span class="mr-2">{{ $statusConfig['icon'] }}</span>
                    <span class="font-bold text-lg">{{ $statusConfig['label'] }}</span>
                </div>
            </div>
        </div>

        <hr class="my-8 border-[#DCE5DF]">

        {{-- DETAIL CUTI --}}
        <div class="mb-8">
            <h2 class="text-lg font-bold text-[#083D1D] mb-4">Detail Cuti</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-[#083D1D]/70 mb-1">Diajukan pada</p>
                        <p class="text-lg font-bold text-[#083D1D]">{{ $leave->created_at->format('d M Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-[#083D1D]/70 mb-1">Durasi Cuti</p>
                        <p class="text-lg font-bold text-[#083D1D]">{{ $leave->days ?? 0 }} hari</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-[#083D1D]/70 mb-1">Alasan Cuti</p>
                        <div class="p-3 bg-[#F9FAF7] rounded-lg border border-[#DCE5DF] max-h-60 overflow-y-auto">
                            <p class="text-[#083D1D] whitespace-pre-wrap break-words">
                                {{ $leave->alasan ?: '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-[#0B5E2E]/5 p-4 rounded-xl border border-[#0B5E2E]/20">
                    <p class="text-xs text-[#083D1D]/70 mb-2">Periode Cuti</p>
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-[#0B5E2E]">{{ $leave->start_date->format('d') }}</p>
                            <p class="text-sm text-[#083D1D]/70">{{ $leave->start_date->format('M') }}</p>
                            <p class="text-sm text-[#083D1D]/70">{{ $leave->start_date->format('Y') }}</p>
                        </div>
                        <div class="text-[#DCE5DF]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-[#0B5E2E]">{{ $leave->end_date->format('d') }}</p>
                            <p class="text-sm text-[#083D1D]/70">{{ $leave->end_date->format('M') }}</p>
                            <p class="text-sm text-[#083D1D]/70">{{ $leave->end_date->format('Y') }}</p>
                        </div>
                        <div class="ml-4">
                            <span class="px-3 py-1 bg-[#0B5E2E]/10 text-[#083D1D] rounded-full text-sm font-bold border border-[#0B5E2E]/20">
                                {{ $leave->days }} hari
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CATATAN ADMIN --}}
        @if($leave->admin_notes)
            <div class="mb-8">
                <h3 class="text-lg font-bold text-[#083D1D] mb-4">Catatan Admin</h3>
                <div class="bg-[#F9FAF7] rounded-xl border border-[#DCE5DF] p-4 max-h-60 overflow-y-auto">
                    <p class="text-[#083D1D] whitespace-pre-wrap break-words">{{ $leave->admin_notes }}</p>
                </div>
            </div>
        @endif

        {{-- FORM APPROVE / REJECT --}}
        @if($leave->status === 'pending')
            <div class="mt-8 pt-8 border-t border-[#DCE5DF]">
                <h3 class="text-lg font-bold text-[#083D1D] mb-6">Proses Pengajuan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- APPROVE FORM --}}
                    <form method="POST" action="{{ route('admin.leaves.approve', $leave->id) }}"
                          class="bg-gradient-to-br from-[#0B5E2E]/5 to-white border-2 border-[#0B5E2E]/30 rounded-xl p-6">
                        @csrf
                        @method('PUT')

                        <h4 class="text-[#083D1D] font-bold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#0B5E2E]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"/>
                            </svg>
                            Setujui Cuti
                        </h4>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">Catatan (opsional)</label>
                            <textarea name="admin_notes" rows="3"
                                      placeholder="Berikan catatan jika diperlukan..."
                                      class="w-full px-4 py-2 border border-[#DCE5DF] rounded-lg focus:ring-2 focus:ring-[#0B5E2E] focus:border-[#0B5E2E] resize-none"></textarea>
                        </div>

                        <button type="submit"
                                onclick="return confirm('Yakin ingin menyetujui cuti ini?')"
                                class="w-full py-3 bg-[#0B5E2E] hover:bg-[#083D1D] text-white font-bold rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui Pengajuan
                        </button>
                    </form>

                    {{-- REJECT FORM --}}
                    <form method="POST" action="{{ route('admin.leaves.reject', $leave->id) }}"
                          class="bg-gradient-to-br from-red-50/50 to-white border-2 border-red-300 rounded-xl p-6">
                        @csrf
                        @method('PUT')

                        <h4 class="text-red-800 font-bold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"/>
                            </svg>
                            Tolak Cuti
                        </h4>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-[#083D1D] mb-2">
                                Alasan Penolakan <span class="text-red-600">*</span>
                            </label>
                            <textarea name="admin_notes" rows="3" required
                                      placeholder="Wajib diisi - berikan alasan penolakan..."
                                      class="w-full px-4 py-2 border border-[#DCE5DF] rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                        </div>

                        <button type="submit"
                                onclick="return confirm('Yakin ingin menolak cuti ini?')"
                                class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak Pengajuan
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Custom styles for better text handling */
    .whitespace-pre-wrap {
        white-space: pre-wrap;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    .break-words {
        word-break: break-word;
    }
    
    /* Custom scrollbar styling */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #F9FAF7;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #DCE5DF;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #0B5E2E;
    }
</style>
@endsection