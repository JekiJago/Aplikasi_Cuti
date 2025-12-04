@extends('layouts.app')

@section('title', 'Ajukan Cuti')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    {{-- Judul halaman --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Ajukan Cuti Baru</h1>
        <p class="text-base text-slate-600">
            Isi formulir di bawah untuk mengajukan permohonan cuti.
        </p>
    </div>

    {{-- Ringkasan kuota --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        @foreach ($quotaCards as $card)
            <div class="card p-5">
                <p class="text-xs text-slate-600 font-medium uppercase tracking-wider mb-2">{{ $card['label'] }}</p>
                <p class="text-3xl font-bold {{ $card['remaining'] > 0 ? 'text-slate-900' : 'text-rose-600' }}">
                    {{ $card['remaining'] }}<span class="text-lg ml-1">{{ $card['unit'] }}</span>
                </p>
                <p class="text-xs text-slate-500 mt-2">
                    {{ $card['note'] }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- Kartu form --}}
    <div class="card-elevated p-8">
        <h2 class="text-lg font-semibold text-slate-900 mb-1">
            Detail Pengajuan Cuti
        </h2>
        <p class="text-sm text-slate-600 mb-6">
            Lengkapi semua data pengajuan cuti Anda dengan benar.
        </p>

        <form method="POST"
              action="{{ route('leave-requests.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            {{-- Jenis cuti --}}
            <div>
                <label class="form-label form-label-required">Jenis Cuti</label>
                <select name="leave_type" required class="input">
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
                        <x-input type="date" name="start_date" id="start_date" :value="old('start_date')" required />
                    @error('start_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                        <x-input type="date" name="end_date" id="end_date" :value="old('end_date')" required />
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
                  <textarea name="reason" rows="4" required class="input" placeholder="Jelaskan alasan pengajuan cuti Anda...">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dokumen pendukung --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Dokumen Pendukung (opsional)
                </label>
                <div class="rounded-xl border border-dashed border-slate-300 bg-white/60 px-3 py-3 flex flex-col gap-2">
                        <input type="file" name="attachment" class="text-xs w-full file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-slate-700 file:text-white hover:file:bg-slate-800">
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
                <x-secondary-button type="reset" class="text-xs md:text-sm">Reset</x-secondary-button>
                <x-primary-button class="flex items-center space-x-2">
                    <span>✈️</span>
                    <span>Kirim Pengajuan</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
