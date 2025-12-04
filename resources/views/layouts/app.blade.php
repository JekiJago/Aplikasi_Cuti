<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Portal Pegawai') - Sistem Cuti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-blue-50/30 to-cyan-50 text-slate-900 antialiased">

<div class="min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="app-container flex items-center justify-between h-16 gap-4">

            {{-- Logo + Judul --}}
            <div class="flex items-center space-x-2">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center text-white text-lg font-bold">
                    <span>PP</span>
                </div>
                <div class="leading-tight">
                    <p class="font-semibold text-slate-900">Portal Pegawai</p>
                    <p class="text-xs text-slate-500">Sistem Cuti</p>
                </div>
            </div>

            {{-- MENU TAB (EMPLOYEE) --}}
            @if(auth()->check() && auth()->user()->isEmployee())
                <nav class="hidden md:flex items-center space-x-6 text-sm">
                    <a href="{{ route('dashboard') }}"
                       class="pb-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-600 text-blue-700 font-medium' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('leave-requests.create') }}"
                       class="pb-1 border-b-2 {{ request()->routeIs('leave-requests.create') ? 'border-blue-600 text-blue-700 font-medium' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Ajukan Cuti
                    </a>
                    <a href="{{ route('leave-requests.index') }}"
                       class="pb-1 border-b-2 {{ request()->routeIs('leave-requests.*') && !request()->routeIs('leave-requests.create') ? 'border-blue-600 text-blue-700 font-medium' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Riwayat Cuti
                    </a>
                    <a href="{{ route('profile.show') }}"
                       class="pb-1 border-b-2 {{ request()->routeIs('profile.*') ? 'border-blue-600 text-blue-700 font-medium' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Profil Saya
                    </a>
                </nav>
            @endif

            {{-- MENU ADMIN (TOP NAV, TANPA SIDEBAR) --}}
            @if(auth()->check() && auth()->user()->isAdmin())
                <nav class="hidden md:flex items-center space-x-6 text-sm">
                    <a href="{{ route('dashboard') }}"
                    class="pb-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-600 text-blue-700 font-medium' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.employees.index') }}"
                    class="pb-1 border-b-2 {{ request()->routeIs('admin.employees.*') ? 'border-blue-600 text-blue-700 font-medium' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Data Pegawai
                    </a>

                    <a href="{{ route('admin.leaves') }}"
                    class="pb-1 border-b-2 {{ request()->is('admin/leaves*') ? 'border-blue-600 text-blue-700 font-medium' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Verifikasi Cuti
                    </a>

                    <a href="{{ route('admin.settings.holidays') }}"
                    class="pb-1 border-b-2 {{ request()->routeIs('admin.settings.*') ? 'border-blue-600 text-blue-700 font-medium' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Setting Cuti
                    </a>
                </nav>
            @endif

            {{-- INFO USER + LOGOUT DI POJOK KANAN ATAS --}}
            <div class="flex items-center space-x-3 text-sm">
                @if(auth()->check())
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="font-medium text-slate-900">
                            {{ auth()->user()->name }}
                            <span class="text-xs text-slate-500">
                                ({{ auth()->user()->role }})
                            </span>
                        </span>
                        @if(auth()->user()->employee_id)
                            <span class="text-xs text-slate-500">
                                {{ auth()->user()->employee_id }}
                            </span>
                        @endif
                    </div>

                    <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-700">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    {{-- << INI TOMBOL LOGOUT >> --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex px-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-600 hover:bg-slate-50">
                            Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    {{-- KONTEN --}}
    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-4 lg:px-0 py-6">

            {{-- Flash message --}}
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="border-t bg-white">
        <div class="max-w-6xl mx-auto px-4 lg:px-0 py-3 text-xs text-slate-400 flex justify-between">
            <span>&copy; {{ date('Y') }} Portal Pegawai - Sistem Cuti</span>
            <span>Laravel · Tailwind CSS</span>
        </div>
    </footer>
</div>

    {{-- Chart.js untuk grafik dashboard admin --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Stack untuk script tambahan dari setiap halaman --}}
    @stack('scripts')

</body>
</html>
