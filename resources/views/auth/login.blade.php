@extends('layouts.guest')

@section('title', 'Login')
@section('heading', 'Login')
@section('subheading', 'Masuk ke Aplikasi Cuti')

@section('content')
<form method="POST" action="{{ url('/login') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Email / NIP
        </label>
        <x-input type="text" name="email" :value="old('email')" required autofocus placeholder="Masukkan Email atau NIP" />
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Password
        </label>
        <x-input type="password" name="password" required placeholder="Masukkan Password" />
    </div>

    <div class="flex items-center justify-between text-sm">
        <label class="inline-flex items-center">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
            <span class="ml-2 text-gray-600">Remember me</span>
        </label>

        <a href="#" class="text-sky-600 hover:underline text-xs">
            Lupa password?
        </a>
    </div>

    <x-primary-button class="w-full justify-center">
        Login
    </x-primary-button>

    <p class="text-center text-xs text-gray-500 mt-2">
        Belum punya akun?
        <x-link-button href="{{ route('register') }}" type="default" class="text-xs">
            Daftar
        </x-link-button>
    </p>
</form>
@endsection
