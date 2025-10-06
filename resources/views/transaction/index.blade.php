<x-layout.default>
    <div class="py-6" x-data="{ activeTab: '{{ $activeTab }}' }">
        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-gray-200 px-10">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'orders'"
                    :class="activeTab === 'orders' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    layanan
                </button>
                <button @click="activeTab = 'purchases'"
                    :class="activeTab === 'purchases' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    produk
                </button>
            </nav>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 relative"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="absolute top-3 right-3 text-green-700 hover:text-green-900"
                    aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Orders Tab Content -->
        <div x-show="activeTab === 'orders'" class="px-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Daftar Pesanan layanan</h1>
                <a href="{{ route('transaction.create') }}?type=order"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Tambah Pesanan Baru
                </a>
            </div>

            <!-- Filter Section for Orders -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('transaction.index') }}"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="hidden" name="tab" value="orders">
                    <div>
                        <label for="status_order" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status_order" name="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_method_order" class="block text-sm font-medium text-gray-700 mb-1">Metode
                            Pembayaran</label>
                        <select id="payment_method_order" name="payment_method"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Metode</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>
                                Transfer</option>
                            <option value="credit_card"
                                {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="material" class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                        <input type="text" id="material" name="material" value="{{ request('material') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Cari material...">
                    </div>
                    <div>
                        <label for="start_date_order" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Mulai</label>
                        <input type="date" id="start_date_order" name="start_date"
                            value="{{ request('start_date') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="end_date_order" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Akhir</label>
                        <input type="date" id="end_date_order" name="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-5 flex justify-end space-x-2 mt-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('transaction.index') }}?tab=orders"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelanggan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Layanan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ukuran</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Harga</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Metode Pembayaran</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Bayar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $order->customer_email ?? '-' }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->customer_phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $order->printing->nama_layanan ?? 'Layanan tidak ditemukan' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->material ?? '-' }}</div>
                                        <div class="text-sm text-gray-500">
                                            @if ($order->tinggi && $order->lebar)
                                                {{ $order->tinggi }} x {{ $order->lebar }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                        {{ $order->payment_method }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->paid_at ? \Carbon\Carbon::parse($order->paid_at)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap flex items-center space-x-2">
                                        <a href="{{ route('transaction.show', $order->id) }}"
                                            class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 4.45962C9.91153 4.16968 10.9104 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C3.75612 8.07914 4.32973 7.43025 5 6.82137"
                                                    stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" />
                                                <path
                                                    d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                            </svg>
                                        </a>

                                        @if ($order->status !== 'completed')
                                            <a href="{{ route('transaction.edit', $order->id) }}"
                                                class="text-green-600 hover:text-green-900" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <!-- Tombol Mark as Completed -->
                                            <form action="{{ route('transaction.markCompleted', $order->id) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900"
                                                    title="Tandai sebagai Selesai"
                                                    onclick="return confirm('Apakah Anda yakin ingin menandai pesanan ini sebagai selesai?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed"
                                                title="Tidak dapat diedit - Status Completed">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </span>

                                            <span class="text-green-600 cursor-default" title="Sudah Selesai">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </span>
                                        @endif

                                        @if ($order->status !== 'completed')
                                            <form action="{{ route('transaction.destroy', $order->id) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this.form)"
                                                    class="text-red-600 hover:text-red-900" title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed"
                                                title="Tidak dapat dihapus - Status Completed">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data pesanan yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Orders -->
                @if ($orders->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $orders->appends(['tab' => 'orders', 'status' => request('status'), 'payment_method' => request('payment_method'), 'material' => request('material'), 'start_date' => request('start_date'), 'end_date' => request('end_date')])->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Purchases Tab Content -->
        <div x-show="activeTab === 'purchases'" class="px-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Daftar Pembelian produk</h1>
                <a href="{{ route('transaction.create') }}?type=purchase"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Tambah Pembelian Baru
                </a>
            </div>

            <!-- Filter Section for Purchases -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('transaction.index') }}"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="hidden" name="tab" value="purchases">
                    <div>
                        <label for="status_purchase"
                            class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status_purchase" name="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_method_purchase"
                            class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select id="payment_method_purchase" name="payment_method"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Metode</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>
                                Transfer</option>
                            <option value="credit_card"
                                {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="material_purchase"
                            class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                        <input type="text" id="material_purchase" name="material"
                            value="{{ request('material') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Cari material...">
                    </div>
                    <div>
                        <label for="start_date_purchase" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Mulai</label>
                        <input type="date" id="start_date_purchase" name="start_date"
                            value="{{ request('start_date') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="end_date_purchase" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Akhir</label>
                        <input type="date" id="end_date_purchase" name="end_date"
                            value="{{ request('end_date') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-5 flex justify-end space-x-2 mt-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('transaction.index') }}?tab=purchases"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Purchases Table -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelanggan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Harga</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Metode Pembayaran</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Bayar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($purchases as $purchase)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $purchase->customer_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $purchase->customer_email ?? '-' }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $purchase->customer_phone ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $purchase->product->name ?? 'Produk tidak ditemukan' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $purchase->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                        {{ $purchase->payment_method }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$purchase->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $purchase->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $purchase->paid_at ? \Carbon\Carbon::parse($purchase->paid_at)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap flex items-center space-x-2">
                                        <a href="{{ route('transaction.show', $purchase->id) }}"
                                            class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 4.45962C9.91153 4.16968 10.9104 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C3.75612 8.07914 4.32973 7.43025 5 6.82137"
                                                    stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" />
                                                <path
                                                    d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                                                    stroke="currentColor" stroke-width="1.5" />
                                            </svg>
                                        </a>

                                        @if ($purchase->status !== 'completed')
                                            <a href="{{ route('transaction.edit', $purchase->id) }}"
                                                class="text-green-600 hover:text-green-900" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <!-- Tombol Mark as Completed -->
                                            <form action="{{ route('transaction.markCompleted', $purchase->id) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900"
                                                    title="Tandai sebagai Selesai"
                                                    onclick="return confirm('Apakah Anda yakin ingin menandai pembelian ini sebagai selesai?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed"
                                                title="Tidak dapat diedit - Status Completed">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </span>

                                            <span class="text-green-600 cursor-default" title="Sudah Selesai">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </span>
                                        @endif

                                        @if ($purchase->status !== 'completed')
                                            <form action="{{ route('transaction.destroy', $purchase->id) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this.form)"
                                                    class="text-red-600 hover:text-red-900" title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed"
                                                title="Tidak dapat dihapus - Status Completed">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data pembelian yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($purchases->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $purchases->appends(['tab' => 'purchases', 'status' => request('status'), 'payment_method' => request('payment_method'), 'material' => request('material'), 'start_date' => request('start_date'), 'end_date' => request('end_date')])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title"
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
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Hapus Data
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
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

    <script>
        function confirmDelete(form) {
            document.getElementById('deleteForm').action = form.action;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Set active tab based on URL parameter or default to orders
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            if (tabParam === 'purchases') {
                Alpine.data('activeTab', 'purchases');
            }
        });
    </script>
</x-layout.default>
