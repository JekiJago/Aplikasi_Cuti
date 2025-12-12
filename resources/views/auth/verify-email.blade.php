<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-teal-500 rounded-2xl flex items-center justify-center shadow-xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Verifikasi Email
                </h1>
                <p class="mt-2 text-sm text-gray-600 max-w-md mx-auto">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
            </div>

            <!-- Content Card -->
            <div class="mt-8">
                <div class="bg-white py-8 px-4 shadow-2xl rounded-3xl border border-gray-100 sm:px-10">
                    <!-- Success Message -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-6 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 p-4 animate-fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Instructions Box -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-800">
                                    Langkah-langkah verifikasi:
                                </p>
                                <ul class="mt-2 text-sm text-blue-700 space-y-1">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Periksa inbox email Anda
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Klik link verifikasi dalam email
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Akun Anda akan langsung aktif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <!-- Resend Email Form -->
                        <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                    class="w-full flex justify-center items-center py-3.5 px-4 rounded-lg 
                                           bg-gradient-to-r from-blue-600 to-teal-600 text-white font-semibold 
                                           hover:from-blue-700 hover:to-teal-700 hover:shadow-xl 
                                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 
                                           transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Atau</span>
                            </div>
                        </div>

                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                    class="w-full flex justify-center items-center py-3 px-4 rounded-lg 
                                           border border-gray-300 text-gray-700 font-medium 
                                           hover:bg-gray-50 hover:border-gray-400 hover:shadow-md 
                                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 
                                           transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>

                    <!-- Troubleshooting -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-amber-800">
                                        Tidak menerima email?
                                    </p>
                                    <p class="mt-1 text-sm text-amber-700">
                                        Periksa folder spam atau junk mail. Pastikan email yang Anda masukkan saat registrasi benar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Butuh bantuan? Hubungi support@perusahaan.com
                    </p>
                </div>
            </div>
        </div>
    </div>

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
            transition-property: background-color, border-color, transform, box-shadow;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Button focus styles */
        button:focus {
            outline: none;
            ring-offset-color: transparent;
        }
    </style>
</x-guest-layout>