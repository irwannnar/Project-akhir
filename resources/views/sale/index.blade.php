<x-layout.default>
    <div class="container mx-auto p-4 md:p-6">
        <!-- Header dan Statistik Utama -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Penjualan</h1>
            <p class="text-gray-600">Statistik dan analisis data penjualan perusahaan</p>
            
            <!-- Statistik Tahun Ini -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600">Total Penjualan Tahun Ini</p>
                            <p class="text-xl font-bold text-gray-800">{{ number_format($totalSales, 0, ',', '.') }} item</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
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

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600">Total Profit</p>
                            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600">Produk Terlaris</p>
                            <p class="text-xl font-bold text-gray-800">
                                @if($bestSellingProduct && $bestSellingProduct->product)
                                    {{ $bestSellingProduct->product->name }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
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

            <!-- Grafik Pendapatan dan Profit -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Pendapatan & Profit</h3>
                <div class="h-80">
                    <canvas id="revenueProfitChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistik Produk -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Statistik Produk</h2>
                <a href="{{ route('sale.product') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                    Lihat Detail Produk
                </a>
            </div>

            <!-- Card Statistik Produk -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600">Total Produk Terjual</p>
                            <p class="text-2xl font-bold text-gray-800">
                                @php
                                    $totalProductSold = $productSales->sum('total_sold');
                                @endphp
                                {{ number_format($totalProductSold) }} item
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
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
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($productSales->count()) }} produk</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600">Total Pendapatan Produk</p>
                            <p class="text-2xl font-bold text-gray-800">
                                @php
                                    $totalProductRevenue = $productSales->sum('total_revenue');
                                @endphp
                                Rp {{ number_format($totalProductRevenue, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Perbandingan Produk -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Top 8 Produk Terlaris</h3>
                <div class="h-96">
                    <canvas id="productComparisonChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Data Penjualan Bulanan -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Data Penjualan Bulanan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penjualan (item)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Profit</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($formattedSales as $sale)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ DateTime::createFromFormat('!m', $sale['month'])->format('F') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($sale['total_sales'], 0, ',', '.') }} item
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                Rp {{ number_format($sale['total_revenue'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                Rp {{ number_format($sale['total_profit'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Data Produk Terlaris -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-800 text-xl font-semibold mb-4">Data Penjualan per Produk</h3>

            @if ($productSales->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Terjual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($productSales->take(10) as $index => $sale)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($sale->product)
                                            {{ $sale->product->name }}
                                        @else
                                            <span class="text-gray-400">Produk tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($sale->total_sold) }} item</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($sale->total_revenue, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ number_format(($sale->total_sold / $totalProductSold) * 100, 2) }}%
                                    </td>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari controller
            const monthlyData = @json($formattedSales);
            const productSalesData = @json($productSales);
            
            // Bulan dalam format Indonesia
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

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
                        datasets: [
                            {
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

            // Grafik Perbandingan Produk
            const productCtx = document.getElementById('productComparisonChart');
            if (productCtx) {
                const topProducts = productSalesData.slice(0, 8); // Ambil 8 produk teratas
                const productNames = topProducts.map(item => item.product ? item.product.name : 'Produk Tidak Dikenal');
                const quantitiesSold = topProducts.map(item => item.total_sold);

                new Chart(productCtx, {
                    type: 'bar',
                    data: {
                        labels: productNames,
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: quantitiesSold,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
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
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
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
    </style>
</x-layout.default>