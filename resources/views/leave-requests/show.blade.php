@extends('layouts.app')

@section('title', 'Detail Pengajuan Cuti')
@section('page-title', 'Detail Pengajuan Cuti')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <a href="{{ route('leave-requests.index') }}" 
                       class="inline-flex items-center text-[#083D1D] hover:text-[#0B5E2E] mr-4 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="ml-1">Kembali ke Daftar</span>
                    </a>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">ID Pengajuan</p>
                    <p class="text-sm font-mono text-[#083D1D]">#{{ str_pad($leave->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-[#083D1D]">Detail Pengajuan Cuti</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap pengajuan cuti Anda</p>
                </div>
                
                <!-- Status Badge -->
                @php
                    $status = $leave->status ?? '';
                    $statusConfig = match(strtolower($status)) {
                        'approved' => [
                            'bg' => 'bg-green-50', 
                            'text' => 'text-green-800',
                            'border' => 'border-green-200',
                            'icon' => '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                            'label' => 'Disetujui'
                        ],
                        'pending' => [
                            'bg' => 'bg-[#F9FAF7]', 
                            'text' => 'text-amber-800',
                            'border' => 'border-[#DCE5DF]',
                            'icon' => '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                            'label' => 'Menunggu'
                        ],
                        'rejected' => [
                            'bg' => 'bg-red-50', 
                            'text' => 'text-red-800',
                            'border' => 'border-red-200',
                            'icon' => '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                            'label' => 'Ditolak'
                        ],
                        default => [
                            'bg' => 'bg-[#F9FAF7]', 
                            'text' => 'text-[#083D1D]',
                            'border' => 'border-[#DCE5DF]',
                            'icon' => '',
                            'label' => $status ? ucfirst($status) : '-'
                        ]
                    };
                @endphp
                
                <div class="px-5 py-3 rounded-xl border {{ $statusConfig['border'] }} {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                    <div class="flex items-center">
                        {!! $statusConfig['icon'] !!}
                        <span class="text-lg font-semibold">{{ $statusConfig['label'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] px-6 py-4 border-b border-[#DCE5DF]">
                <div class="flex items-center">
                    <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                        <svg class="w-6 h-6 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-[#083D1D]">Informasi Pengajuan</h2>
                        <p class="text-sm text-gray-600">Detail lengkap permohonan cuti Anda</p>
                    </div>
                </div>
            </div>

            <!-- Card Content -->
            <div class="p-6">
                <!-- Basic Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Leave Type -->
                    <div class="bg-[#F9FAF7] rounded-xl p-5 border border-[#DCE5DF]">
                        <div class="flex items-center mb-3">
                            <div class="p-2 bg-white rounded-lg mr-3 border border-[#DCE5DF]">
                                <svg class="w-5 h-5 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Jenis Cuti</p>
                                <p class="text-lg font-semibold text-[#083D1D] capitalize">
                                    {{ $leave->leave_type ? str_replace('_', ' ', $leave->leave_type) : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="bg-[#F9FAF7] rounded-xl p-5 border border-[#DCE5DF]">
                        <div class="flex items-center mb-3">
                            <div class="p-2 bg-white rounded-lg mr-3 border border-[#DCE5DF]">
                                <svg class="w-5 h-5 text-[#F2B705]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Durasi Cuti</p>
                                <p class="text-lg font-semibold text-[#083D1D]">{{ $leave->days ?? 0 }} hari</p>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="bg-[#F9FAF7] rounded-xl p-5 border border-[#DCE5DF]">
                        <div class="flex items-center mb-3">
                            <div class="p-2 bg-white rounded-lg mr-3 border border-[#DCE5DF]">
                                <svg class="w-5 h-5 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Periode Cuti</p>
                                <p class="text-lg font-semibold text-[#083D1D]">
                                    {{ $leave->start_date?->format('d M Y') ?? '-' }} - {{ $leave->end_date?->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submission Info -->
                    <div class="bg-[#F9FAF7] rounded-xl p-5 border border-[#DCE5DF]">
                        <div class="flex items-center mb-3">
                            <div class="p-2 bg-white rounded-lg mr-3 border border-[#DCE5DF]">
                                <svg class="w-5 h-5 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Diajukan Pada</p>
                                <p class="text-lg font-semibold text-[#083D1D]">
                                    {{ $leave->submitted_at?->format('d M Y, H:i') ?? ($leave->created_at?->format('d M Y, H:i') ?? '-') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reason Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-white rounded-lg mr-3 border border-[#DCE5DF]">
                            <svg class="w-5 h-5 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#083D1D]">Alasan Cuti</h3>
                    </div>
                    <div class="bg-[#F9FAF7] rounded-xl p-6 border border-[#DCE5DF]">
                        <p class="text-[#083D1D] whitespace-pre-line leading-relaxed">{{ $leave->reason ?? '-' }}</p>
                    </div>
                </div>

                <!-- Attachment Section -->
                @if($leave->file_path)
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-white rounded-lg mr-3 border border-[#DCE5DF]">
                            <svg class="w-5 h-5 text-[#F2B705]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#083D1D]">Dokumen Lampiran</h3>
                    </div>
                    <div class="bg-[#F9FAF7] rounded-xl p-6 border border-[#DCE5DF]">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 bg-white rounded-lg shadow-sm mr-4 border border-[#DCE5DF]">
                                    <svg class="w-6 h-6 text-[#083D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[#083D1D]">Dokumen Pendukung</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        @php
                                            $extension = $leave->file_path ? pathinfo($leave->file_path, PATHINFO_EXTENSION) : null;
                                            $filesize = null;
                                            
                                            if ($leave->file_path) {
                                                $fullPath = storage_path('app/public/' . $leave->file_path);
                                                $filesize = file_exists($fullPath) ? round(filesize($fullPath) / 1024, 1) : null;
                                            }
                                        @endphp
                                        {{ $extension ? strtoupper($extension) : 'N/A' }} • 
                                        {{ $filesize ?? 'N/A' }} KB
                                    </p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/'.$leave->file_path) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2.5 rounded-lg border border-[#DCE5DF] 
                                      text-[#083D1D] font-medium hover:bg-[#F9FAF7] hover:border-[#0B5E2E] 
                                      transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Dokumen
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Admin Notes Section -->
                @if($leave->admin_notes || $leave->approver)
                <div>
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-white rounded-lg mr-3 border border-[#DCE5DF]">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#083D1D]">Catatan dan Persetujuan</h3>
                    </div>
                    <div class="bg-[#F9FAF7] rounded-xl p-6 border border-[#DCE5DF]">
                        @if($leave->admin_notes)
                        <div class="mb-6">
                            <p class="text-sm font-medium text-[#083D1D] mb-2">Catatan dari Admin:</p>
                            <div class="bg-white rounded-lg p-4 border border-[#DCE5DF]">
                                <p class="text-[#083D1D] whitespace-pre-line">{{ $leave->admin_notes }}</p>
                            </div>
                        </div>
                        @endif

                        @if($leave->approver)
                        <div class="flex items-center justify-between pt-4 border-t border-[#DCE5DF]">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] flex items-center justify-center text-sm font-bold text-[#0B5E2E] mr-3 border border-[#DCE5DF]">
                                    {{ $leave->approver && $leave->approver->name ? strtoupper(substr($leave->approver->name, 0, 1)) : '?' }}
                                </div>
                                <div>
                                    <p class="font-medium text-[#083D1D]">{{ $leave->approver->name ?? '-' }}</p>
                                    <p class="text-sm text-gray-600">Admin / Penanggung Jawab</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Tanggal Diproses</p>
                                <p class="font-medium text-[#083D1D]">
                                    {{ $leave->reviewed_at?->format('d M Y, H:i') ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('leave-requests.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 rounded-lg border border-[#DCE5DF] 
                          text-[#083D1D] font-medium hover:bg-[#F9FAF7] hover:border-[#0B5E2E] 
                          transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
                
                @if(($leave->status ?? '') === 'pending')
                <form action="{{ route('leave-requests.destroy', $leave->id) }}" 
                      method="POST"
                      onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-lg border border-red-300 
                                   text-red-600 font-medium hover:bg-red-50 hover:border-red-400 
                                   transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Batalkan Pengajuan
                    </button>
                </form>
                @endif
            </div>

            <!-- Tombol Ajukan Cuti Baru -->
            @if(($leave->status ?? '') === 'pending')
            <a href="{{ route('leave-requests.create') }}" 
               class="inline-flex items-center justify-center px-6 py-3 rounded-lg 
                      bg-gradient-to-r from-[#F2B705] to-[#0B5E2E] text-white font-semibold 
                      hover:from-[#F2B705]/90 hover:to-[#0B5E2E]/90 hover:shadow-lg 
                      transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajukan Cuti Baru
            </a>
            @endif
        </div>
    </div>
</div>

<style>
    /* Smooth transitions */
    * {
        transition-property: background-color, border-color, color, transform, box-shadow;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Code formatting for ID */
    .font-mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }
    
    /* Pre-wrap for long text */
    .whitespace-pre-line {
        white-space: pre-line;
        word-wrap: break-word;
    }
</style>
@endsection