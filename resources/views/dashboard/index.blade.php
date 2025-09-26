<x-layout.default>
    <!DOCTYPE html>
    <!-- Main Content -->
    <main class="container mx-auto p-4 md:p-6" x-data="dashboard()" x-init="init()">
        <!-- Header dan Statistik Utama -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Penjualan</h1>
            <p class="text-gray-600">Statistik dan analisis data penjualan perusahaan</p>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Pendapatan -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm font-semibold">Total Pendapatan</h2>
                        <div class="text-xl font-bold text-green-600" id="totalProfit">
                            RP {{ number_format($totalProfit, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estimasi Pengeluaran -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm font-semibold">Estimasi Pengeluaran</h2>
                        <div class="text-xl font-bold text-red-600" id="estimatedExpenses">
                            RP {{ number_format($estimatedExpenses, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Produk Terjual -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm font-semibold">Total Produk Terjual</h2>
                        <div class="text-xl font-bold text-blue-600" id="totalProductsSold">
                            {{ number_format($totalProductsSold, 0, ',', '.') }} item
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pesanan Selesai -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600">Pesanan Selesai</p>
                        <p class="text-2xl font-bold text-gray-800" id="totalOrdersCompleted">
                            {{ number_format($totalOrdersCompleted, 0, ',', '.') }} pesanan
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Grafik Perbandingan Pendapatan & Pengeluaran -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h2 class="text-gray-800 text-xl font-semibold mb-4">Perbandingan Pendapatan & Pengeluaran Tahunan</h2>
                <div class="h-80">
                    <canvas id="profitExpenseChart"></canvas>
                </div>
            </div>

            <!-- Chart Pembelian vs Pesanan -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Pembelian vs Pesanan</h3>
                <div class="h-80">
                    <canvas id="purchaseOrderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart Terpisah untuk Pembelian dan Pesanan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Donut Chart Pembelian (Produk) -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Distribusi Pembelian Produk</h3>
                <div class="h-80 relative py-8">
                    <canvas id="purchaseDonutChart"></canvas>
                    <div class="donut-center">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-800" id="purchaseDonutTotal">
                                {{ number_format($totalProductsSold, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-600">Total Item Terjual</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donut Chart Pesanan (Layanan) -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Distribusi Pesanan Layanan</h3>
                <div class="h-80 relative py-8">
                    <canvas id="orderDonutChart"></canvas><div class="donut-center">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800" id="orderDonutTotal">
                                    {{ number_format($totalOrdersCompleted, 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-600">Total Pesanan</div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <!-- Tabel dan Daftar Produk -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Transaksi Terbaru -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-gray-800 text-xl font-semibold mb-4">Transaksi Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentTransactions as $transaction)
                                <tr class="border-b">
                                    <td class="px-4 py-2 text-sm">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full 
                                            {{ $transaction['type'] === 'purchase' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $transaction['type'] === 'purchase' ? 'Produk' : 'Layanan' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm">{{ $transaction['name'] }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $transaction['quantity'] }}</td>
                                    <td class="px-4 py-2 text-sm">RP
                                        {{ number_format($transaction['total_price'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        {{ $transaction['created_at']->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Produk & Layanan Terpopuler -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-gray-800 text-xl font-semibold mb-4">Produk & Layanan Terpopuler</h2>

                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-700 mb-3">Produk Terlaris</h3>
                    <div class="space-y-3">
                        @foreach ($bestSellingProducts as $product)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <span class="font-medium text-sm">{{ $product->name }}</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $product->total_sold ?: 0 }} terjual
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-md font-semibold text-gray-700 mb-3">Layanan Terpopuler</h3>
                    <div class="space-y-3">
                        @php
                            // Data layanan dari recent transactions
                            $serviceTransactions = array_filter($recentTransactions->toArray(), function (
                                $transaction,
                            ) {
                                return $transaction['type'] !== 'purchase';
                            });

                            $popularServices = [];
                            foreach ($serviceTransactions as $transaction) {
                                $serviceName = $transaction['name'];
                                if (!isset($popularServices[$serviceName])) {
                                    $popularServices[$serviceName] = 0;
                                }
                                $popularServices[$serviceName] += $transaction['quantity'];
                            }

                            // Urutkan berdasarkan jumlah terbanyak
                            arsort($popularServices);
                            $popularServices = array_slice($popularServices, 0, 4);
                        @endphp

                        @foreach ($popularServices as $serviceName => $count)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <span class="font-medium text-sm">{{ $serviceName }}</span>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $count }} pesanan
                                </span>
                            </div>
                        @endforeach

                        @if (count($popularServices) === 0)
                            <div class="text-center text-gray-500 py-4">
                                Belum ada data layanan
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function dashboard() {
            return {
                init() {
                    this.initCharts();
                },

                initCharts() {
                    // Data dari controller
                    const monthlyProfitData = @json($combinedMonthlyProfit);
                    const monthlyExpenseData = @json($combinedMonthlyExpenses);
                    const bestSellingProducts = @json($bestSellingProducts);
                    const recentTransactions = @json($recentTransactions);

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

                    // Chart 1: Perbandingan Pendapatan & Pengeluaran
                    const profitExpenseCtx = document.getElementById('profitExpenseChart').getContext('2d');
                    if (profitExpenseCtx) {
                        const incomeData = new Array(12).fill(0);
                        monthlyProfitData.forEach(item => {
                            if (item && item.month !== undefined) {
                                incomeData[item.month - 1] = item.total_profit || 0;
                            }
                        });

                        const expenseData = new Array(12).fill(0);
                        monthlyExpenseData.forEach(item => {
                            if (item && item.month !== undefined) {
                                expenseData[item.month - 1] = item.total_expense || 0;
                            }
                        });

                        new Chart(profitExpenseCtx, {
                            type: 'line',
                            data: {
                                labels: monthNames,
                                datasets: [{
                                        label: 'Pendapatan (Rp)',
                                        data: incomeData,
                                        borderColor: '#10B981',
                                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4
                                    },
                                    {
                                        label: 'Pengeluaran (Rp)',
                                        data: expenseData,
                                        borderColor: '#EF4444',
                                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.dataset.label + ': Rp ' + context.raw
                                                    .toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Chart 2: Perbandingan Pembelian vs Pesanan (Bar Chart)
                    const purchaseOrderCtx = document.getElementById('purchaseOrderChart').getContext('2d');
                    if (purchaseOrderCtx) {
                        // Hitung jumlah pembelian (produk) dan pesanan (layanan) per bulan dari recent transactions
                        const purchaseData = new Array(12).fill(0);
                        const orderData = new Array(12).fill(0);

                        recentTransactions.forEach(transaction => {
                            const month = new Date(transaction.created_at).getMonth();
                            if (transaction.type === 'purchase') {
                                purchaseData[month] += transaction.quantity;
                            } else {
                                orderData[month] += 1; // Untuk layanan, hitung per pesanan
                            }
                        });

                        new Chart(purchaseOrderCtx, {
                            type: 'bar',
                            data: {
                                labels: monthNames,
                                datasets: [{
                                        label: 'Pembelian (Produk)',
                                        data: purchaseData,
                                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                                        borderColor: 'rgba(59, 130, 246, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Pesanan (Layanan)',
                                        data: orderData,
                                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                                        borderColor: 'rgba(16, 185, 129, 1)',
                                        borderWidth: 1
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
                                                return value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const label = context.dataset.label;
                                                const value = context.raw;
                                                if (label === 'Pembelian (Produk)') {
                                                    return `${label}: ${value} item`;
                                                } else {
                                                    return `${label}: ${value} pesanan`;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Donut Chart 1: Pembelian (Produk)
                    const purchaseDonutCtx = document.getElementById('purchaseDonutChart');
                    if (purchaseDonutCtx) {
                        const topProducts = bestSellingProducts.slice(0, 6);
                        const productNames = topProducts.map(item => item.name || 'Produk Tidak Dikenal');
                        const quantitiesSold = topProducts.map(item => item.total_sold || 0);
                        const totalSold = quantitiesSold.reduce((sum, quantity) => sum + quantity, 0);

                        new Chart(purchaseDonutCtx, {
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
                                cutout: '65%',
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            boxWidth: 12,
                                            font: {
                                                size: 10
                                            }
                                        }
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

                    // Donut Chart 2: Pesanan (Layanan)
                    const orderDonutCtx = document.getElementById('orderDonutChart');
                    if (orderDonutCtx) {
                        // Hitung distribusi layanan dari recent transactions
                        const serviceDistribution = {};
                        recentTransactions.forEach(transaction => {
                            if (transaction.type !== 'purchase') {
                                const serviceName = transaction.name;
                                if (!serviceDistribution[serviceName]) {
                                    serviceDistribution[serviceName] = 0;
                                }
                                serviceDistribution[serviceName] += 1;
                            }
                        });

                        const serviceNames = Object.keys(serviceDistribution);
                        const serviceCounts = Object.values(serviceDistribution);
                        const totalServices = serviceCounts.reduce((sum, count) => sum + count, 0);

                        // Jika tidak ada data layanan, tampilkan chart kosong
                        if (serviceNames.length === 0) {
                            serviceNames.push('Belum ada data');
                            serviceCounts.push(1);
                        }

                        new Chart(orderDonutCtx, {
                            type: 'doughnut',
                            data: {
                                labels: serviceNames,
                                datasets: [{
                                    data: serviceCounts,
                                    backgroundColor: ['#10B981', '#059669', '#047857', '#065F46', '#064E3B',
                                        '#022C22'
                                    ],
                                    borderColor: '#ffffff',
                                    borderWidth: 2,
                                    hoverOffset: 15
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            boxWidth: 12,
                                            font: {
                                                size: 10
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const label = context.label || '';
                                                const value = context.raw;
                                                const percentage = totalServices > 0 ? ((value /
                                                    totalServices) * 100).toFixed(1) : 0;
                                                return `${label}: ${value} pesanan (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            }
        }

        // Inisialisasi dashboard saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard initialized with server data');
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
