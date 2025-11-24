<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Aplikasi Cuti')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind via Vite (default Laravel 11) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-100 flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="bg-white shadow-lg rounded-xl p-8">
            <h1 class="text-2xl font-bold text-center text-blue-700 mb-2">
                @yield('heading', 'Aplikasi Cuti')
            </h1>
            <p class="text-center text-gray-500 mb-6">
                @yield('subheading')
            </p>

            {{-- Alert error global --}}
            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2 text-sm text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Alert sukses --}}
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-2 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>

        <p class="mt-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Aplikasi Cuti
        </p>
    </div>
</body>
</html>
