<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Manajemen Cuti')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem manajemen cuti pegawai terintegrasi">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📋</text></svg>">

    {{-- Tailwind via Vite (default Laravel 11) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gradient-to-br from-blue-50 to-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-teal-500 rounded-2xl flex items-center justify-center shadow-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    @yield('heading', 'Sistem Manajemen Cuti')
                </h1>
                <p class="text-gray-600">
                    @yield('subheading', 'Kelola pengajuan cuti dengan mudah dan efisien')
                </p>
            </div>

            <!-- Content Card -->
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <!-- Card Header (optional) -->
                @hasSection('card-header')
                    <div class="bg-gradient-to-r from-blue-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                        @yield('card-header')
                    </div>
                @endif

                <div class="p-8">
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border border-red-200 p-4 animate-fade-in">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Terdapat kesalahan dalam pengisian
                                    </h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-6 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 p-4 animate-fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Warning Message -->
                    @if (session('warning'))
                        <div class="mb-6 rounded-xl bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 p-4 animate-fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-amber-800">{{ session('warning') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Info Message -->
                    @if (session('info'))
                        <div class="mb-6 rounded-xl bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 p-4 animate-fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Main Content -->
                    @yield('content')
                </div>

                <!-- Card Footer (optional) -->
                @hasSection('card-footer')
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        @yield('card-footer')
                    </div>
                @endif
            </div>

            <!-- Additional Links/Info -->
            @hasSection('additional-links')
                <div class="mt-6">
                    @yield('additional-links')
                </div>
            @endif

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} <span class="font-semibold text-gray-700">Sistem Manajemen Cuti</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Versi {{ config('app.version', '1.0.0') }}
                </p>
                @hasSection('footer-note')
                    <div class="mt-2">
                        @yield('footer-note')
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Optional Scripts -->
    @stack('scripts')

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(-10px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, transform, box-shadow;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Focus styles */
        input:focus, button:focus, select:focus {
            outline: none;
            ring-offset-color: transparent;
        }
    </style>
</body>
</html>