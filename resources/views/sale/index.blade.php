<x-layout.default>
    <div class="container mx-auto p-6" x-data="sale()" x-init="init()">
        <div class="mb-6">
            <p class="text-lg font-semibold">Statistik Penjualan Tahunan {{ date('Y') }}</p>
        </div>

        <!-- Perbaikan layout grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Grafik - 3 kolom -->
            <div class="lg:col-span-3 bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Grafik Penjualan Tahunan</h3>
                <div class="h-80">
                    <canvas id="saleChart"></canvas>
                </div>
            </div>

            <!-- Statistik Cards - 1 kolom -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600">Total Penjualan</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSales) }} item</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sale() {
            return {
                init() {
                    this.initChart();
                },

                initChart() {
                    const ctx = document.getElementById('saleChart').getContext('2d');
                    const monthlySaleData = @json($formattedSales);

                    // Pastikan data ada dan berupa array
                    if (!monthlySaleData || !Array.isArray(monthlySaleData)) {
                        console.error('Data penjualan tidak valid:', monthlySaleData);
                        return;
                    }

                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    const saleData = new Array(12).fill(0);

                    monthlySaleData.forEach(item => {
                        if (item && item.month !== undefined && item.month >= 1 && item.month <= 12) {
                            saleData[item.month - 1] = item.total_sales || 0;
                        }
                    });

                    // Buat grafik
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Penjualan (item)',
                                data: saleData,
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + context.raw.toLocaleString('id-ID') + ' item';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }

        // Pastikan Chart.js sudah dimuat
        if (typeof Chart !== 'undefined') {
            document.addEventListener('DOMContentLoaded', function() {
                const saleApp = sale();
                saleApp.init();
            });
        } else {
            console.error('Chart.js belum dimuat');
        }
    </script>
</x-layout.default>