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

</section>
