@extends('layouts.app')

@section('title', 'Review Pengajuan Cuti')
@section('page-title', 'Review Pengajuan Cuti')

@section('content')
<div class="max-w-3xl bg-white rounded-xl shadow-sm p-6 space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-base font-semibold">Detail Pengajuan</h2>
        <a href="{{ route('admin.leaves') }}" class="text-sm text-blue-700 hover:underline">
            Kembali ke daftar
        </a>
    </div>

    {{-- DETAIL UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-xs text-gray-500 mb-1">Karyawan</p>
            <p class="font-medium">{{ $leave->user->name }}</p>
            <p class="text-xs text-gray-500">
                {{ $leave->user->employee_id }} - {{ $leave->user->position }}
            </p>
        </div>

        <div>
            <p class="text-xs text-gray-500 mb-1">Departemen</p>
            <p class="font-medium">{{ $leave->user->department ?: '-' }}</p>
        </div>

        <div>
            <p class="text-xs text-gray-500 mb-1">Jenis Cuti</p>
            <p class="font-medium">{{ $leave->leave_type }}</p>
        </div>

        <div>
            <p class="text-xs text-gray-500 mb-1">Status</p>
            @php
                $color = match($leave->status) {
                    'approved' => 'bg-green-100 text-green-700',
                    'pending'  => 'bg-yellow-100 text-yellow-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    default    => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $color }}">
                {{ strtoupper($leave->status) }}
            </span>
        </div>

        <div>
            <p class="text-xs text-gray-500 mb-1">Tanggal</p>
            <p class="font-medium">
                {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                ({{ $leave->days }} hari)
            </p>
        </div>

        <div>
            <p class="text-xs text-gray-500 mb-1">Diajukan</p>
            <p class="font-medium">
                {{ $leave->submitted_at?->format('d M Y H:i') }}
            </p>
        </div>
    </div>

    <div class="text-sm">
        <p class="text-xs text-gray-500 mb-1">Alasan Cuti</p>
        <p class="whitespace-pre-line">{{ $leave->reason }}</p>
    </div>

    @if($leave->file_path)
        @php
            $attachmentUrl = route('admin.leaves.attachment', ['leave' => $leave->id]);
            $downloadUrl   = route('admin.leaves.attachment', ['leave' => $leave->id, 'download' => 1]);
            $filename      = basename($leave->file_path);
            $extension     = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $isImage       = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
            $modalId       = 'attachment-modal-' . $leave->id;
        @endphp

        <div class="text-sm">
            <p class="text-xs text-gray-500 mb-2">Lampiran</p>
            <div class="flex items-center justify-between border rounded-2xl px-4 py-3 bg-slate-50">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-slate-200 flex items-center justify-center text-lg">
                        📎
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">{{ $filename }}</p>
                        <p class="text-[11px] text-slate-500">Format: {{ strtoupper($extension) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button"
                            class="px-3 py-1.5 text-xs rounded-full bg-indigo-600 text-white hover:bg-indigo-700"
                            data-modal-open="{{ $modalId }}">
                        Preview
                    </button>
                    <a href="{{ $downloadUrl }}"
                       class="px-3 py-1.5 text-xs rounded-full border border-slate-300 text-slate-700 hover:bg-white">
                        Download
                    </a>
                </div>
            </div>
        </div>

        <div id="{{ $modalId }}" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden">
                <div class="flex items-center justify-between border-b px-4 py-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Lampiran</p>
                        <p class="text-xs text-slate-500">{{ $filename }}</p>
                    </div>
                    <button type="button" class="text-slate-400 hover:text-slate-600" data-modal-close="{{ $modalId }}">
                        ✕
                    </button>
                </div>
                <div class="p-4 bg-slate-50">
                    @if($isImage)
                        <img src="{{ $attachmentUrl }}" alt="Preview Lampiran"
                             class="max-h-[70vh] mx-auto rounded shadow">
                    @else
                        <iframe src="{{ $attachmentUrl }}" class="w-full h-[70vh] rounded bg-white"></iframe>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- PROSES APPROVE / REJECT --}}
    {{-- PROSES APPROVE / REJECT --}}
@if(in_array(strtolower($leave->status), ['pending', 'menunggu']))
    <div class="border-t-2 pt-6 mt-6">
        <h3 class="text-lg font-bold mb-4 text-gray-800">Proses Pengajuan</h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- ✅ APPROVE FORM --}}
            <div class="border-2 border-green-400 rounded-xl p-5 bg-gradient-to-br from-green-50 to-white shadow-md">
                <h4 class="text-green-800 font-semibold mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Setujui Cuti
                </h4>
                
                <form method="POST" action="{{ route('admin.leaves.approve', $leave->id) }}" class="space-y-3">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan Persetujuan (opsional)
                        </label>
                        <textarea 
                            name="admin_notes" 
                            rows="3"
                            placeholder="Tambahkan catatan jika diperlukan..."
                            class="w-full rounded-lg border-2 border-gray-300 text-sm p-3 
                                   focus:border-green-500 focus:ring-2 focus:ring-green-200 
                                   transition-all duration-200"
                        ></textarea>
                    </div>

                    <button 
                        type="submit"
                        class="w-full px-6 py-3 rounded-lg 
                               bg-green-600 hover:bg-green-700 active:bg-green-800
                               text-white font-bold text-base 
                               shadow-lg hover:shadow-xl
                               transform hover:-translate-y-0.5 
                               transition-all duration-200
                               focus:outline-none focus:ring-4 focus:ring-green-300"
                    >
                        ✓ Setujui Cuti Ini
                    </button>
                </form>
            </div>

            {{-- ❌ REJECT FORM --}}
            <div class="border-2 border-red-400 rounded-xl p-5 bg-gradient-to-br from-red-50 to-white shadow-md">
                <h4 class="text-red-800 font-semibold mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Tolak Cuti
                </h4>
                
                <form method="POST" action="{{ route('admin.leaves.reject', $leave->id) }}" class="space-y-3">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alasan Penolakan <span class="text-red-600 font-bold">*</span>
                        </label>
                        <textarea 
                            name="admin_notes" 
                            rows="3" 
                            required
                            placeholder="Wajib diisi - berikan alasan penolakan..."
                            class="w-full rounded-lg border-2 border-gray-300 text-sm p-3 
                                   focus:border-red-500 focus:ring-2 focus:ring-red-200 
                                   transition-all duration-200"
                        ></textarea>
                    </div>

                    <button 
                        type="submit"
                        class="w-full px-6 py-3 rounded-lg 
                               bg-red-600 hover:bg-red-700 active:bg-red-800
                               text-white font-bold text-base 
                               shadow-lg hover:shadow-xl
                               transform hover:-translate-y-0.5 
                               transition-all duration-200
                               focus:outline-none focus:ring-4 focus:ring-red-300"
                    >
                        ✗ Tolak Cuti Ini
                    </button>
                </form>
            </div>
        </div>
    </div>
@else
    {{-- Sudah diproses --}}
    <div class="border-t pt-4 text-sm mt-6">
        <p class="text-xs text-gray-500 mb-1">Diproses oleh</p>
        <p class="font-medium">
            {{ $leave->approver?->name ?? '-' }}
            @if($leave->reviewed_at)
                ({{ $leave->reviewed_at->format('d M Y H:i') }})
            @endif
        </p>

        <p class="text-xs text-gray-500 mt-2 mb-1">Catatan Admin:</p>
        <p class="whitespace-pre-line">
            {{ $leave->admin_notes ?: '-' }}
        </p>
    </div>
@endif
</div>
@endsection

@push('scripts')
@if($leave->file_path)
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-modal-open]').forEach(button => {
            const targetId = button.getAttribute('data-modal-open');
            const modal = document.getElementById(targetId);
            if (!modal) return;

            const closeModal = () => modal.classList.add('hidden');

            button.addEventListener('click', () => modal.classList.remove('hidden'));
            modal.querySelectorAll('[data-modal-close]').forEach(closeBtn => closeBtn.addEventListener('click', closeModal));
            modal.addEventListener('click', event => {
                if (event.target === modal) {
                    closeModal();
                }
            });
        });
    });
</script>
@endif
@endpush
