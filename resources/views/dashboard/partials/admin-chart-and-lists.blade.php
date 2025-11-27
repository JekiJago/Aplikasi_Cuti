@php
    $monthlyStats      = $monthlyStats      ?? collect();
    $recentLeaves      = $recentLeaves      ?? collect();
    $lowLeaveEmployees = $lowLeaveEmployees ?? collect();
@endphp

{{-- =======================
     GRAFIK PENGAJUAN CUTI
======================= --}}
<section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
    <p class="text-sm font-semibold text-slate-900 mb-1">
        Statistik Pengajuan Cuti per Bulan
    </p>
    <p class="text-xs text-slate-500 mb-4">
        Jumlah pengajuan cuti yang disetujui, ditolak, dan masih pending sepanjang tahun berjalan.
    </p>

    <div class="h-64">
        <canvas id="leaveChart"></canvas>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthlyStats = @json($monthlyStats);

        const labels        = monthlyStats.map(m => m.label);
        const approvedData  = monthlyStats.map(m => m.approved);
        const rejectedData  = monthlyStats.map(m => m.rejected);
        const pendingData   = monthlyStats.map(m => m.pending);

        const ctx = document.getElementById('leaveChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Disetujui',
                        data: approvedData,
                        backgroundColor: 'rgba(16, 185, 129, 0.9)',   // hijau
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.6,
                    },
                    {
                        label: 'Ditolak',
                        data: rejectedData,
                        backgroundColor: 'rgba(248, 113, 113, 0.9)',  // merah
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.6,
                    },
                    {
                        label: 'Pending',
                        data: pendingData,
                        backgroundColor: 'rgba(251, 191, 36, 0.9)',   // kuning
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                // judul tooltip → nama bulan
                                return context[0].label;
                            },
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: false,
                        grid: {
                            display: false,
                        },
                        ticks: {
                            font: {
                                size: 11,
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.2)',
                        },
                        ticks: {
                            precision: 0,
                            stepSize: 1,
                            font: {
                                size: 11,
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

{{-- =======================
   PANEL BAWAH: PENGAJUAN TERBARU + SIS C CUTI RENDAH
======================= --}}
<section class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Pengajuan Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <p class="text-sm font-semibold text-slate-900 mb-1 flex items-center">
            <span class="mr-2">📅</span> Pengajuan Terbaru
        </p>
        <p class="text-xs text-slate-500 mb-3">
            Daftar pengajuan cuti yang baru masuk ke sistem.
        </p>

        <div class="space-y-3">
            @forelse($recentLeaves as $leave)
                @php
                    $statusColor = match($leave->status) {
                        'approved' => 'bg-emerald-50 text-emerald-700',
                        'pending'  => 'bg-amber-50 text-amber-700',
                        'rejected' => 'bg-rose-50 text-rose-700',
                        default    => 'bg-slate-50 text-slate-700',
                    };
                @endphp
                <div class="flex items-start justify-between rounded-xl border border-slate-100 px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-900">
                            {{ $leave->user->name }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}
                            &middot;
                            {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M') }}
                        </p>
                        <p class="mt-1 text-[11px] text-slate-400 line-clamp-1">
                            {{ $leave->reason }}
                        </p>
                    </div>
                    <div class="text-right ml-3">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium {{ $statusColor }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                        <p class="mt-1 text-[11px] text-slate-400">
                            {{ $leave->days }} hari
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400">
                    Belum ada pengajuan cuti terbaru.
                </p>
            @endforelse
        </div>
    </div>

    {{-- Pegawai Sisa Cuti Rendah --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <p class="text-sm font-semibold text-slate-900 mb-1 flex items-center">
            <span class="mr-2">👥</span> Pegawai Sisa Cuti Rendah
        </p>
        <p class="text-xs text-slate-500 mb-3">
            Pegawai dengan sisa cuti yang hampir habis.
        </p>

        <div class="space-y-3">
            @forelse($lowLeaveEmployees as $emp)
                @php
                    $quota     = $emp->annual_leave_quota_current ?? ($emp->annual_leave_quota ?? 0);
                    $used      = $emp->current_year_used ?? 0;
                    $remaining = $emp->remaining_leave_days ?? max(0, $quota - $used);
                    $percent   = $quota > 0 ? ($used / $quota) * 100 : 0;
                @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $emp->name }}</p>
                            <p class="text-xs text-slate-500">{{ $emp->employee_id }}</p>
                        </div>
                        <span class="inline-flex px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[11px] font-medium">
                            {{ $remaining }} hari
                        </span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full bg-slate-900" style="width: {{ min(100, $percent) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400">
                    Belum ada data pegawai dengan sisa cuti rendah.
                </p>
            @endforelse
        </div>
    </div>

</section>
