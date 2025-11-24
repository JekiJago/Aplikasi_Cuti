@extends('layouts.app')

@section('title', 'Setting Cuti')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-xl md:text-2xl font-semibold text-slate-900">Setting Cuti</h1>
        <p class="mt-1 text-sm text-slate-500">
            Kelola hari libur nasional (tidak dihitung sebagai hari cuti).
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-6">
        <h2 class="text-sm font-semibold text-slate-900 mb-1 flex items-center">
            <span class="mr-2">📆</span> Hari Libur Nasional
        </h2>
        <p class="text-xs text-slate-500 mb-4">
            Tambahkan atau hapus hari libur nasional sesuai kebutuhan.
        </p>

        {{-- Form tambah hari libur --}}
        <div class="mb-5 rounded-2xl bg-sky-50 px-4 py-4">
            <p class="text-xs font-semibold text-slate-800 mb-3">Tambah Hari Libur Baru</p>
            <form method="POST" action="{{ route('admin.settings.holidays.store') }}"
                  class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Tanggal</label>
                    <input type="date" name="date"
                           class="w-full rounded-xl border border-slate-200 bg-white text-sm px-3 py-2
                                  focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('date')
                        <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-slate-700 mb-1">Keterangan</label>
                    <input type="text" name="description"
                           placeholder="Misal: Hari Raya Idul Fitri"
                           class="w-full rounded-xl border border-slate-200 bg-white text-sm px-3 py-2
                                  focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('description')
                        <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-3 flex justify-start">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-full bg-slate-900 text-white text-xs md:text-sm hover:bg-black">
                        <span class="mr-1">＋</span> Tambah Libur
                    </button>
                </div>
            </form>
        </div>

        {{-- Daftar hari libur --}}
        <p class="text-xs font-semibold text-slate-800 mb-2">Daftar Hari Libur ({{ $holidays->count() }})</p>
        <div class="space-y-2">
            @forelse($holidays as $holiday)
                <div class="flex items-center justify-between rounded-xl border border-slate-100 px-4 py-3 bg-slate-50">
                    <div>
                        <p class="text-sm font-medium text-slate-900">
                            {{ $holiday->date->translatedFormat('l, d F Y') }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ $holiday->description }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.settings.holidays.destroy', $holiday) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="p-2 rounded-lg border border-rose-200 text-rose-500 hover:bg-rose-50 text-sm"
                                onclick="return confirm('Hapus hari libur ini?')">
                            🗑
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-xs text-slate-400">Belum ada hari libur yang terdaftar.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
