<x-layout.default>
<div class="container mx-auto px-4 py-8" x-data="{ showDeleteModal: false }">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Pembelian</h1>
        <div class="flex space-x-2">
            <a href="{{ route('purchase.edit', $purchase->id) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <button @click="showDeleteModal = true" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Hapus
            </button>
            <a href="{{ route('purchase.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Pembelian -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Pembelian</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID Pembelian</p>
                        <p class="text-lg font-semibold text-gray-800">#{{ $purchase->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Pembelian</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $purchase->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $purchase->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $purchase->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $purchase->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $purchase->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Metode Pembayaran</p>
                        <p class="text-lg font-semibold text-gray-800">{{ ucfirst($purchase->payment_method) }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Produk -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Produk</h2>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <!-- Placeholder for product image -->
                        <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $purchase->product->name }}</h3>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Quantity</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $purchase->quantity }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Harga Satuan</p>
                                <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($purchase->product->price_per_unit, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 border-t pt-4">
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-semibold text-gray-800">Total Harga</p>
                        <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pelanggan & Pembayaran -->
        <div class="space-y-6">
            <!-- Informasi Pelanggan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Pelanggan</h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $purchase->customer_name }}</p>
                    </div>
                    
                    @if($purchase->customer_email)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="text-lg font-semibold text-gray-800">
                            <a href="mailto:{{ $purchase->customer_email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $purchase->customer_email }}
                            </a>
                        </p>
                    </div>
                    @endif
                    
                    @if($purchase->customer_phone)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Telepon</p>
                        <p class="text-lg font-semibold text-gray-800">
                            <a href="tel:{{ $purchase->customer_phone }}" class="text-blue-600 hover:text-blue-800">
                                {{ $purchase->customer_phone }}
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Pembayaran</h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status Pembayaran</p>
                        <p class="text-lg font-semibold text-gray-800">
                            @if($purchase->paid_at)
                                <span class="text-green-600">Lunas</span>
                            @else
                                <span class="text-red-600">Belum Bayar</span>
                            @endif
                        </p>
                    </div>
                    
                    @if($purchase->paid_at)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Pembayaran</p>
                        <p class="text-lg font-semibold text-gray-800">{{ \Carbon\Carbon::parse($purchase->paid_at)->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Metode Pembayaran</p>
                        <p class="text-lg font-semibold text-gray-800">{{ ucfirst($purchase->payment_method) }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Status Timeline</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="{{ $purchase->created_at ? 'bg-blue-500' : 'bg-gray-300' }} rounded-full h-4 w-4 mt-1"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Pembelian Dibuat</p>
                            <p class="text-sm text-gray-500">{{ $purchase->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="{{ $purchase->paid_at ? 'bg-green-500' : 'bg-gray-300' }} rounded-full h-4 w-4 mt-1"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Pembayaran</p>
                            <p class="text-sm text-gray-500">
                                @if($purchase->paid_at)
                                    {{ \Carbon\Carbon::parse($purchase->paid_at)->format('d M Y, H:i') }}
                                @else
                                    Menunggu pembayaran
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="{{ $purchase->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full h-4 w-4 mt-1"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Selesai</p>
                            <p class="text-sm text-gray-500">
                                @if($purchase->status === 'completed')
                                    {{ $purchase->updated_at->format('d M Y, H:i') }}
                                @else
                                    Proses belum selesai
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Hapus Pembelian
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus pembelian dari <span class="font-semibold">{{ $purchase->customer_name }}</span>? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                    </form>
                    <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layout.default>
