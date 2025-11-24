@extends('layouts.guest')

@section('title', 'Login')
@section('heading', 'Login')
@section('subheading', 'Masuk ke Aplikasi Cuti')

@section('content')
<form method="POST" action="{{ url('/login') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Email
        </label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Password
        </label>
        <input type="password" name="password" required
               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
    </div>

    <div class="flex items-center justify-between text-sm">
        <label class="inline-flex items-center">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
            <span class="ml-2 text-gray-600">Remember me</span>
        </label>

        <a href="#" class="text-blue-600 hover:underline text-xs">
            Lupa password?
        </a>
    </div>

    <button type="submit"
            class="w-full py-2 px-4 bg-blue-700 text-white text-sm font-semibold rounded-lg hover:bg-blue-800">
        Login
    </button>

    <p class="text-center text-xs text-gray-500 mt-2">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar</a>
    </p>
</form>
@endsection
