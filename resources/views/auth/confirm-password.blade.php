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
                Konfirmasi Keamanan
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl rounded-2xl border border-gray-100 sm:px-10">
                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                {{ __('Password') }}
                            </div>
                        </label>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   required 
                                   autocomplete="current-password"
                                   class="block w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 
                                          bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          focus:bg-white transition-colors placeholder-gray-500 text-gray-900">
                        </div>
                        
                        @error('password')
                            <div class="mt-2 flex items-start space-x-2 text-red-600 text-sm bg-red-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Security Note -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    Konfirmasi password diperlukan untuk mengakses halaman sensitif.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-3 px-4 rounded-lg 
                                       bg-gradient-to-r from-blue-600 to-teal-600 text-white font-semibold 
                                       hover:from-blue-700 hover:to-teal-700 hover:shadow-lg 
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 
                                       transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Confirm') }}
                        </button>
                    </div>

                    <!-- Back Link -->
                    <div class="text-center">
                        <a href="{{ url()->previous() }}" 
                           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke halaman sebelumnya
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>