<x-layout.default>
    <!-- Main Content -->
    <main class="container mx-auto p-4 md:p-6" x-data="dashboard()">

        <!-- Header dan Statistik Utama -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Penjualan</h1>
            <p class="text-gray-600">Statistik dan analisis data penjualan perusahaan</p>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            
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
                        <h2 class="text-gray-600 text-sm font-semibold">Total Pengeluaran</h2>
                        <div class="text-xl font-bold text-red-600" id="estimatedExpenses">
                            RP {{ number_format($estimatedExpenses, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

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

             <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600 text-sm font-semibold">Total Saldo</h2>
                        <div class="text-xl font-bold text-blue-600" id="totalProfit">
                            RP {{ number_format($totalBalance, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pesanan Selesai -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-violet-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-violet-100">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600">Total Pesanan</p>
                        <p class="text-2xl font-bold text-gray-800" id="totalOrdersCompleted">
                            {{ number_format($totalOrdersCompleted, 0, ',', '.') }} pesanan
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
            <!-- Grafik Perbandingan Pendapatan & Pengeluaran -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-gray-800 text-xl font-semibold mb-4">Perbandingan Pendapatan & Pengeluaran Tahunan</h2>
                <div class="h-80">
                    <canvas id="profitExpenseChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart Terpisah untuk Pembelian dan Pesanan -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
            <!-- Chart Pembelian vs Pesanan -->
            <div class="bg-white rounded-lg shadow p-6 lg:col-span-3">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Pembelian vs Pesanan</h3>
                <div class="h-80">
                    <canvas id="purchaseOrderChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Distribusi Pesanan Layanan</h3>
                <div class="h-80 relative">
                    <canvas id="orderDonutChart"></canvas>
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
                const combinedMonthlyProfit = @json($combinedMonthlyProfit);
                const combinedMonthlyExpenses = @json($combinedMonthlyExpenses);
                const bestSellingProducts = @json($bestSellingProducts);
                const recentTransactions = @json($recentTransactions);

                // Bulan dalam format Indonesia
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                ];

                // Chart 1: Perbandingan Pendapatan & Pengeluaran
                const profitExpenseCtx = document.getElementById('profitExpenseChart');
                if (profitExpenseCtx) {
                    // Cek jika chart sudah ada, destroy dulu
                    if (profitExpenseCtx.chart) {
                        profitExpenseCtx.chart.destroy();
                    }

                    const incomeData = new Array(12).fill(0);
                    const expenseData = new Array(12).fill(0);

                    // Process profit data dari combinedMonthlyProfit
                    if (combinedMonthlyProfit && Array.isArray(combinedMonthlyProfit)) {
                        console.log('Profit Data:', combinedMonthlyProfit);
                        combinedMonthlyProfit.forEach(item => {
                            if (item && item.month !== undefined && item.month !== null) {
                                const monthIndex = parseInt(item.month) - 1;
                                if (monthIndex >= 0 && monthIndex < 12) {
                                    incomeData[monthIndex] = parseFloat(item.total_profit) || 0;
                                }
                            }
                        });
                    }

                    // Process expense data dari combinedMonthlyExpenses
                    if (combinedMonthlyExpenses && Array.isArray(combinedMonthlyExpenses)) {
                        console.log('Expense Data:', combinedMonthlyExpenses);
                        combinedMonthlyExpenses.forEach(item => {
                            if (item && item.month !== undefined && item.month !== null) {
                                const monthIndex = parseInt(item.month) - 1;
                                if (monthIndex >= 0 && monthIndex < 12) {
                                    expenseData[monthIndex] = parseFloat(item.total_expense) || 0;
                                }
                            }
                        });
                    }

                    console.log('Final Income:', incomeData);
                    console.log('Final Expense:', expenseData);

                    // Buat chart baru
                    profitExpenseCtx.chart = new Chart(profitExpenseCtx, {
                        type: 'line',
                        data: {
                            labels: monthNames,
                            datasets: [{
                                    label: 'Pendapatan (Rp)',
                                    data: incomeData,
                                    borderColor: '#10B981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4
                                },
                                {
                                    label: 'Pengeluaran (Rp)',
                                    data: expenseData,
                                    borderColor: '#EF4444',
                                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                    borderWidth: 3,
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

                // Chart 2: Purchase vs Order (Bar Chart)
                const purchaseOrderCtx = document.getElementById('purchaseOrderChart');
                if (purchaseOrderCtx) {
                    // Cek jika chart sudah ada, destroy dulu
                    if (purchaseOrderCtx.chart) {
                        purchaseOrderCtx.chart.destroy();
                    }

                    // Hitung jumlah transaksi per bulan berdasarkan type
                    const purchaseData = new Array(12).fill(0);
                    const orderData = new Array(12).fill(0);

                    if (recentTransactions && Array.isArray(recentTransactions)) {
                        recentTransactions.forEach(transaction => {
                            if (transaction.created_at) {
                                const month = new Date(transaction.created_at).getMonth();
                                if (transaction.type === 'purchase') {
                                    purchaseData[month] += 1;
                                } else {
                                    orderData[month] += 1;
                                }
                            }
                        });
                    }

                    purchaseOrderCtx.chart = new Chart(purchaseOrderCtx, {
                        type: 'bar',
                        data: {
                            labels: monthNames,
                            datasets: [{
                                    label: 'Pembelian Produk',
                                    data: purchaseData,
                                    backgroundColor: '#3B82F6',
                                    borderColor: '#2563EB',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Pesanan Layanan',
                                    data: orderData,
                                    backgroundColor: '#10B981',
                                    borderColor: '#059669',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value + ' transaksi';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Donut Chart: Distribusi Pesanan Layanan
                const orderDonutCtx = document.getElementById('orderDonutChart');
                if (orderDonutCtx) {
                    // Cek jika chart sudah ada, destroy dulu
                    if (orderDonutCtx.chart) {
                        orderDonutCtx.chart.destroy();
                    }

                    // Hitung distribusi layanan dari recent transactions
                    const serviceDistribution = {};
                    if (recentTransactions && Array.isArray(recentTransactions)) {
                        recentTransactions.forEach(transaction => {
                            if (transaction.type !== 'purchase') {
                                const serviceName = transaction.name;
                                if (!serviceDistribution[serviceName]) {
                                    serviceDistribution[serviceName] = 0;
                                }
                                serviceDistribution[serviceName] += 1;
                            }
                        });
                    }

                    const serviceNames = Object.keys(serviceDistribution);
                    const serviceCounts = Object.values(serviceDistribution);
                    const totalServices = serviceCounts.reduce((sum, count) => sum + count, 0);

                    // Jika tidak ada data layanan, tampilkan placeholder
                    if (serviceNames.length === 0) {
                        serviceNames.push('Belum ada data');
                        serviceCounts.push(1);
                    }

                    orderDonutCtx.chart = new Chart(orderDonutCtx, {
                        type: 'doughnut',
                        data: {
                            labels: serviceNames,
                            datasets: [{
                                data: serviceCounts,
                                backgroundColor: ['#10B981', '#059669', '#047857', '#065F46', '#064E3B'],
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
        const dashboardElement = document.querySelector('[x-data="dashboard()"]');
        if (dashboardElement) {
            const dashboardInstance = Alpine.evaluate(dashboardElement, 'dashboard()');
            dashboardInstance.init();
        }
    });
</script>
</x-layout.default>
