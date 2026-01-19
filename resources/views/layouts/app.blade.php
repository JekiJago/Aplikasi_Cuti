<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Portal Pegawai') - Sistem Manajemen Cuti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem manajemen cuti pegawai terintegrasi">
    
    <!-- Favicon tetap bisa gunakan logo Kejari jika ada, jika tidak pakai default -->
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚖️</text></svg>">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gradient-to-b from-[#F9FAF7] to-[#DCE5DF]/30 text-[#083D1D] antialiased">

<div class="min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="bg-[#F9FAF7] shadow-lg border-b border-[#DCE5DF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo + Brand --}}
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 no-underline">
                        <!-- Logo Kejaksaan Negeri Pontianak -->
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#0B5E2E] to-[#083D1D] flex items-center justify-center text-white text-lg font-bold shadow-md">
                            <!-- Ganti dengan logo Kejari Pontianak -->
                            <!-- Jika ada file logo, gunakan: <img src="{{ asset('images/kejari-pontianak-logo.png') }}" class="w-8 h-8"> -->
                            <!-- Jika tidak, gunakan ikon scales (timbangan) untuk simbol hukum -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                </path>
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <!-- Ganti nama aplikasi -->
                            <p class="font-bold text-[#083D1D] text-lg">Kejaksaan Negeri</p>
                            <p class="text-xs text-[#083D1D]/70">Sistem Manajemen Cuti</p>
                        </div>
                    </a>
                </div>

                {{-- Desktop Navigation --}}
                @if(auth()->check())
                <nav class="hidden md:flex items-center space-x-8">
                    @if(auth()->user()->isEmployee())
                        <!-- Employee Menu - HAPUS "PROFIL SAYA" -->
                        <a href="{{ route('dashboard') }}"
                           class="relative pb-1 px-1 text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('dashboard') 
                                     ? 'text-[#0B5E2E] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-gradient-to-r after:from-[#0B5E2E] after:to-[#083D1D]' 
                                     : 'text-[#083D1D]/70 hover:text-[#083D1D]' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Dashboard
                            </div>
                        </a>
                        <a href="{{ route('leave-requests.create') }}"
                           class="relative pb-1 px-1 text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('leave-requests.create') 
                                     ? 'text-[#0B5E2E] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-gradient-to-r after:from-[#0B5E2E] after:to-[#083D1D]' 
                                     : 'text-[#083D1D]/70 hover:text-[#083D1D]' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Ajukan Cuti
                            </div>
                        </a>
                        <a href="{{ route('leave-requests.index') }}"
                           class="relative pb-1 px-1 text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('leave-requests.*') && !request()->routeIs('leave-requests.create') 
                                     ? 'text-[#0B5E2E] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-gradient-to-r after:from-[#0B5E2E] after:to-[#083D1D]' 
                                     : 'text-[#083D1D]/70 hover:text-[#083D1D]' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Riwayat Cuti
                            </div>
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <!-- Admin Menu - Tetap lengkap -->
                        <a href="{{ route('dashboard') }}"
                           class="relative pb-1 px-1 text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('dashboard') 
                                     ? 'text-[#0B5E2E] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-gradient-to-r after:from-[#0B5E2E] after:to-[#083D1D]' 
                                     : 'text-[#083D1D]/70 hover:text-[#083D1D]' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Dashboard
                            </div>
                        </a>
                        <a href="{{ route('admin.employees.index') }}"
                           class="relative pb-1 px-1 text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('admin.employees.*') 
                                     ? 'text-[#0B5E2E] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-gradient-to-r after:from-[#0B5E2E] after:to-[#083D1D]' 
                                     : 'text-[#083D1D]/70 hover:text-[#083D1D]' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Data Pegawai
                            </div>
                        </a>
                        <a href="{{ route('admin.leaves.index') }}"
                           class="relative pb-1 px-1 text-sm font-medium transition-colors duration-200
                                  {{ request()->is('admin/leaves*') 
                                     ? 'text-[#0B5E2E] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-gradient-to-r after:from-[#0B5E2E] after:to-[#083D1D]' 
                                     : 'text-[#083D1D]/70 hover:text-[#083D1D]' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Verifikasi Cuti
                            </div>
                        </a>
                        <a href="{{ route('admin.settings.holidays') }}"
                           class="relative pb-1 px-1 text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('admin.settings.*') 
                                     ? 'text-[#0B5E2E] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-gradient-to-r after:from-[#0B5E2E] after:to-[#083D1D]' 
                                     : 'text-[#083D1D]/70 hover:text-[#083D1D]' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Pengaturan
                            </div>
                        </a>
                    @endif
                </nav>
                @endif

                {{-- Mobile Menu Button --}}
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button"
                            class="inline-flex items-center justify-center p-2 rounded-md text-[#083D1D]/70 hover:text-[#083D1D] hover:bg-[#F9FAF7] focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#0B5E2E]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                {{-- User Profile & Logout --}}
                @if(auth()->check())
                <div class="hidden md:flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-[#083D1D]">
                            {{ auth()->user()->name }}
                            <span class="text-xs font-normal text-[#083D1D]/70 capitalize">
                                ({{ auth()->user()->role === 'admin' ? 'Administrator' : 'Pegawai' }})
                            </span>
                        </p>
                        @if(auth()->user()->employee_id)
                            <p class="text-xs text-[#083D1D]/70 font-mono">
                                {{ auth()->user()->employee_id }}
                            </p>
                        @endif
                    </div>

                    {{-- Tombol Profil dan Logout --}}
                    <div class="flex items-center space-x-3">
                        
                        <!-- Tombol Logout - WARNA MERAH -->
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                    title="Keluar dari sistem">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            {{-- Mobile Menu --}}
            @if(auth()->check())
            <div id="mobile-menu" class="md:hidden hidden bg-[#F9FAF7] border-t border-[#DCE5DF] py-3 px-4">
                <div class="space-y-1">
                    @if(auth()->user()->isEmployee())
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-[#0B5E2E]/10 text-[#083D1D]' : 'text-[#083D1D]/70 hover:bg-[#F9FAF7]' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('leave-requests.create') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('leave-requests.create') ? 'bg-[#0B5E2E]/10 text-[#083D1D]' : 'text-[#083D1D]/70 hover:bg-[#F9FAF7]' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Ajukan Cuti
                        </a>
                        <a href="{{ route('leave-requests.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('leave-requests.*') && !request()->routeIs('leave-requests.create') ? 'bg-[#0B5E2E]/10 text-[#083D1D]' : 'text-[#083D1D]/70 hover:bg-[#F9FAF7]' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Riwayat Cuti
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-[#0B5E2E]/10 text-[#083D1D]' : 'text-[#083D1D]/70 hover:bg-[#F9FAF7]' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.employees.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.employees.*') ? 'bg-[#0B5E2E]/10 text-[#083D1D]' : 'text-[#083D1D]/70 hover:bg-[#F9FAF7]' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Data Pegawai
                        </a>
                        <a href="{{ route('admin.leaves.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium {{ request()->is('admin/leaves*') ? 'bg-[#0B5E2E]/10 text-[#083D1D]' : 'text-[#083D1D]/70 hover:bg-[#F9FAF7]' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Verifikasi Cuti
                        </a>
                        <a href="{{ route('admin.settings.holidays') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.settings.*') ? 'bg-[#0B5E2E]/10 text-[#083D1D]' : 'text-[#083D1D]/70 hover:bg-[#F9FAF7]' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Pengaturan
                        </a>
                    @endif

                    <div class="pt-4 border-t border-[#DCE5DF]">
                        <div class="px-3 py-2">
                            <p class="text-sm font-semibold text-[#083D1D]">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-[#083D1D]/70">{{ auth()->user()->employee_id ?? '' }}</p>
                        </div>
                        <div class="grid grid-cols-1 gap-2 mt-2">
                            <!-- HAPUS TOMBOL PROFIL -->
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit"
                                        class="flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 w-full">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar dari Sistem
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl bg-gradient-to-r from-[#0B5E2E]/10 to-[#083D1D]/10 border border-[#0B5E2E]/20 px-4 py-3 text-sm text-[#083D1D] animate-fade-in">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-[#0B5E2E] mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border border-red-200 px-4 py-3 text-sm text-red-800 animate-fade-in">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-[#F9FAF7] border-t border-[#DCE5DF] shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <!-- Ganti juga di footer -->
                    <p class="text-sm text-[#083D1D]/70">
                        &copy; {{ date('Y') }} <span class="font-semibold">Kejaksaan Negeri Pontianak</span> - Sistem Manajemen Cuti
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-xs text-[#083D1D]/70 px-2 py-1 bg-[#DCE5DF] rounded">
                        Laravel {{ app()->version() }}
                    </span>
                    <span class="text-xs text-[#083D1D]/70">
                        Status: <span class="text-[#0B5E2E] font-medium">Online</span>
                    </span>
                </div>
            </div>
        </div>
    </footer>
</div>

{{-- Mobile Menu Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                
                // Toggle icon
                const icon = menuButton.querySelector('svg');
                if (mobileMenu.classList.contains('hidden')) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
                } else {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                }
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!menuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    const icon = menuButton.querySelector('svg');
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
                }
            });
        }
    });
</script>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Stack untuk script tambahan --}}
@stack('scripts')

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Smooth transitions */
    * {
        transition-property: background-color, border-color, color, transform, box-shadow;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
</style>

</body>
</html>