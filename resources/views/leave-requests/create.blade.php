@extends('layouts.app')

@section('title', 'Ajukan Cuti')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Judul halaman --}}
    <div class="mb-6">
        <h1 class="text-xl md:text-2xl font-semibold text-slate-900">
            Form Pengajuan Cuti
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Isi formulir di bawah untuk mengajukan permohonan cuti.
        </p>
    </div>

    {{-- Ringkasan kuota --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
        @foreach ($quotaCards as $card)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
                <p class="text-xs text-slate-500 mb-1">{{ $card['label'] }}</p>
                <p class="text-2xl font-semibold {{ $card['remaining'] > 0 ? 'text-slate-900' : 'text-red-500' }}">
                    {{ $card['remaining'] }} {{ $card['unit'] }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">
                    {{ $card['note'] }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- Kartu form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-7">
        <h2 class="text-sm font-semibold text-slate-900 mb-1">
            Informasi Cuti
        </h2>
        <p class="text-xs text-slate-500 mb-4">
            Lengkapi data pengajuan cuti Anda.
        </p>

        <form method="POST"
              action="{{ route('leave-requests.store') }}"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf

            {{-- Jenis cuti --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Jenis Cuti <span class="text-red-500">*</span>
                </label>
                <select name="leave_type" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 text-sm px-3 py-2
                               focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">Pilih jenis cuti</option>
                    @foreach ([
                        'tahunan'        => 'Cuti Tahunan',
                        'urusan_penting' => 'Cuti Urusan Penting',
                        'cuti_besar'     => 'Cuti Besar',
                        'cuti_non_aktif' => 'Cuti Non Aktif',
                        'cuti_bersalin'  => 'Cuti Bersalin',
                        'cuti_sakit'     => 'Cuti Sakit',
                    ] as $val => $label)
                        <option value="{{ $val }}" {{ old('leave_type') === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('leave_type')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal mulai & selesai --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date"
                           value="{{ old('start_date') }}" required
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 text-sm px-3 py-2
                                  focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('start_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date"
                           value="{{ old('end_date') }}" required
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 text-sm px-3 py-2
                                  focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @error('end_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Alasan cuti --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Detail / Alasan Cuti <span class="text-red-500">*</span>
                </label>
                <textarea name="reason" rows="4" required
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 text-sm px-3 py-2
                                 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          placeholder="Jelaskan alasan pengajuan cuti Anda...">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dokumen pendukung --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Dokumen Pendukung (opsional)
                </label>
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 flex flex-col gap-2">
                    <input type="file" name="attachment"
                           class="text-xs w-full
                                  file:mr-3 file:py-1.5 file:px-3
                                  file:rounded-lg file:border-0
                                  file:text-xs file:font-medium
                                  file:bg-slate-800 file:text-white
                                  hover:file:bg-slate-900">
                    <p class="text-[11px] text-slate-400">
                        Unggah file bukti / dokumen pendukung. Maksimal 5MB, format PDF, JPG, atau PNG.
                    </p>
                </div>
                @error('attachment')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="pt-3 flex items-center justify-end space-x-3">
                <button type="reset"
                        class="px-4 py-2 rounded-full border border-slate-200 bg-white text-xs md:text-sm text-slate-700 hover:bg-slate-50">
                    Reset
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-slate-900 text-xs md:text-sm font-medium text-white flex items-center space-x-2 hover:bg-black">
                    <span>✈️</span>
                    <span>Kirim Pengajuan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
