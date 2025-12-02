@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-xs text-slate-500 mb-1">Nama Lengkap</p>
            <p class="text-lg font-semibold text-slate-900">{{ $user->name }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 mb-1">Alamat Email</p>
            <p class="text-sm font-medium text-slate-900">{{ $user->email }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 mb-1">NIP / ID Pegawai</p>
            <p class="text-sm font-medium text-slate-900">{{ $user->employee_id ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 mb-1">Peran</p>
            <p class="text-sm font-medium text-slate-900">{{ ucfirst($user->role) }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 mb-1">Departemen</p>
            <p class="text-sm font-medium text-slate-900">{{ $user->department ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 mb-1">Jabatan</p>
            <p class="text-sm font-medium text-slate-900">{{ $user->position ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 mb-1">Gender</p>
            <p class="text-sm font-medium text-slate-900">{{ $user->gender === 'female' ? 'Wanita' : 'Pria' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500 mb-1">Tgl. Bergabung</p>
            <p class="text-sm font-medium text-slate-900">
                {{ $user->hire_date?->format('d M Y') ?? '-' }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="text-sm font-semibold text-slate-900 mb-4">Ringkasan Cuti Tahunan</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div class="rounded-2xl border border-slate-100 p-4 bg-slate-50">
                <p class="text-xs text-slate-500">Kuota / Tahun</p>
                <p class="text-2xl font-bold text-slate-900">{{ $annualSummary['quota_per_year'] ?? 0 }} hari</p>
            </div>
            <div class="rounded-2xl border border-slate-100 p-4 bg-slate-50">
                <p class="text-xs text-slate-500">Sisa Tahun Ini</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $annualSummary['current_year_available'] ?? 0 }} hari</p>
            </div>
            <div class="rounded-2xl border border-slate-100 p-4 bg-slate-50">
                <p class="text-xs text-slate-500">Total Tersedia</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $annualSummary['total_available'] ?? 0 }} hari</p>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('profile.edit') }}"
           class="inline-flex items-center px-4 py-2 rounded-full border border-slate-200 text-sm text-slate-700 hover:bg-slate-50">
            Edit Profil
        </a>
        <a href="{{ route('profile.edit') }}#password"
           class="inline-flex items-center px-4 py-2 rounded-full bg-slate-900 text-sm text-white hover:bg-black">
            Ganti Password
        </a>
    </div>
</div>
@endsection

