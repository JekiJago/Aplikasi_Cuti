@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-1">Manajemen Data Pegawai</h1>
            <p class="text-slate-600">Lihat dan kelola informasi semua pegawai di sistem.</p>
        </div>
        <x-link-button href="{{ route('admin.employees.create') }}" type="primary" class="text-sm">
            + Tambah Pegawai
        </x-link-button>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-6 flex items-start gap-3">
            <span class="text-lg">✓</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="card-elevated overflow-hidden">
        <div class="table-wrapper">
            <table class="table-base">
            <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">NIP</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Sisa Cuti Aktif</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($employees as $employee)
                    @php
                        $remaining = $employee->remaining_leave_days ?? max(0, ($employee->annual_leave_quota ?? 0) - ($employee->used_leave_days ?? 0));
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">
                            {{ $employee->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $employee->employee_id }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex px-3 py-1 rounded-full badge-success">
                                {{ $remaining }} hari
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-right flex justify-end gap-2">
                            <a href="{{ route('admin.employees.show', $employee) }}"
                               class="inline-flex items-center px-3 py-1 text-xs rounded-full border border-slate-200 text-slate-700 hover:bg-slate-50">
                                Lihat
                            </a>

                            <form action="{{ route('admin.employees.destroy', $employee) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Yakin ingin menghapus pegawai ini? Seluruh pengajuan cutinya juga akan dihapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 text-xs rounded-full border border-rose-200 text-rose-600 hover:bg-rose-50">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-sm text-slate-400 text-center">
                            Belum ada data pegawai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection
