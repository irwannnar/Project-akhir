<x-layout.default>
    <div class="py-6" x-data="transactionIndex()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Daftar Transaksi</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Kelola semua transaksi pembelian produk dan pesanan layanan
                </p>
            </div>

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 3000)"
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8" aria-label="Tabs">
                        <button @click="activeTab = 'orders'"
                            :class="activeTab === 'orders' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Pesanan Layanan
                            <span class="bg-blue-100 text-blue-600 text-xs font-medium ml-2 px-2 py-0.5 rounded-full">
                                {{ $orders->total() }}
                            </span>
                        </button>
                        <button @click="activeTab = 'purchases'"
                            :class="activeTab === 'purchases' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Pembelian Produk
                            <span class="bg-green-100 text-green-600 text-xs font-medium ml-2 px-2 py-0.5 rounded-full">
                                {{ $purchases->total() }}
                            </span>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex space-x-3">
                    <a href="{{ route('transaction.create') }}?type=order"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95">
                        + Tambah Pesanan
                    </a>
                    <a href="{{ route('transaction.create') }}?type=purchase"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 active:scale-95">
                        + Tambah Pembelian
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('transaction.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="hidden" name="tab" x-model="activeTab">

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>
                        <select id="status" name="status" x-model="filters.status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Mulai
                        </label>
                        <input type="date" id="start_date" name="start_date" x-model="filters.start_date"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Akhir
                        </label>
                        <input type="date" id="end_date" name="end_date" x-model="filters.end_date"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('transaction.index') }}"
                            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Orders Tab Content -->
            <div x-show="activeTab === 'orders'" class="space-y-6">
                <!-- Orders Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. Transaksi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelanggan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Item
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Harga
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $order->transaction_number }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                            @if ($order->customer_email)
                                                <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->items->sum('quantity') }} item
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusConfig = [
                                                    'pending' => [
                                                        'class' => 'bg-yellow-100 text-yellow-800',
                                                        'label' => 'Pending',
                                                    ],
                                                    'completed' => [
                                                        'class' => 'bg-green-100 text-green-800',
                                                        'label' => 'Completed',
                                                    ],
                                                ];
                                                $config = $statusConfig[$order->status] ?? [
                                                    'class' => 'bg-gray-100 text-gray-800',
                                                    'label' => $order->status,
                                                ];
                                            @endphp
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['class'] }}">
                                                {{ $config['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('transaction.show', $order->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                                    title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                @if ($order->status !== 'completed')
                                                    <a href="{{ route('transaction.edit', $order->id) }}"
                                                        class="text-green-600 hover:text-green-900 transition-colors"
                                                        title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>

                                                    <form
                                                        action="{{ route('transaction.mark-completed', $order->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-900 transition-colors"
                                                            title="Tandai Selesai">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($order->status !== 'completed')
                                                    <form action="{{ route('transaction.destroy', $order->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="confirmDelete(this.form, '{{ $order->customer_name }}')"
                                                            class="text-red-600 hover:text-red-900 transition-colors"
                                                            title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-lg font-medium">Tidak ada data pesanan</p>
                                            <p class="text-sm mt-1">Mulai dengan membuat pesanan baru</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($orders->hasPages())
                        @php
                            $queryOrders = array_merge(request()->except('page'), ['tab' => 'orders']);
                        @endphp
                        <nav class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
                            role="navigation" aria-label="Pagination">
                            <div class="hidden sm:block">
                                <p class="text-sm text-gray-700">
                                    Menampilkan
                                    <span class="font-medium">{{ $orders->firstItem() ?? 0 }}</span>
                                    sampai
                                    <span class="font-medium">{{ $orders->lastItem() ?? 0 }}</span>
                                    dari
                                    <span class="font-medium">{{ $orders->total() }}</span>
                                    hasil
                                </p>
                            </div>

                            <div class="flex-1 flex justify-between sm:justify-end">
                                {{-- sebelumnya --}}
                                <a href="{{ $orders->appends($queryOrders)->previousPageUrl() ?: '#' }}"
                                    class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $orders->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4 7H15C16.8692 7 17.8039 7 18.5 7.40193C18.9561 7.66523 19.3348 8.04394 19.5981 8.49999C20 9.19615 20 10.1308 20 12C20 13.8692 20 14.8038 19.5981 15.5C19.3348 15.9561 18.9561 16.3348 18.5 16.5981C17.8039 17 16.8692 17 15 17H8.00001M4 7L7 4M4 7L7 10"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </a>

                                <div class="hidden md:flex items-center space-x-1 px-2">
                                    @for ($i = 1; $i <= $orders->lastPage(); $i++)
                                        <a href="{{ $orders->appends($queryOrders)->url($i) }}"
                                            class="px-3 py-2 rounded-md text-sm font-medium {{ $orders->currentPage() == $i ? 'bg-blue-600 text-white' : 'text-gray-700 bg-white border border-gray-200 hover:bg-gray-50' }}">
                                            {{ $i }}
                                        </a>
                                    @endfor
                                </div>

                                {{-- selanjutnya --}}
                                <a href="{{ $orders->appends($queryOrders)->nextPageUrl() ?: '#' }}"
                                    class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $orders->hasMorePages() ? '' : 'opacity-50 pointer-events-none' }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20 7H9.00001C7.13077 7 6.19615 7 5.5 7.40193C5.04395 7.66523 4.66524 8.04394 4.40193 8.49999C4 9.19615 4 10.1308 4 12C4 13.8692 4 14.8038 4.40192 15.5C4.66523 15.9561 5.04394 16.3348 5.5 16.5981C6.19615 17 7.13077 17 9 17H16M20 7L17 4M20 7L17 10"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        </nav>
                    @endif
                </div>
            </div>

            <!-- Purchases Tab Content -->
            <div x-show="activeTab === 'purchases'" class="space-y-6">
                <!-- Purchases Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. Transaksi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelanggan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Item
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Harga
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($purchases as $purchase)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $purchase->transaction_number }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $purchase->created_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $purchase->customer_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $purchase->customer_phone }}</div>
                                            @if ($purchase->customer_email)
                                                <div class="text-sm text-gray-500">{{ $purchase->customer_email }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $purchase->items->sum('quantity') }} item
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusConfig = [
                                                    'pending' => [
                                                        'class' => 'bg-yellow-100 text-yellow-800',
                                                        'label' => 'Pending',
                                                    ],
                                                    'completed' => [
                                                        'class' => 'bg-green-100 text-green-800',
                                                        'label' => 'Completed',
                                                    ],
                                                ];
                                                $config = $statusConfig[$purchase->status] ?? [
                                                    'class' => 'bg-gray-100 text-gray-800',
                                                    'label' => $purchase->status,
                                                ];
                                            @endphp
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['class'] }}">
                                                {{ $config['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('transaction.show', $purchase->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                                    title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                @if ($purchase->status !== 'completed')
                                                    <a href="{{ route('transaction.edit', $purchase->id) }}"
                                                        class="text-green-600 hover:text-green-900 transition-colors"
                                                        title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>

                                                    <form
                                                        action="{{ route('transaction.mark-completed', $purchase->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-900 transition-colors"
                                                            title="Tandai Selesai">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($purchase->status !== 'completed')
                                                    <form action="{{ route('transaction.destroy', $purchase->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="confirmDelete(this.form, '{{ $purchase->customer_name }}')"
                                                            class="text-red-600 hover:text-red-900 transition-colors"
                                                            title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                            <p class="text-lg font-medium">Tidak ada data pembelian</p>
                                            <p class="text-sm mt-1">Mulai dengan membuat pembelian baru</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($purchases->hasPages())
                        @php
                            $queryPurchases = array_merge(request()->except('page'), ['tab' => 'purchases']);
                        @endphp
                        <nav class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
                            role="navigation" aria-label="Pagination">
                            <div class="hidden sm:block">
                                <p class="text-sm text-gray-700">
                                    Menampilkan
                                    <span class="font-medium">{{ $purchases->firstItem() ?? 0 }}</span>
                                    sampai
                                    <span class="font-medium">{{ $purchases->lastItem() ?? 0 }}</span>
                                    dari
                                    <span class="font-medium">{{ $purchases->total() }}</span>
                                    hasil
                                </p>
                            </div>

                            <div class="flex-1 flex justify-between sm:justify-end">
                                {{-- sebelumnya --}}
                                <a href="{{ $purchases->appends($queryPurchases)->previousPageUrl() ?: '#' }}"
                                    class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $purchases->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4 7H15C16.8692 7 17.8039 7 18.5 7.40193C18.9561 7.66523 19.3348 8.04394 19.5981 8.49999C20 9.19615 20 10.1308 20 12C20 13.8692 20 14.8038 19.5981 15.5C19.3348 15.9561 18.9561 16.3348 18.5 16.5981C17.8039 17 16.8692 17 15 17H8.00001M4 7L7 4M4 7L7 10"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                </a>

                                <div class="hidden md:flex items-center space-x-1 px-2">
                                    @for ($i = 1; $i <= $purchases->lastPage(); $i++)
                                        <a href="{{ $purchases->appends($queryPurchases)->url($i) }}"
                                            class="px-3 py-2 rounded-md text-sm font-medium {{ $purchases->currentPage() == $i ? 'bg-green-600 text-white' : 'text-gray-700 bg-white border border-gray-200 hover:bg-gray-50' }}">
                                            {{ $i }}
                                        </a>
                                    @endfor
                                </div>

                                {{-- selanjutnya --}}
                                <a href="{{ $purchases->appends($queryPurchases)->nextPageUrl() ?: '#' }}"
                                    class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $purchases->hasMorePages() ? '' : 'opacity-50 pointer-events-none' }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20 7H9.00001C7.13077 7 6.19615 7 5.5 7.40193C5.04395 7.66523 4.66524 8.04394 4.40193 8.49999C4 9.19615 4 10.1308 4 12C4 13.8692 4 14.8038 4.40192 15.5C4.66523 15.9561 5.04394 16.3348 5.5 16.5981C6.19615 17 7.13077 17 9 17H16M20 7L17 4M20 7L17 10"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        </nav>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Hapus Transaksi
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus transaksi dari <span id="customerName"
                                            class="font-semibold"></span>?
                                        Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Hapus
                            </button>
                        </form>
                        <button type="button" onclick="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function transactionIndex() {
            return {
                activeTab: '{{ $activeTab }}',
                filters: {
                    status: '{{ request('status', '') }}',
                    payment_method: '{{ request('payment_method', '') }}',
                    start_date: '{{ request('start_date', '') }}',
                    end_date: '{{ request('end_date', '') }}'
                },

                init() {
                    // Set active tab based on URL parameter
                    const urlParams = new URLSearchParams(window.location.search);
                    const tabParam = urlParams.get('tab');
                    if (tabParam) {
                        this.activeTab = tabParam;
                    }
                },
            }
        }

        function confirmDelete(form, customerName) {
            document.getElementById('deleteForm').action = form.action;
            document.getElementById('customerName').textContent = customerName;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-layout.default>
