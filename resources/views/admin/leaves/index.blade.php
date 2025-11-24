@extends('layouts.app')

@section('title', 'Semua Pengajuan Cuti')
@section('page-title', 'Semua Pengajuan Cuti')

@section('content')
<h2 class="text-base font-semibold mb-4">Daftar Pengajuan Cuti Karyawan</h2>

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
        <label class="block text-xs font-medium text-gray-600 mb-1">Departemen</label>
        <input type="text" name="department" value="{{ request('department') }}"
               class="w-full rounded-lg border-gray-300">
    </div>
    <div class="md:col-span-2 flex items-end">
        <button type="submit"
                class="px-4 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-900">
            Filter
        </button>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
        <tr>
            <th class="px-4 py-2 text-left">Karyawan</th>
            <th class="px-4 py-2 text-left">Departemen</th>
            <th class="px-4 py-2 text-left">Tanggal</th>
            <th class="px-4 py-2 text-left">Jenis</th>
            <th class="px-4 py-2 text-left">Status</th>
            <th class="px-4 py-2 text-right">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse($leaves as $leave)
            <tr class="border-t">
                <td class="px-4 py-2">
                    {{ $leave->user->name }}<br>
                    <span class="text-xs text-gray-500">{{ $leave->user->employee_id }}</span>
                </td>
                <td class="px-4 py-2">
                    {{ $leave->user->department ?: '-' }}
                </td>
                <td class="px-4 py-2">
                    {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                </td>
                <td class="px-4 py-2 capitalize">{{ $leave->leave_type }}</td>
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
                <td class="px-4 py-2 text-right">
                    <a href="{{ route('admin.leaves.show', $leave->id) }}"
                       class="inline-flex px-3 py-1 rounded-lg border text-xs hover:bg-gray-50">
                        Review
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                    Belum ada pengajuan cuti.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="px-4 py-3 border-t">
        {{ $leaves->withQueryString()->links() }}
    </div>
</div>
@endsection
