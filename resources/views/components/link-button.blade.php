@props(['href' => '#', 'type' => 'primary'])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg no-underline transition ease-in-out duration-150 focus:outline-none';
    
    $typeClasses = match($type) {
        'secondary' => 'px-4 py-2 bg-slate-200 text-slate-800 hover:bg-slate-300 focus:ring-2 focus:ring-offset-2 focus:ring-slate-400',
        'primary' => 'px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-500 text-white hover:from-sky-600 hover:to-cyan-600 focus:ring-2 focus:ring-offset-2 focus:ring-cyan-400',
        'danger' => 'px-4 py-2 bg-rose-600 text-white hover:bg-rose-700 focus:ring-2 focus:ring-offset-2 focus:ring-rose-500',
        default => 'text-sky-600 hover:text-sky-700 hover:underline',
    };
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $typeClasses]) }}>
    {{ $slot }}
</a>
