<x-layout.default>
    <div class="container mx-auto p-4 md:p-6">
        <!-- Header dan Statistik Utama -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Penjualan</h1>
            <p class="text-gray-600">Statistik dan analisis data penjualan perusahaan</p>
        </div>

        <!-- Grafik Penjualan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Grafik Penjualan Bulanan -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Penjualan Bulanan</h3>
                <div class="h-80">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>

            
            
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
            <!-- Donut Chart Distribusi Produk -->
            <div class="bg-white rounded-lg shadow p-6 grid grid-cols-3">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Distribusi Penjualan Produk</h3>
                <div class="h-80 relative">
                    <canvas id="productDonutChart"></canvas>
                    <div id="donutChartCenter"
                        class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="text-center">
                            @php
                                $totalProductSold = $productSales->sum('total_sold');
                            @endphp
                            <div class="text-2xl font-bold text-gray-800" id="donutTotal">
                                {{ number_format($totalProductSold) }}</div>
                            <div class="text-sm text-gray-600">Total Item</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card Statistik Produk -->
            <div class="bg-white rounded-lg shadow p-6 mb-4 grid grid-cols-1">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600">Jenis Produk</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($productSales->count()) }} produk
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Produk -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Statistik Produk</h2>
                <a href="{{ route('sale.product') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                    Lihat Detail Produk
                </a>
            </div>

            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari controller
            const monthlyData = @json($formattedSales);
            const productSalesData = @json($productSales);

            // Bulan dalam format Indonesia
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            // Warna untuk chart
            const chartColors = [
                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                '#06B6D4', '#84CC16', '#F97316', '#6366F1', '#EC4899',
                '#14B8A6', '#F43F5E', '#8B5CF6', '#06B6D4', '#84CC16'
            ];

            // Grafik Penjualan Bulanan
            const salesCtx = document.getElementById('monthlySalesChart').getContext('2d');
            if (salesCtx) {
                new Chart(salesCtx, {
                    type: 'bar',
                    data: {
                        labels: monthlyData.map(item => monthNames[item.month - 1]),
                        datasets: [{
                            label: 'Total Penjualan (item)',
                            data: monthlyData.map(item => item.total_sales),
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Penjualan: ${context.raw.toLocaleString('id-ID')} item`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Grafik Pendapatan dan Profit
            const revenueCtx = document.getElementById('revenueProfitChart').getContext('2d');
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyData.map(item => monthNames[item.month - 1]),
                        datasets: [{
                                label: 'Pendapatan',
                                data: monthlyData.map(item => item.total_revenue),
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Profit',
                                data: monthlyData.map(item => item.total_profit),
                                borderColor: 'rgba(153, 102, 255, 1)',
                                backgroundColor: 'rgba(153, 102, 255, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw;
                                        const label = context.dataset.label;
                                        return `${label}: Rp ${value.toLocaleString('id-ID')}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Donut Chart Produk
            const donutCtx = document.getElementById('productDonutChart');
            if (donutCtx) {
                const topProducts = productSalesData.slice(0, 8);
                const productNames = topProducts.map(item => item.product ? item.product.name :
                    'Produk Tidak Dikenal');
                const quantitiesSold = topProducts.map(item => item.total_sold);
                const totalSold = quantitiesSold.reduce((sum, quantity) => sum + quantity, 0);

                // Update total di tengah donut
                document.getElementById('donutTotal').textContent = totalSold.toLocaleString('id-ID');

                new Chart(donutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: productNames,
                        datasets: [{
                            data: quantitiesSold,
                            backgroundColor: chartColors.slice(0, topProducts.length),
                            borderColor: '#ffffff',
                            borderWidth: 2,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw;
                                        const percentage = totalSold > 0 ? ((value / totalSold) * 100)
                                            .toFixed(1) : 0;
                                        return `${label}: ${value.toLocaleString('id-ID')} item (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        #donutChartCenter {
            pointer-events: none;
        }
    </style>
</x-layout.default>
