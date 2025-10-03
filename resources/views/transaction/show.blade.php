<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">Detail Transaksi</h1>
                        <p class="text-gray-600">Informasi lengkap tentang transaksi cetak brosur</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('transaction.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                        <button onclick="window.print()"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 9V2h12v7M6 18h12v-5H6v5zm0 0v2a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Detail -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header Card -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold">Transaksi #{{ $transaction->id }}</h2>
                            <p class="text-blue-100 mt-1">
                                {{ $transaction->type == 'purchase' ? 'Pembelian' : 'Pesanan Cetak' }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y') }}
                            </span>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-500 bg-opacity-20 text-yellow-100',
                                    'processing' => 'bg-blue-500 bg-opacity-20 text-blue-100',
                                    'completed' => 'bg-green-500 bg-opacity-20 text-green-100',
                                    'cancelled' => 'bg-red-500 bg-opacity-20 text-red-100',
                                ];
                            @endphp
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$transaction->status] }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Body Card -->
                <div class="p-6">
                    <!-- Informasi Pelanggan -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pelanggan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Nama Pelanggan</label>
                                <p class="text-gray-800 mt-1">{{ $transaction->customer_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Telepon</label>
                                <p class="text-gray-800 mt-1">{{ $transaction->customer_phone ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Email</label>
                                <p class="text-gray-800 mt-1">{{ $transaction->customer_email ?? '-' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500">Alamat</label>
                                <p class="text-gray-800 mt-1">{{ $transaction->customer_address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Transaksi -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Transaksi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if ($transaction->type == 'purchase')
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Produk</label>
                                    <p class="text-gray-800 mt-1">
                                        {{ $transaction->product->name ?? 'Produk tidak ditemukan' }}</p>
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Layanan Cetak</label>
                                    <p class="text-gray-800 mt-1">
                                        {{ $transaction->printing->nama_layanan ?? 'Layanan tidak ditemukan' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Material</label>
                                    <p class="text-gray-800 mt-1">{{ $transaction->material ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Ukuran</label>
                                    <p class="text-gray-800 mt-1">{{ $transaction->ukuran ?? '-' }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Jumlah</label>
                                <p class="text-gray-800 mt-1">{{ $transaction->quantity }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Harga -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Harga</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Harga Satuan</label>
                                <p class="text-xl font-bold text-gray-800 mt-1">
                                    Rp {{ number_format($transaction->unit_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Total Harga</label>
                                <p class="text-xl font-bold text-gray-800 mt-1">
                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                </p>
                            </div>
                            @if ($transaction->total_cost)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Total Biaya</label>
                                    <p class="text-lg text-gray-800 mt-1">
                                        Rp {{ number_format($transaction->total_cost, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Profit</label>
                                    <p class="text-lg font-bold text-green-600 mt-1">
                                        Rp {{ number_format($transaction->profit, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Pembayaran -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Metode Pembayaran</label>
                                <p class="text-gray-800 mt-1">
                                    @switch($transaction->payment_method)
                                        @case('cash')
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Cash</span>
                                        @break

                                        @case('credit_card')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Kartu
                                                Kredit</span>
                                        @break

                                        @case('debit_card')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Kartu
                                                Debit</span>
                                        @break

                                        @case('transfer')
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Transfer</span>
                                        @break
                                    @endswitch
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Status Pembayaran</label>
                                <p class="text-gray-800 mt-1">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </p>
                            </div>
                            @if ($transaction->paid_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Tanggal Pembayaran</label>
                                    <p class="text-gray-800 mt-1">{{ $transaction->paid_at }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- File Attachment -->
                    @if ($transaction->file_path)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">File Desain</h3>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <a href="{{ Storage::url($transaction->file_path) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                    Lihat File Desain
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Catatan -->
                    @if ($transaction->notes)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Catatan</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $transaction->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Informasi Sistem -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Informasi Sistem</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Dibuat:</span>
                                <span>{{ $transaction->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Diupdate:</span>
                                <span>{{ $transaction->updated_at->format('d M Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-medium">ID Transaksi:</span>
                                <span class="font-mono">{{ $transaction->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-500">
                            Terakhir diupdate {{ $transaction->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.default>
