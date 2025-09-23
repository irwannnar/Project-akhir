<x-layout.default>
    <div class="min-h-screen">
        <!-- Header -->
        <header class="mt-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Edit Transaksi</h1>
                    <a href="{{ route('transaction.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Kembali
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <div>
                            <span class="font-medium">Terdapat kesalahan:</span>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden p-6" x-data="transactionForm()" x-init="init()">
                <form action="{{ route('transaction.update', $transaction->id) }}" method="POST" enctype="multipart/form-data" id="transactionForm">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="type" value="{{ $transaction->type }}">

                    <!-- Transaction Type Display -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi</label>
                        <div class="p-3 bg-gray-100 rounded-md">
                            <span class="font-medium">
                                {{ $transaction->type == 'purchase' ? 'Pembelian' : 'Pesanan Layanan' }}
                            </span>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pelanggan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan *</label>
                                <input 
                                    type="text" 
                                    id="customer_name" 
                                    name="customer_name" 
                                    value="{{ old('customer_name', $transaction->customer_name) }}" 
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                            </div>
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                                <input 
                                    type="text" 
                                    id="customer_phone" 
                                    name="customer_phone" 
                                    value="{{ old('customer_phone', $transaction->customer_phone) }}" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                            </div>
                            <div class="md:col-span-2">
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input 
                                    type="email" 
                                    id="customer_email" 
                                    name="customer_email" 
                                    value="{{ old('customer_email', $transaction->customer_email) }}" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                            </div>
                            <div class="md:col-span-2">
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea 
                                    id="customer_address" 
                                    name="customer_address" 
                                    rows="2"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >{{ old('customer_address', $transaction->customer_address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Product/Service Selection -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Transaksi</h3>
                        
                        <!-- Product Selection (for purchases) -->
                        <div x-show="transactionType === 'purchase'">
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Produk *</label>
                            <select 
                                id="product_id" 
                                name="product_id" 
                                x-model="productId"
                                @change="updateProductPrice()"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                @if($transaction->type == 'purchase') required @endif
                            >
                                <option value="">Pilih Produk</option>
                                @foreach($products as $product)
                                    <option 
                                        value="{{ $product->id }}" 
                                        data-price="{{ $product->price_per_unit }}"
                                        {{ old('product_id', $transaction->product_id) == $product->id ? 'selected' : '' }}
                                    >
                                        {{ $product->name }} - Rp {{ number_format($product->price_per_unit, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Service Selection (for orders) -->
                        <div x-show="transactionType === 'order'">
                            <label for="printing_id" class="block text-sm font-medium text-gray-700 mb-1">Layanan Cetak *</label>
                            <select 
                                id="printing_id" 
                                name="printing_id" 
                                x-model="printingId"
                                @change="updateServicePrice()"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                @if($transaction->type == 'order') required @endif
                            >
                                <option value="">Pilih Layanan Cetak</option>
                                @foreach($services as $service)
                                    <option 
                                        value="{{ $service->id }}" 
                                        data-price="{{ $service->biaya }}"
                                        {{ old('printing_id', $transaction->printing_id) == $service->id ? 'selected' : '' }}
                                    >
                                        {{ $service->nama_layanan }} - Rp {{ number_format($service->biaya, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                            <input 
                                type="number" 
                                id="quantity" 
                                name="quantity" 
                                x-model="quantity"
                                @input="updateTotalPrice()"
                                min="1" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                        <div x-show="transactionType === 'order'">
                            <label for="material" class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                            <input 
                                type="text" 
                                id="material" 
                                name="material" 
                                value="{{ old('material', $transaction->material) }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                        <div x-show="transactionType === 'order'">
                            <label for="ukuran" class="block text-sm font-medium text-gray-700 mb-1">Ukuran</label>
                            <input 
                                type="text" 
                                id="ukuran" 
                                name="ukuran" 
                                value="{{ old('ukuran', $transaction->ukuran) }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp)</label>
                            <input 
                                type="number" 
                                id="unit_price" 
                                name="unit_price" 
                                x-model="unitPrice"
                                @input="updateTotalPrice()"
                                min="0" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                        <div>
                            <label for="total_price" class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp) *</label>
                            <input 
                                type="number" 
                                id="total_price" 
                                name="total_price" 
                                x-model="totalPrice"
                                required
                                readonly
                                class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100"
                            >
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6" x-show="transactionType === 'order'">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File Desain</label>
                        <div class="mt-1 flex items-center">
                            <input 
                                type="file" 
                                id="file" 
                                name="file" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                        @if($transaction->file_path)
                            <div class="mt-2">
                                <span class="text-sm text-gray-600">File saat ini: </span>
                                <a href="{{ Storage::url($transaction->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                    Lihat File
                                </a>
                            </div>
                        @endif
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, PDF, DOC, DOCX (Maks. 5MB)</p>
                    </div>

                    <!-- Payment Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran *</label>
                                <select 
                                    id="payment_method" 
                                    name="payment_method" 
                                    x-model="paymentMethod"
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="cash" {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="transfer" {{ old('payment_method', $transaction->payment_method) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="credit_card" {{ old('payment_method', $transaction->payment_method) == 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select 
                                    id="status" 
                                    name="status" 
                                    x-model="status"
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Pilih Status</option>
                                    <option value="pending" {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ old('status', $transaction->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ old('status', $transaction->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $transaction->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4" x-show="status === 'completed' && paymentMethod !== 'cash'">
                            <label for="paid_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembayaran</label>
                            <input 
                                type="datetime-local" 
                                id="paid_at" 
                                name="paid_at" 
                                value="{{ old('paid_at', $transaction->paid_at ? $transaction->paid_at->format('Y-m-d\TH:i') : '') }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="3"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('notes', $transaction->notes) }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Perbarui Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Script Section -->
    <script>
        function transactionForm() {
            return {
                transactionType: @json($transaction->type),
                productId: @json(old('product_id', $transaction->product_id)),
                printingId: @json(old('printing_id', $transaction->printing_id)),
                quantity: {{ old('quantity', $transaction->quantity) }},
                unitPrice: {{ old('unit_price', $transaction->unit_price) }},
                totalPrice: {{ old('total_price', $transaction->total_price) }},
                totalCost: {{ old('total_cost', $transaction->total_cost ?? 0) }},
                profit: {{ old('profit', $transaction->profit ?? 0) }},
                paymentMethod: @json(old('payment_method', $transaction->payment_method)),
                status: @json(old('status', $transaction->status)),
                
                init() {
                    console.log('Initializing form...');
                    console.log('Transaction Type:', this.transactionType);
                    console.log('Product ID:', this.productId);
                    console.log('Printing ID:', this.printingId);
                    console.log('Quantity:', this.quantity);
                    console.log('Unit Price:', this.unitPrice);
                    console.log('Payment Method:', this.paymentMethod);
                    console.log('Status:', this.status);
                    
                    // Tunggu sebentar untuk memastikan DOM sudah terrender
                    setTimeout(() => {
                        // Update harga berdasarkan produk/layanan yang dipilih
                        if (this.transactionType === 'purchase' && this.productId) {
                            this.updateProductPrice();
                        } else if (this.transactionType === 'order' && this.printingId) {
                            this.updateServicePrice();
                        }
                        
                        // Update total harga dan profit
                        this.updateTotalPrice();
                        this.updateProfit();
                    }, 100);
                },
                
                updateProductPrice() {
                    if (this.productId) {
                        const selectedOption = document.querySelector(`#product_id option[value="${this.productId}"]`);
                        if (selectedOption) {
                            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                            this.unitPrice = price;
                            console.log('Updated product price:', price);
                        }
                    }
                    this.updateTotalPrice();
                },
                
                updateServicePrice() {
                    if (this.printingId) {
                        const selectedOption = document.querySelector(`#printing_id option[value="${this.printingId}"]`);
                        if (selectedOption) {
                            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                            this.unitPrice = price;
                            console.log('Updated service price:', price);
                        }
                    }
                    this.updateTotalPrice();
                },
                
                updateTotalPrice() {
                    this.totalPrice = this.quantity * this.unitPrice;
                    console.log('Updated total price:', this.totalPrice, 'Quantity:', this.quantity, 'Unit Price:', this.unitPrice);
                    this.updateProfit();
                },
                
                updateProfit() {
                    this.profit = this.totalPrice - this.totalCost;
                    console.log('Updated profit:', this.profit, 'Total Price:', this.totalPrice, 'Total Cost:', this.totalCost);
                }
            }
        }
        
        // Initialize Alpine.js
        document.addEventListener('alpine:init', () => {
            Alpine.data('transactionForm', transactionForm);
        });
    </script>
</x-layout.default>