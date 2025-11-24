@extends('layouts.app')

@section('title', 'Pengajuan Cuti')
@section('page-title', 'Pengajuan Cuti Saya')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-base font-semibold">Daftar Pengajuan Cuti</h2>
    <a href="{{ route('leave-requests.create') }}"
       class="inline-flex items-center px-3 py-2 bg-blue-700 text-white text-sm rounded-lg hover:bg-blue-800">
        + Ajukan Cuti
    </a>
</div>

{{-- Filter sederhana --}}
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
        <select name="status" class="w-full rounded-lg border-gray-300">
            <option value="">Semua</option>
            @foreach(['pending' => 'Pending', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
        <input type="date" name="from" value="{{ request('from') }}"
               class="w-full rounded-lg border-gray-300">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
        <input type="date" name="to" value="{{ request('to') }}"
               class="w-full rounded-lg border-gray-300">
    </div>
    <div class="flex items-end">
        <button type="submit"
                class="w-full px-3 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-900">
            Filter
        </button>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
        <tr>
            <th class="px-4 py-2 text-left">Tanggal</th>
            <th class="px-4 py-2 text-left">Jenis</th>
            <th class="px-4 py-2 text-left">Hari</th>
            <th class="px-4 py-2 text-left">Status</th>
            <th class="px-4 py-2 text-right">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse($leaveRequests as $leave)
            <tr class="border-t">
                <td class="px-4 py-2">
                    {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                </td>
                <td class="px-4 py-2 capitalize">{{ $leave->leave_type }}</td>
                <td class="px-4 py-2">{{ $leave->days }}</td>
                <td class="px-4 py-2">
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
                </td>
                <td class="px-4 py-2 text-right space-x-2">
                    <a href="{{ route('leave-requests.show', $leave->id) }}"
                       class="inline-flex px-3 py-1 rounded-lg border text-xs hover:bg-gray-50">
                        Detail
                    </a>

                    @if($leave->status === 'pending')
                        <form action="{{ route('leave-requests.destroy', $leave->id) }}" method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Hapus pengajuan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex px-3 py-1 rounded-lg border border-red-300 text-xs text-red-600 hover:bg-red-50">
                                Hapus
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                    Belum ada pengajuan cuti.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="px-4 py-3 border-t">
        {{ $leaveRequests->withQueryString()->links() }}
    </div>
</div>
@endsection
