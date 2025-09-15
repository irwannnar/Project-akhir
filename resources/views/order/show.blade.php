<x-layout.default>
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Detail Order</h1>
            <div class="flex space-x-2">
                <a href="{{ route('order.edit', $order->id) }}"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <form action="{{ route('order.destroy', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-600 text-white px-4 py-2 rounded-lg"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                        hapus
                    </button>
                </form>
                <a href="{{ route('order.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Info Order -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Order</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Kode Order</p>
                            <p class="font-medium">{{ $order->order_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($order->status == 'pending') bg-yellow-100; text-yellow-800;
                                @elseif($order->status == 'processing') bg-blue-100; text-blue-800;
                                @elseif($order->status == 'completed') bg-green-100; text-green-800;
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Printing</p>
                            <p class="font-medium">{{ ucfirst($order->printing_type) }} Printing</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Material</p>
                            <p class="font-medium">{{ ucfirst($order->material) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ukuran</p>
                            <p class="font-medium">{{ $order->width }}cm x {{ $order->height }}cm</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jumlah</p>
                            <p class="font-medium">{{ $order->quantity }} item</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Harga</p>
                            <p class="font-medium text-lg text-blue-600">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Customer</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-medium">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Telepon</p>
                            <p class="font-medium">{{ $order->customer_phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $order->customer_email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Alamat</p>
                            <p class="font-medium">{{ $order->customer_address }}</p>
                        </div>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-800 mt-6 mb-4">Catatan</h2>
                    <p class="text-gray-700">{{ $order->notes ?? 'Tidak ada catatan' }}</p>

                    @if ($order->file_path)
                        <h2 class="text-xl font-semibold text-gray-800 mt-6 mb-4">File Desain</h2>
                        <a href="{{ Storage::url($order->file_path) }}" target="_blank"
                            class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-file-download mr-2"></i> Download File
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Timeline Order</h2>
            <div class="space-y-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                            <i class="fas fa-plus text-xs"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Order dibuat</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                @if ($order->status != 'pending')
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
                                <i class="fas fa-cog text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Diproses</p>
                            <p class="text-sm text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                @endif

                @if ($order->status == 'completed')
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Selesai</p>
                            <p class="text-sm text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout.default>
