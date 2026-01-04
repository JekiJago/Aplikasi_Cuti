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
                <p class="font-bold text-lg">{{ ucfirst($leave->leave_type) }}</p>
                
                <p class="text-xs text-slate-500 mt-4">Status</p>
                @php
                    $statusConfig = match($leave->status) {
                        'approved' => ['class' => 'bg-green-100 text-green-700 border-green-300', 'icon' => '✅', 'label' => 'DISETUJUI'],
                        'pending'  => ['class' => 'bg-yellow-100 text-yellow-700 border-yellow-300', 'icon' => '⏳', 'label' => 'MENUNGGU'],
                        'rejected' => ['class' => 'bg-red-100 text-red-700 border-red-300', 'icon' => '❌', 'label' => 'DITOLAK'],
                        'cancelled' => ['class' => 'bg-gray-100 text-gray-700 border-gray-300', 'icon' => '🚫', 'label' => 'DIBATALKAN'],
                        default    => ['class' => 'bg-gray-100 text-gray-700 border-gray-300', 'icon' => '❓', 'label' => strtoupper($leave->status)],
                    };
                @endphp
                <div class="inline-flex items-center px-4 py-2 rounded-lg border {{ $statusConfig['class'] }}">
                    <span class="mr-2">{{ $statusConfig['icon'] }}</span>
                    <span class="font-bold text-lg">{{ $statusConfig['label'] }}</span>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-xs text-slate-500">Lampiran</p>
                @if($leave->file_path)
                    @php
                        $filename = basename($leave->file_path);
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        // Shorten filename untuk tampilan
                        $displayName = (strlen($filename) > 20) 
                            ? substr($filename, 0, 15) . '...' . $extension 
                            : $filename;
                    @endphp
                    <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                        <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file text-slate-600"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-sm truncate" title="{{ $filename }}">
                                {{ $displayName }}
                            </p>
                            <p class="text-xs text-slate-500">Format: {{ strtoupper($extension) }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-slate-500 italic">Tidak ada lampiran</p>
                @endif
            </div>
        </div>

        <hr class="my-8 border-slate-300">

        {{-- DETAIL CUTI --}}
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Detail Cuti</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Diajukan pada</p>
                        <p class="text-lg font-bold">{{ $leave->created_at->format('d M Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Durasi Cuti</p>
                        <p class="text-lg font-bold">{{ $leave->days }} hari</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Alasan Cuti</p>
                        <p class="text-slate-700 p-3 bg-slate-50 rounded-lg">{{ $leave->reason ?: '-' }}</p>
                    </div>
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
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-700">{{ $leave->end_date->format('d') }}</p>
                            <p class="text-sm text-slate-600">{{ $leave->end_date->format('M') }}</p>
                            <p class="text-sm text-slate-600">{{ $leave->end_date->format('Y') }}</p>
                        </div>
                        <div class="ml-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-bold">
                                {{ $leave->days }} hari
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- LAMPIRAN FILE --}}
        @if($leave->file_path && Storage::disk('public')->exists($leave->file_path))
            @php
                // Generate URLs untuk preview dan download
                $previewUrl = route('admin.leaves.attachment', ['leaveRequest' => $leave->id]);
                $downloadUrl = route('admin.leaves.attachment', ['leaveRequest' => $leave->id, 'download' => 1]);
                $filename = basename($leave->file_path);
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                // Shorten filename untuk tampilan - INI YANG DIPERBAIKI
                $displayName = (strlen($filename) > 30) 
                    ? substr($filename, 0, 25) . '...' . $extension 
                    : $filename;
                
                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                $modalId = 'attachment-modal-' . $leave->id;
            @endphp

            <div class="mb-8">
                <p class="text-xs text-slate-500 mb-2">Lampiran</p>
                <div class="border rounded-2xl p-4 bg-slate-50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center flex-1 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-lg mr-4 flex-shrink-0">
                                @if($isImage)
                                    <i class="fas fa-file-image text-blue-600"></i>
                                @elseif($extension === 'pdf')
                                    <i class="fas fa-file-pdf text-red-600"></i>
                                @else
                                    <i class="fas fa-file text-slate-600"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-slate-900 truncate" title="{{ $filename }}">
                                    {{ $displayName }}
                                </p>
                                <p class="text-xs text-slate-500">Format: {{ strtoupper($extension) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <button type="button"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap"
                                    onclick="openModal('{{ $modalId }}')">
                                Preview
                            </button>
                            <a href="{{ $downloadUrl }}"
                               class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-white transition-colors whitespace-nowrap">
                                Download
                            </a>
                        </div>
                    </div>
                </div>

                {{-- MODAL PREVIEW --}}
                <div id="{{ $modalId }}" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden max-h-[90vh]">
                        <div class="flex items-center justify-between border-b px-6 py-4">
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-900 truncate">{{ $displayName }}</p>
                                <p class="text-sm text-slate-500 truncate">Lampiran Pengajuan Cuti</p>
                            </div>
                            <button type="button" 
                                    class="text-slate-400 hover:text-slate-600 text-xl p-2 flex-shrink-0"
                                    onclick="closeModal('{{ $modalId }}')">
                                ✕
                            </button>
                        </div>
                        <div class="p-6 bg-slate-50 overflow-auto max-h-[calc(90vh-80px)]">
                            @if($isImage)
                                <img src="{{ $previewUrl }}" alt="Preview Lampiran"
                                     class="max-h-full max-w-full mx-auto rounded-lg shadow"
                                     loading="lazy">
                            @elseif(in_array($extension, ['pdf']))
                                <iframe src="{{ $previewUrl }}" 
                                        class="w-full h-[70vh] rounded bg-white border"
                                        frameborder="0"></iframe>
                            @else
                                <div class="text-center py-12">
                                    <div class="text-5xl mb-4 text-blue-500">
                                        @if($isImage)
                                            <i class="fas fa-file-image"></i>
                                        @elseif($extension === 'pdf')
                                            <i class="fas fa-file-pdf"></i>
                                        @else
                                            <i class="fas fa-file"></i>
                                        @endif
                                    </div>
                                    <p class="text-lg font-medium text-slate-700">File {{ strtoupper($extension) }}</p>
                                    <p class="text-slate-500 mb-6">Format file tidak dapat dipreview di browser</p>
                                    <a href="{{ $downloadUrl }}"
                                       class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download File
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- INFO ADMIN PROSES --}}
        <div class="mb-8">
            <p class="text-xs text-slate-500 mb-2">Proses Admin</p>
            <div class="bg-slate-50 p-4 rounded-xl">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <span class="text-green-700 font-bold">
                            {{ strtoupper(substr($leave->approver?->name ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-medium">{{ $leave->approver?->name ?? 'Belum diproses' }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $leave->reviewed_at?->format('d M Y H:i') ?? 'Belum diproses' }}
                        </p>
                    </div>
                </div>
                
                @if($leave->admin_notes)
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Catatan Admin:</p>
                        <p class="text-slate-700 p-3 bg-white rounded-lg border whitespace-pre-line">
                            {{ $leave->admin_notes }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- FORM APPROVE / REJECT --}}
        @if($leave->status === 'pending')
            <div class="mt-8 pt-8 border-t">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Proses Pengajuan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- APPROVE FORM --}}
                    <form method="POST" action="{{ route('admin.leaves.approve', $leave->id) }}"
                          class="bg-gradient-to-br from-green-50 to-white border-2 border-green-300 rounded-xl p-6">
                        @csrf
                        @method('PUT')

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
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>

                        <button type="submit"
                                onclick="return confirm('Yakin ingin menyetujui cuti ini?')"
                                class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui Pengajuan
                        </button>
                    </form>

                    {{-- REJECT FORM --}}
                    <form method="POST" action="{{ route('admin.leaves.reject', $leave->id) }}"
                          class="bg-gradient-to-br from-red-50 to-white border-2 border-red-300 rounded-xl p-6">
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
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Alasan Penolakan <span class="text-red-600">*</span>
                            </label>
                            <textarea name="admin_notes" rows="3" required
                                      placeholder="Wajib diisi - berikan alasan penolakan..."
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
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

        {{-- TOMBOL BATAL KAN APPROVAL --}}
        @if($leave->status === 'approved')
            <div class="mt-8 pt-8 border-t">
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-yellow-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Batalkan Persetujuan
                    </h3>
                    <p class="text-yellow-700 mb-4">
                        Pengajuan cuti ini sudah disetujui. Anda dapat membatalkan persetujuan jika diperlukan.
                    </p>
                    <form method="POST" action="{{ route('admin.leaves.cancel', $leave->id) }}" 
                          onsubmit="return confirm('Yakin ingin membatalkan persetujuan cuti ini?')">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors">
                            Batalkan Persetujuan
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id*="attachment-modal-"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id*="attachment-modal-"]').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    closeModal(modal.id);
                }
            });
        }
    });
});
</script>
@endpush

<style>
/* Style untuk modal */
.fixed {
    display: flex !important;
}

.hidden {
    display: none !important;
}

/* Smooth transitions */
.modal-content {
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>