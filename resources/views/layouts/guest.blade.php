<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Manajemen Cuti')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem manajemen cuti pegawai terintegrasi">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS via CDN (untuk backup) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Vite (Laravel default) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom styles untuk konsistensi */
        * {
            transition-property: background-color, border-color, color, transform, box-shadow;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
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
        
        /* Password strength colors */
        .strength-weak { color: #ef4444; }
        .strength-medium { color: #f59e0b; }
        .strength-strong { color: #10b981; }
        .strength-very-strong { color: #8b5cf6; }
        
        /* Split-screen specific */
        @media (max-width: 1023px) {
            .split-left {
                display: none !important;
            }
        }
    </style>
</head>
<body class="h-full">
    @yield('full-content')
    
    @stack('scripts')
</body>
</html>