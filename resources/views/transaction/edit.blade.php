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
                            <div>
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
                                        {{ $service->nama_layanan }} - Rp {{ number_format($service->biaya, 0, ',', '.') }}/cm²
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                            <input 
                                type="number" 
                                id="quantity" 
                                name="quantity" 
                                x-model="quantity"
                                @input="calculateTotalPrice()"
                                min="1" 
                                value="{{ old('quantity', $transaction->quantity) }}" 
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
                            <label for="tinggi" class="block text-sm font-medium text-gray-700 mb-1">Tinggi (cm) *</label>
                            <input 
                                type="number" 
                                id="tinggi" 
                                name="tinggi" 
                                x-model="tinggi"
                                @input="calculateTotalPrice()"
                                min="1" 
                                step="0.1"
                                value="{{ old('tinggi', $transaction->tinggi) }}" 
                                :required="transactionType === 'order'"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="0"
                            >
                        </div>
                        <div x-show="transactionType === 'order'">
                            <label for="lebar" class="block text-sm font-medium text-gray-700 mb-1">Lebar (cm) *</label>
                            <input 
                                type="number" 
                                id="lebar" 
                                name="lebar" 
                                x-model="lebar"
                                @input="calculateTotalPrice()"
                                min="1" 
                                step="0.1"
                                value="{{ old('lebar', $transaction->lebar) }}" 
                                :required="transactionType === 'order'"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="0"
                            >
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Harga</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- For Orders - Show calculated price -->
                            <template x-if="transactionType === 'order'">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per cm²</label>
                                    <div class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100 px-3 py-2">
                                        <span x-text="'Rp ' + formatNumber(unitPrice) + '/cm²'"></span>
                                    </div>
                                </div>
                            </template>
                            
                            <!-- For Purchases - Show unit price input -->
                            <template x-if="transactionType === 'purchase'">
                                <div>
                                    <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp)</label>
                                    <input 
                                        type="number" 
                                        id="unit_price" 
                                        name="unit_price" 
                                        x-model="unitPrice"
                                        @input="calculateTotalPrice()"
                                        min="0" 
                                        step="1"
                                        value="{{ old('unit_price', $transaction->unit_price) }}" 
                                        required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                </div>
                            </template>

                            <div>
                                <label for="total_price" class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp) *</label>
                                <input 
                                    type="number" 
                                    id="total_price" 
                                    name="total_price" 
                                    x-model="totalPrice"
                                    readonly
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100"
                                >
                                <!-- Price breakdown for orders -->
                                <div x-show="transactionType === 'order' && totalPrice > 0" class="mt-1 text-xs text-gray-500">
                                    <span x-text="'Luas: ' + (tinggi || 0) + 'cm × ' + (lebar || 0) + 'cm = ' + ((tinggi || 0) * (lebar || 0)).toFixed(2) + 'cm²'"></span>
                                    <br>
                                    <span x-text="'× Rp ' + formatNumber(unitPrice) + '/cm²'"></span>
                                    <br>
                                    <span x-text="'× ' + quantity + ' item = Rp ' + formatNumber(totalPrice)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6" x-show="transactionType === 'order'">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File Desain</label>
                        <input 
                            type="file" 
                            id="file" 
                            name="file" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                        >
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
                                <label for="paid_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                                <input 
                                    type="datetime-local" 
                                    id="paid_at" 
                                    name="paid_at" 
                                    value="{{ old('paid_at', $transaction->paid_at ? $transaction->paid_at->format('Y-m-d\TH:i') : '') }}" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select 
                                    id="status" 
                                    name="status" 
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
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="3"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Catatan tambahan untuk transaksi..."
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('transactionForm', () => ({
                transactionType: @json($transaction->type),
                productId: @json(old('product_id', $transaction->product_id)),
                printingId: @json(old('printing_id', $transaction->printing_id)),
                quantity: {{ old('quantity', $transaction->quantity) }},
                unitPrice: {{ old('unit_price', $transaction->unit_price ?? 0) }},
                totalPrice: {{ old('total_price', $transaction->total_price) }},
                tinggi: {{ old('tinggi', $transaction->tinggi ?? 0) }},
                lebar: {{ old('lebar', $transaction->lebar ?? 0) }},
                
                init() {
                    console.log('Form initialized with type:', this.transactionType);
                    
                    // Update harga berdasarkan data yang ada
                    if (this.transactionType === 'purchase' && this.productId) {
                        this.updateProductPrice();
                    } else if (this.transactionType === 'order' && this.printingId) {
                        this.updateServicePrice();
                    }
                    
                    this.calculateTotalPrice();
                },
                
                updateProductPrice() {
                    if (this.productId) {
                        const selectedOption = document.querySelector(`#product_id option[value="${this.productId}"]`);
                        if (selectedOption) {
                            this.unitPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                        }
                    } else {
                        this.unitPrice = 0;
                    }
                    this.calculateTotalPrice();
                },
                
                updateServicePrice() {
                    if (this.printingId) {
                        const selectedOption = document.querySelector(`#printing_id option[value="${this.printingId}"]`);
                        if (selectedOption) {
                            this.unitPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                        }
                    } else {
                        this.unitPrice = 0;
                    }
                    this.calculateTotalPrice();
                },
                
                calculateTotalPrice() {
                    if (this.transactionType === 'order') {
                        // Untuk order: (tinggi × lebar) × harga per cm² × quantity
                        const luas = (parseFloat(this.tinggi) || 0) * (parseFloat(this.lebar) || 0);
                        this.totalPrice = luas * (parseFloat(this.unitPrice) || 0) * (parseInt(this.quantity) || 1);
                    } else {
                        // Untuk purchase: unit price × quantity
                        this.totalPrice = (parseFloat(this.unitPrice) || 0) * (parseInt(this.quantity) || 1);
                    }
                    
                    // Round to nearest integer
                    this.totalPrice = Math.round(this.totalPrice);
                },
                
                formatNumber(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                }
            }));
        });

        // Custom form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('transactionForm');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submitted');
                    
                    // Validasi basic
                    const quantity = document.getElementById('quantity');
                    const totalPrice = document.getElementById('total_price');
                    
                    if (quantity && parseFloat(quantity.value) <= 0) {
                        e.preventDefault();
                        alert('Jumlah harus lebih besar dari 0');
                        quantity.focus();
                        return false;
                    }
                    
                    if (totalPrice && parseFloat(totalPrice.value) <= 0) {
                        e.preventDefault();
                        alert('Total harga harus lebih besar dari 0');
                        totalPrice.focus();
                        return false;
                    }
                    
                    return true;
                });
            }
        });
    </script>
</x-layout.default>