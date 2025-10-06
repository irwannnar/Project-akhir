<x-layout.default>
    <div class="min-h-screen">
        <!-- Header -->
        <header class="mt-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Transaksi Baru</h1>
                    <a href="{{ route('transaction.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
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

            <div class="bg-white shadow-md rounded-lg overflow-hidden p-6" x-data="transactionForm()"
                x-init="init()">
                <form action="{{ route('transaction.store') }}" method="POST" enctype="multipart/form-data"
                    id="transactionForm">
                    @csrf

                    <input type="hidden" name="type" x-model="transactionType">

                    <!-- Transaction Type Selector -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div @click="setTransactionType('purchase')"
                                :class="transactionType === 'purchase' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
                                class="border-2 rounded-lg p-4 cursor-pointer transition-colors">
                                <div class="flex items-center">
                                    <input type="radio" name="transaction_type" value="purchase"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                                        :checked="transactionType === 'purchase'">
                                    <label class="ml-3 block text-sm font-medium text-gray-700">
                                        Pembelian
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Transaksi pembelian produk fisik
                                </p>
                            </div>

                            <div @click="setTransactionType('order')"
                                :class="transactionType === 'order' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
                                class="border-2 rounded-lg p-4 cursor-pointer transition-colors">
                                <div class="flex items-center">
                                    <input type="radio" name="transaction_type" value="order"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                                        :checked="transactionType === 'order'">
                                    <label class="ml-3 block text-sm font-medium text-gray-700">
                                        Pesanan Layanan
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Transaksi pemesanan layanan cetak
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pelanggan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                    Pelanggan *</label>
                                <input type="text" id="customer_name" name="customer_name"
                                    value="{{ old('customer_name') }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="customer_phone"
                                    class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                                <input type="text" id="customer_phone" name="customer_phone"
                                    value="{{ old('customer_phone') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="customer_email"
                                    class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="customer_email" name="customer_email"
                                    value="{{ old('customer_email') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label for="customer_address"
                                    class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea id="customer_address" name="customer_address" rows="2"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('customer_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Product/Service Selection -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Transaksi</h3>

                        <!-- Product Selection (for purchases) -->
                        <div x-show="transactionType === 'purchase'">
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Produk
                                *</label>
                            <select id="product_id" name="product_id" x-model="productId"
                                @change="updateProductPriceAndStock()"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :required="transactionType === 'purchase'">
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price_per_unit }}"
                                        data-stock="{{ $product->stock }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - Rp
                                        {{ number_format($product->price_per_unit, 0, ',', '.') }}
                                        (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>

                            <!-- Notifikasi Stok -->
                            <div x-show="showStockWarning"
                                class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    <span class="text-red-700 text-sm font-medium">
                                        Stok tidak mencukupi!
                                        <span
                                            x-text="'Stok tersedia: ' + availableStock + ', yang diminta: ' + quantity"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Info Stok Tersedia -->
                            <div x-show="availableStock !== null && !showStockWarning" class="mt-2">
                                <span class="text-sm text-gray-600"
                                    x-text="'Stok tersedia: ' + availableStock"></span>
                            </div>
                        </div>

                        <!-- Service Selection (for orders) -->
                        <div x-show="transactionType === 'order'">
                            <label for="printing_id" class="block text-sm font-medium text-gray-700 mb-1">Layanan
                                Cetak *</label>
                            <select id="printing_id" name="printing_id" x-model="printingId"
                                @change="updateServicePrice()"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :required="transactionType === 'order'">
                                <option value="">Pilih Layanan Cetak</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->biaya }}"
                                        {{ old('printing_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->nama_layanan }} - Rp
                                        {{ number_format($service->biaya, 0, ',', '.') }}/cm
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah
                                *</label>
                            <input type="number" id="quantity" name="quantity" x-model="quantity"
                                @input="calculateTotalPrice()" min="1" value="{{ old('quantity', 1) }}"
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div x-show="transactionType === 'order'">
                            <label for="material"
                                class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                            <input type="text" id="material" name="material" value="{{ old('material') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div x-show="transactionType === 'order'">
                            <label for="tinggi" class="block text-sm font-medium text-gray-700 mb-1">Tinggi
                                (cm)</label>
                            <input type="number" id="tinggi" name="tinggi" x-model="tinggi"
                                @input="calculateTotalPrice()" min="0" step="0.1"
                                value="{{ old('tinggi') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="0">
                        </div>
                        <div x-show="transactionType === 'order'">
                            <label for="lebar" class="block text-sm font-medium text-gray-700 mb-1">Lebar
                                (cm)</label>
                            <input type="number" id="lebar" name="lebar" x-model="lebar"
                                @input="calculateTotalPrice()" min="0" step="0.1"
                                value="{{ old('lebar') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="0">
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Harga</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- For Orders - Show calculated price -->
                            <template x-if="transactionType === 'order'">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per cm</label>
                                    <div class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100 px-3 py-2">
                                        <span x-text="'Rp ' + formatNumber(unitPrice) + '/cm'"></span>
                                    </div>
                                </div>
                            </template>

                            <!-- For Purchases - Show unit price input -->
                            <template x-if="transactionType === 'purchase'">
                                <div>
                                    <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Harga
                                        Satuan (Rp)</label>
                                    <input type="number" id="unit_price" name="unit_price" x-model="unitPrice"
                                        @input="calculateTotalPrice()" min="0" step="1"
                                        value="{{ old('unit_price', 0) }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </template>

                            <div>
                                <label for="total_price" class="block text-sm font-medium text-gray-700 mb-1">Total
                                    Harga (Rp) *</label>
                                <input type="number" id="total_price" name="total_price" x-model="totalPrice"
                                    readonly required class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100">
                                <!-- Price breakdown for orders -->
                                <div x-show="transactionType === 'order' && totalPrice > 0"
                                    class="mt-1 text-xs text-gray-500">
                                    <span
                                        x-text="'Luas: ' + (tinggi || 0) + 'cm × ' + (lebar || 0) + 'cm = ' + ((tinggi || 0) * (lebar || 0)).toFixed(2) + 'cm'"></span>
                                    <br>
                                    <span x-text="'× Rp ' + formatNumber(unitPrice) + '/cm'"></span>
                                    <br>
                                    <span x-text="'× ' + quantity + ' item = Rp ' + formatNumber(totalPrice)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6" x-show="transactionType === 'order'">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File Desain</label>
                        <input type="file" id="file" name="file"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, PDF, DOC, DOCX (Maks. 5MB)</p>
                    </div>

                    <!-- Payment Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="payment_method"
                                    class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran *</label>
                                <select id="payment_method" name="payment_method" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="cash"
                                        {{ old('payment_method', 'cash') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="transfer"
                                        {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="credit_card"
                                        {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="paid_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                    Bayar</label>
                                <input type="datetime-local" id="paid_at" name="paid_at"
                                    value="{{ old('paid_at') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status
                                    *</label>
                                <select id="status" name="status" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih Status</option>
                                    <option value="pending"
                                        {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>
                                        Processing</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Catatan tambahan untuk transaksi...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan Transaksi
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
                transactionType: @json(request('type', 'order')),
                productId: @json(old('product_id', '')),
                printingId: @json(old('printing_id', '')),
                quantity: {{ old('quantity', 1) }},
                unitPrice: {{ old('unit_price', 0) }},
                totalPrice: {{ old('total_price', 0) }},
                tinggi: {{ old('tinggi', 0) }},
                lebar: {{ old('lebar', 0) }},
                availableStock: null,
                showStockWarning: false,

                init() {
                    console.log('Form initialized with type:', this.transactionType);

                    // Update harga berdasarkan data old() jika ada
                    if (this.transactionType === 'purchase' && this.productId) {
                        this.updateProductPriceAndStock();
                    } else if (this.transactionType === 'order' && this.printingId) {
                        this.updateServicePrice();
                    }

                    this.calculateTotalPrice();
                    this.checkStock();
                },

                setTransactionType(type) {
                    this.transactionType = type;
                    this.productId = '';
                    this.printingId = '';
                    this.unitPrice = 0;
                    this.tinggi = 0;
                    this.lebar = 0;
                    this.availableStock = null;
                    this.showStockWarning = false;
                    this.calculateTotalPrice();

                    // Handle required attributes dengan delay
                    setTimeout(() => {
                        const productField = document.getElementById('product_id');
                        const printingField = document.getElementById('printing_id');
                        const tinggiField = document.getElementById('tinggi');
                        const lebarField = document.getElementById('lebar');

                        if (type === 'purchase') {
                            if (printingField) printingField.removeAttribute('required');
                            if (tinggiField) tinggiField.removeAttribute('required');
                            if (lebarField) lebarField.removeAttribute('required');
                            if (productField) productField.setAttribute('required', 'required');
                        } else {
                            if (productField) productField.removeAttribute('required');
                            if (printingField) printingField.setAttribute('required',
                                'required');
                            if (tinggiField) tinggiField.setAttribute('required', 'required');
                            if (lebarField) lebarField.setAttribute('required', 'required');
                        }
                    }, 50);
                },

                updateProductPriceAndStock() {
                    if (this.productId) {
                        const selectedOption = document.querySelector(
                            `#product_id option[value="${this.productId}"]`);
                        if (selectedOption) {
                            this.unitPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                            this.availableStock = parseInt(selectedOption.getAttribute('data-stock')) ||
                                0;
                        }
                    } else {
                        this.unitPrice = 0;
                        this.availableStock = null;
                    }
                    this.calculateTotalPrice();
                    this.checkStock();
                },

                updateServicePrice() {
                    if (this.printingId) {
                        const selectedOption = document.querySelector(
                            `#printing_id option[value="${this.printingId}"]`);
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
                        // Untuk order: (tinggi × lebar) × harga per cm × quantity
                        const luas = (parseFloat(this.tinggi) || 0) * (parseFloat(this.lebar) || 0);
                        this.totalPrice = luas * (parseFloat(this.unitPrice) || 0) * (parseInt(this
                            .quantity) || 1);
                    } else {
                        // Untuk purchase: unit price × quantity
                        this.totalPrice = (parseFloat(this.unitPrice) || 0) * (parseInt(this
                            .quantity) || 1);
                    }

                    // Round to nearest integer
                    this.totalPrice = Math.round(this.totalPrice);

                    // Check stock setelah calculate
                    this.checkStock();
                },

                checkStock() {
                    if (this.transactionType === 'purchase' && this.availableStock !== null) {
                        this.showStockWarning = parseInt(this.quantity) > this.availableStock;
                    } else {
                        this.showStockWarning = false;
                    }
                },

                formatNumber(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                }
            }));
        });

        // Custom form validation dengan validasi stok
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('transactionForm');

            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submitted');

                    // Cari section yang aktif
                    const purchaseSection = document.querySelector(
                        '[x-show="transactionType === \'purchase\'"]');
                    const orderSection = document.querySelector('[x-show="transactionType === \'order\'"]');

                    // Hapus required attribute dari field yang tersembunyi
                    if (purchaseSection && getComputedStyle(purchaseSection).display === 'none') {
                        const productField = document.getElementById('product_id');
                        if (productField) productField.removeAttribute('required');
                    }

                    if (orderSection && getComputedStyle(orderSection).display === 'none') {
                        const printingField = document.getElementById('printing_id');
                        const tinggiField = document.getElementById('tinggi');
                        const lebarField = document.getElementById('lebar');
                        if (printingField) printingField.removeAttribute('required');
                        if (tinggiField) tinggiField.removeAttribute('required');
                        if (lebarField) lebarField.removeAttribute('required');
                    }

                    // Validasi basic
                    const quantity = document.getElementById('quantity');
                    const totalPrice = document.getElementById('total_price');
                    const productId = document.getElementById('product_id');

                    if (quantity && parseFloat(quantity.value) <= 0) {
                        e.preventDefault();
                        alert('Jumlah harus lebih besar dari 0');
                        quantity.focus();
                        return false;
                    }

                    if (totalPrice && parseFloat(totalPrice.value) <= 0) {
                        e.preventDefault();
                        alert('Total harga harus lebih besar dari 0');
                        return false;
                    }

                    // Validasi stok untuk pembelian
                    if (purchaseSection && getComputedStyle(purchaseSection).display !== 'none' &&
                        productId) {
                        const selectedOption = productId.options[productId.selectedIndex];
                        if (selectedOption && selectedOption.value !== '') {
                            const availableStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                            const requestedQuantity = parseInt(quantity.value) || 0;

                            if (requestedQuantity > availableStock) {
                                e.preventDefault();
                                alert('Stok tidak mencukupi! Stok tersedia: ' + availableStock +
                                    ', yang diminta: ' + requestedQuantity);
                                quantity.focus();
                                return false;
                            }
                        }
                    }

                    return true;
                });
            }

            // Real-time stock validation on quantity change
            const quantityInput = document.getElementById('quantity');
            if (quantityInput) {
                quantityInput.addEventListener('input', function() {
                    const productId = document.getElementById('product_id');
                    if (productId && productId.value) {
                        const selectedOption = productId.options[productId.selectedIndex];
                        if (selectedOption) {
                            const availableStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                            const requestedQuantity = parseInt(this.value) || 0;

                            if (requestedQuantity > availableStock) {
                                this.setCustomValidity('Stok tidak mencukupi! Stok tersedia: ' +
                                    availableStock);
                            } else {
                                this.setCustomValidity('');
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layout.default>
