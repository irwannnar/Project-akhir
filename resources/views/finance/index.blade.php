<x-layout.default>
    <!-- Header -->
    <header class="mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    Finance Dashboard
                </h1>
                <div class="flex space-x-3">
                    <a href="{{ route('finance.report') }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-file-pdf mr-2"></i>Laporan
                    </a>
                    <form action="{{ route('finance.syncBalance') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-sync-alt mr-2"></i>Sync Balance
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Balance Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Balance -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Balance</p>
                        <p class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($finance->balance, 0, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-wallet text-blue-500 text-2xl"></i>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Saldo awal: Rp {{ number_format($finance->initial_balance, 0, ',', '.') }}
                </div>
            </div>

            <!-- Total Income -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($finance->total_income, 0, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-arrow-up text-green-500 text-2xl"></i>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    {{ $recentTransactions->count() }} transaksi completed
                </div>
            </div>

            <!-- Total Expense -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($finance->total_expense, 0, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-arrow-down text-red-500 text-2xl"></i>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    {{ $recentSpendings->count() }} pengeluaran
                </div>
            </div>

            <!-- Net Profit -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Net Profit</p>
                        <p class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($finance->total_income - $finance->total_expense, 0, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-chart-bar text-purple-500 text-2xl"></i>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Pendapatan bersih
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Weekly Chart -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Aktivitas 7 Hari Terakhir</h3>
                <canvas id="weeklyChart" height="200"></canvas>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('finance.edit', $finance) }}"
                        class="w-full bg-blue-100 text-blue-700 px-4 py-3 rounded-lg flex items-center hover:bg-blue-200 transition">
                        <i class="fas fa-edit mr-3"></i>
                        <span>Edit Saldo Awal</span>
                    </a>
                    <a href="{{ route('finance.transactions') }}"
                        class="w-full bg-green-100 text-green-700 px-4 py-3 rounded-lg flex items-center hover:bg-green-200 transition">
                        <i class="fas fa-list mr-3"></i>
                        <span>Lihat Semua Transaksi</span>
                    </a>
                    <a href="{{ route('finance.report') }}"
                        class="w-full bg-purple-100 text-purple-700 px-4 py-3 rounded-lg flex items-center hover:bg-purple-200 transition">
                        <i class="fas fa-chart-pie mr-3"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Transactions and Spendings -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Transactions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-shopping-cart text-green-600 mr-2"></i>
                        Transaksi Terbaru
                    </h3>
                </div>
                <div class="divide-y">
                    @forelse($recentTransactions as $transaction)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $transaction->customer_name }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $transaction->product->name ?? 'Product' }} •
                                        {{ $transaction->quantity }} pcs
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $transaction->paid_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-medium">
                                    +Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-receipt text-3xl mb-2"></i>
                            <p>Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Spendings -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-money-bill-wave text-red-600 mr-2"></i>
                        Pengeluaran Terbaru
                    </h3>
                </div>
                <div class="divide-y">
                    @forelse($recentSpendings as $spending)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $spending->name }}</p>
                                    <p class="text-sm text-gray-600 capitalize">
                                        {{ $spending->category }} •
                                        {{ $spending->quantity }} pcs
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $spending->spending_date->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">
                                    -Rp {{ number_format($spending->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-receipt text-3xl mb-2"></i>
                            <p>Belum ada pengeluaran</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript untuk Chart -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Weekly Chart
            const ctx = document.getElementById('weeklyChart').getContext('2d');
            const weeklyData = @json($weeklyData);

            const dates = weeklyData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', {
                    weekday: 'short',
                    day: 'numeric'
                });
            });

            const incomeData = weeklyData.map(item => item.income);
            const expenseData = weeklyData.map(item => item.expense);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                            label: 'Pendapatan',
                            data: incomeData,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Pengeluaran',
                            data: expenseData,
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' +
                                        context.parsed.y.toLocaleString('id-ID');
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
        });
    </script>
</x-layout.default>
