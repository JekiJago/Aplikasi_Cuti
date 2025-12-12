@extends('layouts.app')

@section('title', 'Review Pengajuan Cuti')
@section('page-title', 'Review Pengajuan Cuti')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- HEADER CARD --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Portal Pegawai</h1>
                <p class="text-slate-600">Sistem Manajemen Cuti</p>
            </div>
            <a href="{{ route('admin.leaves.index') }}" 
               class="px-4 py-2 text-sm bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                ← Kembali ke Daftar
            </a>
        </div>

        {{-- INFO KARYAWAN --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="space-y-2">
                <p class="text-xs text-slate-500">Karyawan</p>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-lg font-bold text-blue-700">
                            {{ strtoupper(substr($leave->user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-bold text-lg">{{ $leave->user->name }}</p>
                        <p class="text-sm text-slate-600">{{ $leave->user->employee_id }}</p>
                    </div>
                </div>
                <p class="text-sm text-slate-700">{{ $leave->user->department ?: '-' }}</p>
                <p class="text-sm text-slate-700">{{ $leave->user->position ?: 'Staff' }}</p>
            </div>

            <div class="space-y-2">
                <p class="text-xs text-slate-500">Jenis Cuti</p>
                <p class="font-bold text-lg">{{ $leave->leave_type }}</p>
                
                <p class="text-xs text-slate-500 mt-4">Status</p>
                @php
                    $statusConfig = match($leave->status) {
                        'approved' => ['class' => 'bg-green-100 text-green-700 border-green-300', 'icon' => '✅'],
                        'pending'  => ['class' => 'bg-yellow-100 text-yellow-700 border-yellow-300', 'icon' => '⏳'],
                        'rejected' => ['class' => 'bg-red-100 text-red-700 border-red-300', 'icon' => '❌'],
                        default    => ['class' => 'bg-gray-100 text-gray-700 border-gray-300', 'icon' => '❓'],
                    };
                @endphp
                <div class="inline-flex items-center px-4 py-2 rounded-lg border {{ $statusConfig['class'] }}">
                    <span class="mr-2">{{ $statusConfig['icon'] }}</span>
                    <span class="font-bold text-lg">{{ strtoupper($leave->status) }}</span>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-xs text-slate-500">Lampiran</p>
                @if($leave->file_path)
                    <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                        <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center">
                            📎
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ basename($leave->file_path) }}</p>
                            <p class="text-xs text-slate-500">Format: JPG</p>
                        </div>
                    </div>
                @else
                    <p class="text-slate-500 italic">Tidak ada lampiran</p>
                @endif
            </div>
        </div>

        <hr class="my-8 border-slate-300">

        {{-- DETAIL --}}
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Detail</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Pengajuan</p>
                    <p class="text-lg font-bold">{{ $leave->user->name }}</p>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-xl">
                    <p class="text-xs text-slate-500 mb-2">Periode Cuti</p>
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-700">{{ $leave->start_date->format('d') }}</p>
                            <p class="text-sm text-slate-600">{{ $leave->start_date->format('M') }}</p>
                            <p class="text-sm text-slate-600">{{ $leave->start_date->format('Y') }}</p>
                        </div>
                        <div class="text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-700">{{ $leave->end_date->format('d') }}</p>
                            <p class="text-sm text-slate-600">{{ $leave->end_date->format('M') }}</p>
                            <p class="text-sm text-slate-600">{{ $leave->end_date->format('Y') }}</p>
                        </div>
                        <div class="ml-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-bold">
                                ({{ $leave->days }} hari)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PREVIEW FILE --}}
        @if($leave->file_path)
            @php
                $attachmentUrl = route('admin.leaves.attachment', ['leave' => $leave->id]);
                $downloadUrl   = route('admin.leaves.attachment', ['leave' => $leave->id, 'download' => 1]);
                $filename      = basename($leave->file_path);
                $extension     = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $isImage       = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                $modalId       = 'attachment-modal-' . $leave->id;
            @endphp

            <div class="mb-8">
                <p class="text-xs text-slate-500 mb-2">Lampiran</p>
                <div class="border rounded-2xl p-4 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-200 flex items-center justify-center text-lg">
                                📎
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">{{ $filename }}</p>
                                <p class="text-xs text-slate-500">Format: {{ strtoupper($extension) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                    data-modal-open="{{ $modalId }}">
                                Preview
                            </button>
                            <a href="{{ $downloadUrl }}"
                               class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-white transition-colors">
                                Download
                            </a>
                        </div>
                    </div>
                </div>

                {{-- MODAL --}}
                <div id="{{ $modalId }}" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden">
                        <div class="flex items-center justify-between border-b px-6 py-4">
                            <div>
                                <p class="font-semibold text-slate-900">Preview Lampiran</p>
                                <p class="text-sm text-slate-500">{{ $filename }}</p>
                            </div>
                            <button type="button" class="text-slate-400 hover:text-slate-600 text-xl" 
                                    data-modal-close="{{ $modalId }}">
                                ✕
                            </button>
                        </div>
                        <div class="p-6 bg-slate-50">
                            @if($isImage)
                                <img src="{{ $attachmentUrl }}" alt="Preview Lampiran"
                                     class="max-h-[70vh] mx-auto rounded-lg shadow">
                            @else
                                <iframe src="{{ $attachmentUrl }}" class="w-full h-[70vh] rounded bg-white"></iframe>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- PROSES ADMIN --}}
        <div>
            <p class="text-xs text-slate-500 mb-2">Diproses oleh</p>
            <div class="bg-slate-50 p-4 rounded-xl">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <span class="text-green-700 font-bold">
                            {{ strtoupper(substr($leave->approver?->name ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-medium">{{ $leave->approver?->name ?? 'Admin' }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $leave->reviewed_at?->format('d M Y H:i') ?? 'Belum diproses' }}
                        </p>
                    </div>
                </div>
                
                @if($leave->admin_notes)
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Catatan Admin:</p>
                        <p class="text-slate-700 p-3 bg-white rounded-lg border">
                            {{ $leave->admin_notes }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- FORM APPROVE / REJECT --}}
        @if(in_array(strtolower($leave->status), ['pending', 'menunggu']))
            <div class="mt-8 pt-8 border-t">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Proses Pengajuan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- APPROVE --}}
                    <form method="POST" action="{{ route('admin.leaves.approve', $leave->id) }}"
                          class="bg-gradient-to-br from-green-50 to-white border-2 border-green-300 rounded-xl p-6">
                        @csrf

                        <h4 class="text-green-800 font-bold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"/>
                            </svg>
                            Setujui Cuti
                        </h4>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Catatan (opsional)</label>
                            <textarea name="admin_notes" rows="3"
                                      placeholder="Berikan catatan jika diperlukan..."
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors">
                            ✓ Setujui Pengajuan
                        </button>
                    </form>

                    {{-- REJECT --}}
                    <form method="POST" action="{{ route('admin.leaves.reject', $leave->id) }}"
                          class="bg-gradient-to-br from-red-50 to-white border-2 border-red-300 rounded-xl p-6">
                        @csrf

                        <h4 class="text-red-800 font-bold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 
                                    7.293a1 1 0 00-1.414 1.414L8.586 
                                    10l-1.293 1.293a1 1 0 101.414 
                                    1.414L10 11.414l1.293 1.293a1 1 0 
                                    001.414-1.414L11.414 10l1.293-1.293a1
                                    1 0 00-1.414-1.414L10 8.586 
                                    8.707 7.293z"
                                    clip-rule="evenodd"/>
                            </svg>
                            Tolak Cuti
                        </h4>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Alasan Penolakan <span class="text-red-600">*</span>
                            </label>
                            <textarea name="admin_notes" rows="3" required
                                      placeholder="Wajib diisi - berikan alasan penolakan..."
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors">
                            ✗ Tolak Pengajuan
                        </button>
                    </form>

                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($leave->file_path)
<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-modal-open');
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-modal-close');
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        });
    });

    document.querySelectorAll('[id*="attachment-modal-"]').forEach(modal => {
        modal.addEventListener('click', e => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id*="attachment-modal-"]').forEach(modal => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }
    });

});
</script>
@endif
@endpush
