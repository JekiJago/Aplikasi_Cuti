@extends('layouts.app')

@section('title', 'Detail Pengajuan Cuti')
@section('page-title', 'Detail Pengajuan Cuti')

@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow-sm p-6 space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-base font-semibold">Informasi Pengajuan</h2>
        <x-link-button href="{{ route('leave-requests.index') }}" type="secondary" class="text-sm">
            Kembali
        </x-link-button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-xs text-gray-500">Jenis Cuti</p>
            <p class="font-medium capitalize">{{ $leave->leave_type }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Status</p>
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
            <p class="text-xs text-gray-500">Tanggal Mulai</p>
            <p class="font-medium">{{ $leave->start_date->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Tanggal Selesai</p>
            <p class="font-medium">{{ $leave->end_date->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Jumlah Hari</p>
            <p class="font-medium">{{ $leave->days }} hari</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Diajukan Pada</p>
            <p class="font-medium">{{ $leave->submitted_at?->format('d M Y H:i') }}</p>
        </div>
    </div>

    <div class="text-sm">
        <p class="text-xs text-gray-500 mb-1">Alasan Cuti</p>
        <p class="whitespace-pre-line">{{ $leave->reason }}</p>
    </div>

    @if($leave->file_path)
        <div class="text-sm">
            <p class="text-xs text-gray-500 mb-1">Lampiran</p>
            <x-link-button href="{{ asset('storage/'.$leave->file_path) }}" type="primary" target="_blank"
               class="px-3 py-2 text-xs">
                Lihat Lampiran
            </x-link-button>
        </div>
    @endif

    @if($leave->admin_notes || $leave->approver)
        <div class="border-t pt-4 text-sm">
            <p class="text-xs text-gray-500 mb-1">Catatan Admin</p>
            <p class="whitespace-pre-line mb-2">
                {{ $leave->admin_notes ?: '-' }}
            </p>
            @if($leave->approver)
                <p class="text-xs text-gray-500">
                    Diproses oleh: <span class="font-medium">{{ $leave->approver->name }}</span>
                    pada {{ $leave->reviewed_at?->format('d M Y H:i') }}
                </p>
            @endif
        </div>
    @endif
</div>
@endsection
