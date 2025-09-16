<x-layout.default>
    <div class="container mx-auto p-6" x-data="dashboard()" x-init="init()">

        <div class="mb-6">
            <p class="text-lg font-semibold">Statistik Tahun {{ date('Y') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Grafik -->
            <div class="lg:col-span-3 bg-white rounded-lg shadow p-6">
                <h2 class="text-gray-800 text-xl font-semibold mb-4">Grafik Pendapatan Tahunan</h2>
                <div class="h-80">
                    <canvas id="profitChart"></canvas>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                <div class="bg-white rounded-lg shadow p-6">
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
                            <div class="text-xl font-bold text-green-600">
                                RP {{ number_format($totalProfit, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
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
                            <div class="text-xl font-bold text-blue-600">
                                {{ number_format($totalProductsSold, 0, ',', '.') }} item
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm font-semibold">Total Pesanan Selesai</h2>
                            <div class="text-xl font-bold text-purple-600">
                                {{ number_format($totalOrdersCompleted, 0, ',', '.') }} pesanan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Transaksi Terbaru (Gabungan Purchase dan Order) -->
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
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $transaction['type'] === 'purchase' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $transaction['type'] === 'purchase' ? 'Produk' : 'Layanan' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm">{{ $transaction['name'] }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $transaction['quantity'] }}</td>
                                    <td class="px-4 py-2 text-sm">RP {{ number_format($transaction['total_price'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $transaction['created_at']->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Produk Terlaris dan Layanan Populer -->
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
            </div>
        </div>

        <script>
            function dashboard() {
                return {
                    init() {
                        this.initChart();
                    },

                    initChart() {
                        const ctx = document.getElementById('profitChart').getContext('2d');
                        const monthlyData = @json($combinedMonthlyProfit);

                        // Siapkan data untuk chart
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        const profitData = new Array(12).fill(0);

                        monthlyData.forEach(item => {
                            if (item && item.month !== undefined) {
                                profitData[item.month - 1] = item.total_profit || 0;
                            }
                        });

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: months,
                                datasets: [{
                                    label: 'Pendapatan (Rp)',
                                    data: profitData,
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
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return 'Rp ' + context.raw.toLocaleString('id-ID');
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
                }
            }
        </script>
    </div>
</x-layout.default>