@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Data Pegawai</h1>
                    <p class="text-gray-600 mt-2">Kelola informasi pegawai dan sisa cuti aktif</p>
                    
                    <!-- Stats Summary -->
                    <div class="flex flex-wrap gap-4 mt-4">
                        <div class="flex items-center bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                            <div class="p-2 bg-blue-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Pegawai</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $employees->total() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('admin.employees.create') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-lg 
                          bg-gradient-to-r from-blue-600 to-teal-600 text-white font-semibold 
                          hover:from-blue-700 hover:to-teal-700 hover:shadow-lg 
                          transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pegawai Baru
                </a>
            </div>
        </div>

        <!-- Search Section -->
        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <form action="{{ route('admin.employees.index') }}" method="GET">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg 
                                              bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 
                                              focus:border-blue-500 focus:outline-none transition duration-200"
                                       placeholder="Cari berdasarkan nama atau NIP...">
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-lg 
                                           bg-gradient-to-r from-blue-600 to-teal-600 text-white font-medium 
                                           hover:from-blue-700 hover:to-teal-700 hover:shadow-md 
                                           transition-all duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Cari
                            </button>
                            @if(request('search'))
                            <a href="{{ route('admin.employees.index') }}"
                               class="inline-flex items-center justify-center px-5 py-3 rounded-lg 
                                      bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 
                                      hover:shadow-md transition-all duration-200 border border-gray-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset
                            </a>
                            @endif
                        </div>
                    </div>
                    @if(request('search'))
                    <div class="mt-3 flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Hasil pencarian untuk: <span class="font-semibold ml-1">{{ request('search') }}</span>
                        <span class="mx-2">•</span>
                        Ditemukan: <span class="font-semibold ml-1">{{ $employees->total() }} pegawai</span>
                    </div>
                    @endif
                </form>
            </div>
        </div>

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

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Nama Pegawai
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                    NIP / ID
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Sisa Cuti Aktif
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                    Aksi
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($employees as $employee)
                            @php
                                $remaining = $employee->remaining_leave_days ?? max(0, ($employee->annual_leave_quota ?? 0) - ($employee->used_leave_days ?? 0));
                                $badgeColor = $remaining >= 10 ? 'bg-green-100 text-green-800' : 
                                             ($remaining >= 5 ? 'bg-blue-100 text-blue-800' : 
                                             ($remaining >= 2 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'));
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-teal-100 rounded-full flex items-center justify-center">
                                            @if($employee->gender === 'female')
                                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a5.5 5.5 0 11-11 0 5.5 5.5 0 0111 0z"/>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                            <div class="text-xs text-gray-500 capitalize">{{ $employee->gender === 'male' ? 'Pria' : 'Wanita' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono text-gray-800 bg-gray-50 px-3 py-1 rounded-lg inline-block">
                                        {{ $employee->employee_id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $badgeColor }}">
                                            {{ $remaining }} hari
                                        </span>
                                        @if($remaining <= 3)
                                            <svg class="w-4 h-4 ml-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.employees.show', $employee) }}"
                                           class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 
                                                  text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 
                                                  transition-colors duration-200 text-sm shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                        
                                        <div class="relative">
                                            <button type="button"
                                                    class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 
                                                           text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 
                                                           transition-colors duration-200 text-sm shadow-sm dropdown-toggle">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                </svg>
                                                Lainnya
                                            </button>
                                            <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden dropdown-menu">
                                                <a href="{{ route('admin.employees.edit', $employee) }}"
                                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-100">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit Data
                                                    </div>
                                                </a>
                                                <form action="{{ route('admin.employees.destroy', $employee) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus pegawai ini? Seluruh pengajuan cutinya juga akan dihapus.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Hapus Pegawai
                                                        </div>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="mx-auto w-24 h-24 text-gray-300 mb-4">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    @if(request('search'))
                                        <p class="text-gray-500 font-medium">Tidak ditemukan pegawai dengan kata kunci "{{ request('search') }}"</p>
                                        <p class="text-gray-400 text-sm mt-1">Coba dengan kata kunci lain atau <a href="{{ route('admin.employees.index') }}" class="text-blue-600 hover:text-blue-800">tampilkan semua pegawai</a></p>
                                    @else
                                        <p class="text-gray-500 font-medium">Belum ada data pegawai</p>
                                        <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan pegawai baru</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($employees->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-700">
                            Menampilkan 
                            <span class="font-medium">{{ $employees->firstItem() ?? 0 }}</span>
                            sampai 
                            <span class="font-medium">{{ $employees->lastItem() ?? 0 }}</span>
                            dari 
                            <span class="font-medium">{{ $employees->total() }}</span>
                            pegawai
                        </div>
                        <div class="flex space-x-2">
                            {{ $employees->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown toggle for action menu
        document.querySelectorAll('.dropdown-toggle').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = this.nextElementSibling;
                
                // Hide all other dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.add('hidden');
                    }
                });
                
                // Toggle current dropdown
                dropdown.classList.toggle('hidden');
            });
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        });
        
        // Clear search button functionality
        const searchInput = document.querySelector('input[name="search"]');
        const clearButton = document.querySelector('a[href*="reset"]');
        
        if (searchInput && clearButton) {
            searchInput.addEventListener('input', function() {
                // Update URL with search parameter in real-time (optional)
                // You can implement real-time search here if needed
            });
        }
    });
</script>
@endpush

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Highlight search results */
    .search-highlight {
        background-color: #fff3cd;
        padding: 2px 4px;
        border-radius: 3px;
        font-weight: 600;
    }
</style>
@endsection