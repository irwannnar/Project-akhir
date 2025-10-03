<x-layout.default>
    <!-- Main Content -->
    <main class="container mx-auto p-4 md:p-6" x-data="dashboard()">

        <!-- Header dan Statistik Utama -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Penjualan</h1>
            <p class="text-gray-600">Statistik dan analisis data penjualan perusahaan</p>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

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

            {{-- total saldo --}}
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
                        <h2 class="text-gray-600 text-sm font-semibold">Total profit</h2>
                        <div class="text-xl font-bold text-blue-600" id="totalProfit">
                            RP {{ number_format($totalBalance, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
            <!-- Grafik Pendapatan & Pengeluaran (3 kolom) -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow p-6 h-full">
                    <h2 class="text-gray-800 text-xl font-semibold mb-4">Grafik Pendapatan & Pengeluaran Tahunan</h2>
                    <div class="h-80">
                        <canvas id="incomeExpenseChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Card Statistik Pesanan Compact (2 kolom) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6 h-full">
                    <h3 class="text-gray-800 font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 text-violet-600 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        Statistik Pesanan
                    </h3>

                    <div class="space-y-4">
                        <!-- Total -->
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600">Tahun ini</span>
                            <span class="text-lg font-bold text-gray-800">
                                {{ number_format($totalOrdersCompleted, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- bulan ini --}}
                        <div class="flex justify-between items-center p-3 bg-violet-50 rounded-lg">
                            <span class="text-gray-600">Bulan Ini</span>
                            <span class="text-lg font-semibold text-violet-600">
                                {{ number_format($monthlyOrderCompleted, 0, ',', '.') }}
                            </span>
                        </div> 
                        

                        <!-- Minggu Ini -->
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <span class="text-gray-600">Minggu Ini</span>
                            <span class="text-lg font-semibold text-blue-600">
                                {{ number_format($weeklyOrderCompleted, 0, ',', '.') }}
                            </span>
                        </div>
                        <!-- Hari Ini -->
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <span class="text-gray-600">Hari Ini</span>
                            <span class="text-lg font-semibold text-green-600">
                                {{ number_format($dailyOrderCompleted, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Chart Pembelian dan Pesanan -->
        <div class="grid grid-cols-1 gap-6 mb-6">
            <!-- Chart Pembelian vs Pesanan -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Pembelian vs Pesanan (Tahun {{ date('Y') }})
                </h3>
                <div class="h-80">
                    <canvas id="purchaseOrderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel dan Daftar Produk -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6 lg:col-span-1">
                <h3 class="text-gray-800 text-xl font-semibold mb-4">Distribusi Pesanan Layanan (Tahun
                    {{ date('Y') }})</h3>
                <div class="h-80 relative">
                    <canvas id="orderDonutChart"></canvas>
                </div>
            </div>

            <!-- Produk & Layanan Terpopuler -->
            <div class="bg-white rounded-lg shadow p-6 lg:col-span-1">
                <h2 class="text-gray-800 text-xl font-semibold mb-4">Produk & Layanan Terpopuler</h2>

                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-700 mb-3">Produk Terlaris (Keseluruhan)</h3>
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
                    <h3 class="text-md font-semibold text-gray-700 mb-3">Layanan Terpopuler (Tahun {{ date('Y') }})
                    </h3>
                    <div class="space-y-3">
                        @php
                            $popularServices = $serviceDistribution;
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
                    const monthlyPurchaseData = @json($monthlyPurchaseData);
                    const monthlyOrderData = @json($monthlyOrderData);
                    const serviceDistribution = @json($serviceDistribution);
                    const bestSellingProducts = @json($bestSellingProducts);

                    // Bulan dalam format Indonesia
                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ];

                    // Chart 1: Grafik Pendapatan & Pengeluaran (Digabung)
                    const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
                    if (incomeExpenseCtx) {
                        // Cek jika chart sudah ada, destroy dulu
                        if (incomeExpenseCtx.chart) {
                            incomeExpenseCtx.chart.destroy();
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

                        // Buat chart pendapatan & pengeluaran
                        incomeExpenseCtx.chart = new Chart(incomeExpenseCtx, {
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
                                        tension: 0.4,
                                        pointBackgroundColor: '#10B981',
                                        pointBorderColor: '#ffffff',
                                        pointBorderWidth: 2,
                                        pointRadius: 5,
                                        pointHoverRadius: 7,
                                        yAxisID: 'y'
                                    },
                                    {
                                        label: 'Pengeluaran (Rp)',
                                        data: expenseData,
                                        borderColor: '#EF4444',
                                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4,
                                        pointBackgroundColor: '#EF4444',
                                        pointBorderColor: '#ffffff',
                                        pointBorderWidth: 2,
                                        pointRadius: 5,
                                        pointHoverRadius: 7,
                                        yAxisID: 'y'
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
                                        position: 'top',
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                label += 'Rp ' + context.raw.toLocaleString('id-ID');
                                                return label;
                                            }
                                        }
                                    },
                                    // Menambahkan annotation untuk net profit
                                    annotation: {
                                        annotations: {
                                            line1: {
                                                type: 'line',
                                                yMin: 0,
                                                yMax: 0,
                                                borderColor: 'rgb(75, 85, 99)',
                                                borderWidth: 2,
                                                borderDash: [5, 5],
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp ' + value.toLocaleString('id-ID');
                                            }
                                        },
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.1)'
                                        }
                                    },
                                    x: {
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.1)'
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

                        console.log('Purchase Data:', monthlyPurchaseData);
                        console.log('Order Data:', monthlyOrderData);

                        // Pastikan data adalah array numerik
                        const purchaseData = Array.isArray(monthlyPurchaseData) ?
                            monthlyPurchaseData.map(val => parseInt(val) || 0) :
                            new Array(12).fill(0);

                        const orderData = Array.isArray(monthlyOrderData) ?
                            monthlyOrderData.map(val => parseInt(val) || 0) :
                            new Array(12).fill(0);

                        purchaseOrderCtx.chart = new Chart(purchaseOrderCtx, {
                            type: 'bar',
                            data: {
                                labels: monthNames,
                                datasets: [{
                                        label: 'Pembelian Produk',
                                        data: purchaseData,
                                        backgroundColor: '#3B82F6',
                                        borderColor: '#2563EB',
                                        borderWidth: 2,
                                        borderRadius: 4,
                                        borderSkipped: false,
                                    },
                                    {
                                        label: 'Pesanan Layanan',
                                        data: orderData,
                                        backgroundColor: '#10B981',
                                        borderColor: '#059669',
                                        borderWidth: 2,
                                        borderRadius: 4,
                                        borderSkipped: false,
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
                                                return `${context.dataset.label}: ${context.raw} transaksi`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                if (value % 1 === 0) {
                                                    return value + ' transaksi';
                                                }
                                                return '';
                                            },
                                            stepSize: 1
                                        },
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.1)'
                                        }
                                    },
                                    x: {
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.1)'
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Donut Chart: Distribusi Pesanan Layanan - DATA KESELURUHAN
                    const orderDonutCtx = document.getElementById('orderDonutChart');
                    if (orderDonutCtx) {
                        if (orderDonutCtx.chart) {
                            orderDonutCtx.chart.destroy();
                        }

                        console.log('Service Distribution:', serviceDistribution);

                        const serviceNames = Object.keys(serviceDistribution);
                        const serviceCounts = Object.values(serviceDistribution);
                        const totalServices = serviceCounts.reduce((sum, count) => sum + count, 0);

                        // Jika tidak ada data layanan, tampilkan placeholder
                        if (serviceNames.length === 0) {
                            serviceNames.push('Belum ada data');
                            serviceCounts.push(1);
                        }

                        // Warna untuk chart
                        const backgroundColors = [
                            '#10B981', '#059669', '#047857', '#065F46', '#064E3B',
                            '#3B82F6', '#2563EB', '#1D4ED8', '#1E40AF', '#1E3A8A',
                            '#8B5CF6', '#7C3AED', '#6D28D9', '#5B21B6', '#4C1D95'
                        ];

                        orderDonutCtx.chart = new Chart(orderDonutCtx, {
                            type: 'doughnut',
                            data: {
                                labels: serviceNames,
                                datasets: [{
                                    data: serviceCounts,
                                    backgroundColor: backgroundColors.slice(0, serviceNames.length),
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
                                            },
                                            generateLabels: function(chart) {
                                                const data = chart.data;
                                                if (data.labels.length && data.datasets.length) {
                                                    return data.labels.map((label, i) => {
                                                        const value = data.datasets[0].data[i];
                                                        const percentage = totalServices > 0 ?
                                                            ((value / totalServices) * 100).toFixed(1) :
                                                            0;

                                                        return {
                                                            text: `${label} (${percentage}%)`,
                                                            fillStyle: data.datasets[0].backgroundColor[
                                                                i],
                                                            hidden: false,
                                                            index: i
                                                        };
                                                    });
                                                }
                                                return [];
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const label = context.label || '';
                                                const value = context.raw;
                                                const percentage = totalServices > 0 ?
                                                    ((value / totalServices) * 100).toFixed(1) : 0;
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
