@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-[#083D1D]">Profil Saya</h1>
            <p class="text-gray-600 mt-1">Informasi profil pegawai</p>
        </div>

        <!-- Profile Sections -->
        <div class="space-y-6">
            <!-- Profile Information (Read-only) -->
            <div class="bg-white rounded-2xl shadow-xl border border-[#DCE5DF] overflow-hidden">
                <div class="bg-gradient-to-r from-[#F9FAF7] to-[#DCE5DF] px-6 py-4 border-b border-[#DCE5DF]">
                    <div class="flex items-center">
                        <div class="p-2 bg-white rounded-lg shadow-sm mr-3">
                            <svg class="w-6 h-6 text-[#0B5E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#083D1D]">Informasi Profil</h3>
                            <p class="text-sm text-gray-600">Informasi pribadi yang tidak dapat diubah</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Nama Field -->
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-1">Nama Lengkap</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" 
                                       value="{{ Auth::user()->name }}" 
                                       readonly
                                       class="flex-1 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] text-[#083D1D] cursor-not-allowed focus:ring-0 focus:border-[#DCE5DF]">
                            </div>
                        </div>

                        <!-- Email/NIP Field -->
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-1">Email / NIP</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" 
                                       value="{{ Auth::user()->email }}" 
                                       readonly
                                       class="flex-1 block w-full rounded-lg border-[#DCE5DF] bg-[#F9FAF7] text-[#083D1D] cursor-not-allowed focus:ring-0 focus:border-[#DCE5DF]">
                            </div>
                        </div>

                        <!-- Status Pegawai Field -->
                        <div>
                            <label class="block text-sm font-medium text-[#083D1D] mb-1">Status</label>
                            <div class="mt-1 flex items-center">
                                <span class="inline-flex items-center px-3 py-2 rounded-lg bg-green-50 text-green-800 font-medium border border-green-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Pegawai Aktif
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Status ini bersifat tetap dan tidak dapat diubah</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan (Opsional) -->
            <div class="bg-[#F9FAF7] border border-[#DCE5DF] rounded-xl p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-[#0B5E2E] mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-[#083D1D]">Informasi</h4>
                        <p class="text-sm text-[#083D1D] mt-1">
                            Data profil (Nama, Email/NIP, dan Status) tidak dapat diubah secara mandiri. 
                            Jika terdapat kesalahan data, harap hubungi administrator.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection