@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Data Pegawai</h1>
            <p class="text-sm text-slate-500">Kelola data pegawai dan informasi cuti.</p>
        </div>
        <a href="{{ route('admin.employees.create') }}"
           class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
            + Tambah Pegawai
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-2 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">NIP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500">Sisa Cuti Aktif</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($employees as $employee)
                    @php
                        $remaining = $employee->remaining_leave_days ?? max(0, ($employee->annual_leave_quota ?? 0) - ($employee->used_leave_days ?? 0));
                    @endphp
                    <tr>
                        <td class="px-6 py-3 text-sm text-slate-900">
                            {{ $employee->name }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-600">
                            {{ $employee->employee_id }}
                        </td>
                        <td class="px-6 py-3 text-sm">
                            <span class="inline-flex px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs">
                                {{ $remaining }} hari
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-right space-x-2">
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
