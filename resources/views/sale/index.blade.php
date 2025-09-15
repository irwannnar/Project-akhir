<x-layout.default>
    <div class="container mx-auto p-6" x-data="sale()" x-init="init()">
        <div class="mb-6 flex justify-between items-center">
            <p class="text-lg font-semibold">Statistik Penjualan Tahunan {{ date('Y') }}</p>
            <a href="{{ route('sale.product') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Lihat Statistik Produk
            </a>
        </div>

        <!-- Grafik Penjualan Tahunan -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-gray-800 text-xl font-semibold mb-4">Grafik Penjualan Tahunan</h3>
            <div class="h-80">
                <canvas id="saleChart"></canvas>
            </div>
        </div>

        <!-- Perbaikan layout grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Statistik Cards - 3 kolom -->
            <div class="lg:col-span-3 bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Statistik Penjualan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600">Total Penjualan</p>
                                <p class="text-xl font-bold text-gray-800">{{ number_format($totalSales) }} item</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600">Total Pendapatan</p>
                                <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-600">Total Keuntungan</p>
                                <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card untuk produk terlaris - 1 kolom -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600">Produk Terlaris</p>
                            <p class="text-lg font-bold text-gray-800">
                                @if (isset($bestSellingProduct) && $bestSellingProduct)
                                    {{ $bestSellingProduct->product->name }}
                                    <span class="block text-sm text-gray-500 mt-1">
                                        {{ number_format($bestSellingProduct->total_sold) }} item
                                    </span>
                                @else
                                    Tidak ada data penjualan
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center">
                        <p class="text-gray-600">Lihat detail lengkap</p>
                        <a href="{{ route('sale.product') }}" class="inline-block mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Statistik Produk &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Produk (Ringkasan) -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-gray-800 text-xl font-semibold">Ringkasan Produk Terjual</h3>
                <a href="{{ route('sale.product') }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                    Lihat detail lengkap &raquo;
                </a>
            </div>
            
            @if (isset($bestSellingProduct) && $bestSellingProduct)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-green-800 font-semibold">Produk Terlaris</p>
                        <p class="text-lg">{{ $bestSellingProduct->product->name }}</p>
                        <p class="text-gray-600">{{ number_format($bestSellingProduct->total_sold) }} item terjual</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-blue-800 font-semibold">Total Produk Terjual</p>
                        <p class="text-lg">{{ number_format($totalSales) }} item</p>
                        <p class="text-gray-600">Tahun {{ date('Y') }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">Tidak ada data penjualan produk.</p>
            @endif
        </div>
    </div>

    <script>
        function sale() {
            return {
                init() {
                    this.initChart();
                },

                initChart() {
                    const ctx = document.getElementById('saleChart');
                    if (!ctx) {
                        console.error('Element saleChart tidak ditemukan');
                        return;
                    }

                    const monthlySaleData = @json($formattedSales);

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
                                            return context.dataset.label + ': ' + context.raw.toLocaleString(
                                                'id-ID') + ' item';
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