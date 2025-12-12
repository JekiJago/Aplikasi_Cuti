<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
            <h2 class="mt-6 text-center text-2xl font-bold text-gray-900">
                Reset Password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 max-w-md mx-auto">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl rounded-2xl border border-gray-100 sm:px-10">
                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ __('Email Address') }}
                            </div>
                        </label>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   required 
                                   autofocus
                                   value="{{ old('email') }}"
                                   class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 
                                          bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          focus:bg-white transition-colors placeholder-gray-500 text-gray-900"
                                   placeholder="nama@email.com">
                        </div>
                        
                        @error('email')
                            <div class="mt-2 flex items-start space-x-2 text-red-600 text-sm bg-red-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-800">
                                    Instruksi Reset Password
                                </p>
                                <p class="text-sm text-blue-700 mt-1">
                                    Link reset password akan dikirim ke email Anda. Periksa folder spam jika tidak menerima email.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center items-center py-3 px-4 rounded-lg 
                                       bg-gradient-to-r from-blue-600 to-teal-600 text-white font-semibold 
                                       hover:from-blue-700 hover:to-teal-700 hover:shadow-lg 
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 
                                       transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke halaman login
                        </a>
                    </div>
                </form>
            </div>

            <!-- Additional Help -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Butuh bantuan? Hubungi administrator sistem.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>

<style>
    /* Custom styling untuk auth-session-status component */
    :deep(.auth-session-status) {
        @apply rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 p-4 mb-6;
    }
    
    :deep(.auth-session-status div) {
        @apply flex items-center text-sm text-green-800;
    }
    
    :deep(.auth-session-status svg) {
        @apply h-5 w-5 text-green-600 mr-3 flex-shrink-0;
    }
</style>