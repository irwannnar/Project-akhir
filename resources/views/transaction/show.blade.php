<x-layout.default>
    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - Cetak Brosur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('transaction.edit', $transaction->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Edit
                        </a>
                        <a href="{{ route('transaction.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Transaction Header -->
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900">
                            Transaksi #{{ $transaction->id }}
                        </h2>
                        <div class="flex items-center space-x-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses[$transaction->status] }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $transaction->type == 'purchase' ? 'Pembelian' : 'Pesanan' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <!-- Customer Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pelanggan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Nama Pelanggan</p>
                                <p class="text-sm text-gray-900">{{ $transaction->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Telepon</p>
                                <p class="text-sm text-gray-900">{{ $transaction->customer_phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Email</p>
                                <p class="text-sm text-gray-900">{{ $transaction->customer_email ?? '-' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-700">Alamat</p>
                                <p class="text-sm text-gray-900">{{ $transaction->customer_address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Transaksi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($transaction->type == 'purchase')
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Produk</p>
                                    <p class="text-sm text-gray-900">{{ $transaction->product->name ?? 'Produk tidak ditemukan' }}</p>
                                </div>
                            @else
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Layanan Cetak</p>
                                    <p class="text-sm text-gray-900">{{ $transaction->printing->nama_layanan ?? 'Layanan tidak ditemukan' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Material</p>
                                    <p class="text-sm text-gray-900">{{ $transaction->material ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Ukuran</p>
                                    <p class="text-sm text-gray-900">{{ $transaction->ukuran ?? '-' }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-700">Jumlah</p>
                                <p class="text-sm text-gray-900">{{ $transaction->quantity }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Harga</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Harga Satuan</p>
                                <p class="text-sm text-gray-900">Rp {{ number_format($transaction->unit_price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Total Harga</p>
                                <p class="text-sm text-gray-900">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                            </div>
                            @if($transaction->total_cost)
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Total Biaya</p>
                                    <p class="text-sm text-gray-900">Rp {{ number_format($transaction->total_cost, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Profit</p>
                                    <p class="text-sm text-gray-900">Rp {{ number_format($transaction->profit, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Metode Pembayaran</p>
                                <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Status</p>
                                <p class="text-sm text-gray-900">{{ ucfirst($transaction->status) }}</p>
                            </div>
                            @if($transaction->paid_at)
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tanggal Pembayaran</p>
                                    <p class="text-sm text-gray-900">{{ $transaction->paid_at->format('d M Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- File Attachment -->
                    @if($transaction->file_path)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">File Desain</h3>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <a href="{{ Storage::url($transaction->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    Lihat File
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($transaction->notes)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Catatan</h3>
                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $transaction->notes }}</p>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <p>Dibuat pada: {{ $transaction->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p>Diperbarui pada: {{ $transaction->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
</x-layout.default>