<h2 class="text-lg font-semibold text-gray-800 mb-6">Riwayat Pengajuan Cuti</h2>

@forelse($leaves as $leave)
    @php
        $statusConfig = [
            'pending' => [
                'bg' => 'bg-amber-50', 
                'text' => 'text-amber-800',
                'border' => 'border-amber-200',
                'icon' => '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'label' => 'Menunggu'
            ],
            'approved' => [
                'bg' => 'bg-emerald-50', 
                'text' => 'text-emerald-800',
                'border' => 'border-emerald-200',
                'icon' => '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                'label' => 'Disetujui'
            ],
            'rejected' => [
                'bg' => 'bg-red-50', 
                'text' => 'text-red-800',
                'border' => 'border-red-200',
                'icon' => '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                'label' => 'Ditolak'
            ]
        ][$leave->status] ?? [
            'bg' => 'bg-gray-50', 
            'text' => 'text-gray-800',
            'border' => 'border-gray-200',
            'icon' => '',
            'label' => ucfirst($leave->status)
        ];
    @endphp
    
    <div class="rounded-xl border {{ $statusConfig['border'] }} p-4 mb-4 hover:shadow-md transition-shadow duration-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Left Section -->
            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                            {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $leave->reason }}</p>
                        
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center">
                                <div class="p-1.5 bg-gray-100 rounded-lg mr-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Periode</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="p-1.5 bg-gray-100 rounded-lg mr-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Durasi</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $leave->days }} hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                        {!! $statusConfig['icon'] !!}
                        {{ $statusConfig['label'] }}
                    </span>
                </div>
                
                <!-- Metadata -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Diajukan: {{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y, H:i') }}
                        </div>
                        @if($leave->status === 'approved' || $leave->status === 'rejected')
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                Diperbarui: {{ \Carbon\Carbon::parse($leave->updated_at)->format('d M Y, H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-12">
        <div class="mx-auto w-20 h-20 text-gray-300 mb-4">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">Belum ada riwayat cuti</p>
        <p class="text-gray-400 text-sm mt-1">Pegawai ini belum pernah mengajukan cuti</p>
    </div>
@endforelse