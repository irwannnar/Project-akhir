<x-layout.default>
    <div class="container mx-auto p-6" x-data="sale()" x-init="init()">
        <div class="mb-6">
            <p class="text-lg font-semibold">Statistik Penjualan Tahunan {{ date('Y') }}</p>
        </div>

        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-gray-800 text-xl font-semibold mb-4">Grafik Penjualan Tahunan</h3>
            <div class="h-80">
                <canvas id="saleChart"></canvas>
            </div>
        </div>

        <!-- Perbaikan layout grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Grafik - 3 kolom -->
            <div class="bg-white rounded-lg shadow p-6 lg:col-span-3">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Perbandingan Produk Terjual</h3>
                <div class="h-60">
                    <canvas id="productComparisonChart"></canvas>
                </div>
            </div>

            <!-- Statistik Cards - 1 kolom -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Penjualan</p>
                            <p class="text-sm font-bold text-gray-800">{{ number_format($totalSales) }} item</p>
                        </div>
                    </div>
                </div>

                <!-- Card untuk produk terlaris -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Produk Terlaris</p>
                            <p class="text-sm font-bold text-gray-800">
                                @if ($productSales->count() > 0)
                                    {{ $productSales->first()->product->name }}
                                    ({{ number_format($productSales->first()->total_sold) }} item)
                                @else
                                    Tidak ada data penjualan
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card untuk total produk -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Produk Terjual</p>
                            <p class="text-sm font-bold text-gray-800">{{ number_format($totalSales) }} item</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Produk -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-gray-800 text-xl font-semibold mb-4">Data Penjualan per Produk</h3>

            @if ($productSales->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipe</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah Terjual</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga/Unit</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($productSales as $sale)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $sale->product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $sale->product->type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($sale->total_sold) }} item
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp
                                        {{ number_format($sale->product->price_per_unit, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    this.initProductComparisonChart();
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
                },

                initProductComparisonChart() {
                    const ctx = document.getElementById('productComparisonChart');
                    if (!ctx) {
                        console.error('Element productComparisonChart tidak ditemukan');
                        return;
                    }

                    // Gunakan data penjualan produk dari backend
                    const productSalesData = @json($productSales);

                    const productNames = productSalesData.map(item => item.product ? item.product.name :
                        'Produk Tidak Dikenal');
                    const quantitiesSold = productSalesData.map(item => item.total_sold);

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: productNames,
                            datasets: [{
                                label: 'Jumlah Terjual',
                                data: quantitiesSold,
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(255, 206, 86, 0.7)',
                                    'rgba(75, 192, 192, 0.7)',
                                    'rgba(153, 102, 255, 0.7)',
                                    'rgba(255, 159, 64, 0.7)',
                                    'rgba(199, 199, 199, 0.7)',
                                    'rgba(83, 102, 255, 0.7)',
                                    'rgba(40, 159, 64, 0.7)',
                                    'rgba(210, 99, 132, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(199, 199, 199, 1)',
                                    'rgba(83, 102, 255, 1)',
                                    'rgba(40, 159, 64, 1)',
                                    'rgba(210, 99, 132, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Terjual: ' + context.raw.toLocaleString('id-ID') + ' item';
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
