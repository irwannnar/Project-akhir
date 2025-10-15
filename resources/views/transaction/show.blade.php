<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <title>Lep Print</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-300">

    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header Actions -->
            <div class="mb-6 print:hidden">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">Invoice Transaksi</h1>
                        <p class="text-gray-600">Struk resmi untuk transaksi cetak brosur</p>
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
                            Print Invoice
                        </button>
                    </div>
                </div>
            </div>

            <!-- Invoice Card -->
            <div
                class="bg-white rounded-lg shadow-lg overflow-hidden border-2 border-gray-200 print:shadow-none print:border-0">
                <!-- Invoice Header -->
                <div class="bg-white p-8 border-b-2 border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-800">INVOICE</h1>
                                    <p class="text-gray-600 text-sm">Printing Service</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-2xl font-bold text-blue-600 mb-2">
                                {{ $transaction->transaction_number }}
                            </h2>
                            <p class="text-gray-600 text-sm">
                                Tanggal: {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}
                            </p>
                            <p class="text-gray-600 text-sm">
                                Due: {{ \Carbon\Carbon::parse($transaction->created_at)->addDays(7)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Company & Customer Info -->
                <div class="p-8 border-b-2 border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Company Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dari:</h3>
                            <div class="space-y-2">
                                <p class="text-xl font-bold text-gray-900">Lep Print</p>
                                <p class="text-gray-600">Jl. Printing No. 123, Jakarta</p>
                                <p class="text-gray-600">Telp: (99) 3009-1965</p>
                                <p class="text-gray-600">Email: scam@asklasadabak.com</p>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Kepada:</h3>
                            <div class="space-y-2">
                                <p class="text-xl font-bold text-gray-900">{{ $transaction->customer_name }}</p>
                                <p class="text-gray-600">{{ $transaction->customer_phone ?? '-' }}</p>
                                <p class="text-gray-600">{{ $transaction->customer_email ?? '-' }}</p>
                                <p class="text-gray-600">{{ $transaction->customer_address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div class="p-8 border-b-2 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Pesanan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 border-b">
                                        Deskripsi</th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-gray-700 border-b">Jumlah
                                    </th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-gray-700 border-b">Harga
                                        Satuan</th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-gray-700 border-b">Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                <tr>
                                    <td class="py-4 px-4 border-b">
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                @if ($transaction->type == 'purchase')
                                                    {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                                                @else
                                                    {{ $item->printing->nama_layanan ?? 'Layanan tidak ditemukan' }}
                                                @endif
                                            </p>
                                            @if ($transaction->type == 'order')
                                                @if ($item->tinggi && $item->lebar)
                                                <p class="text-sm text-gray-600 mt-1">
                                                    Ukuran: {{ $item->tinggi }} x {{ $item->lebar }} cm
                                                </p>
                                                @endif
                                            @endif
                                            @if($item->notes)
                                            <p class="text-sm text-gray-600 mt-1">
                                                Catatan: {{ $item->notes }}
                                            </p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-right border-b text-gray-700">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="py-4 px-4 text-right border-b text-gray-700">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4 text-right border-b text-gray-700">
                                        Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="p-8">
                    <div class="flex justify-end">
                        <div class="w-64">
                            <div class="space-y-3">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2">
                                    <div class="flex justify-between text-lg font-bold text-gray-800">
                                        <span>Total:</span>
                                        <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="p-8 bg-gray-50 border-t-2 border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tipe Transaksi:</span>
                                    <span class="font-semibold text-gray-800 capitalize">
                                        {{ $transaction->type == 'order' ? 'Pesanan Layanan' : 'Pembelian Produk' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Metode Pembayaran:</span>
                                    <span class="font-semibold text-gray-800 capitalize">
                                        {{ $transaction->payment_method }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-semibold">
                                        @php
                                            $statusColors = [
                                                'pending' => 'text-yellow-600',
                                                'completed' => 'text-green-600',
                                                'cancelled' => 'text-red-600',
                                            ];
                                        @endphp
                                        <span class="{{ $statusColors[$transaction->status] ?? 'text-gray-600' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </span>
                                </div>
                                @if ($transaction->paid_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tanggal Bayar:</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ \Carbon\Carbon::parse($transaction->paid_at)->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Catatan</h3>
                            <div class="text-sm text-gray-600">
                                @if ($transaction->notes)
                                    <p class="whitespace-pre-wrap">{{ $transaction->notes }}</p>
                                @else
                                    <p class="text-gray-400">Tidak ada catatan</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-8 bg-white border-t-2 border-gray-200">
                    <div class="flex justify-center text-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-2">Terima Kasih</p>
                            <p class="text-xs text-gray-500">Terima kasih atas kepercayaan Anda</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Print Notice -->
            <div class="mt-6 text-center print:hidden">
                <p class="text-sm text-gray-500">
                    Invoice ini sah dan dapat digunakan sebagai bukti transaksi
                </p>
            </div>
        </div>
    </div>

    <style>
        @media print {
            @page {
                margin: 0.5cm;
                size: A4 portrait;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print\:break-before-page {
                break-before: page;
            }

            .print\:shadow-none {
                box-shadow: none !important;
            }

            .print\:border-0 {
                border: none !important;
            }
        }
    </style>
</body>

</html>