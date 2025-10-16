<!-- edit.blade.php -->
<x-layout.default>
    <div class="py-6" x-data="transactionApp()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Edit Transaksi</h1>
                <p class="mt-2 text-sm text-gray-600">
                    {{ $transaction->type == 'purchase' ? 'Pembelian Produk' : 'Pesanan Layanan' }}
                </p>
            </div>

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Input Produk/Layanan -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Tambah Item
                        </h2>

                        <form id="addItemForm" @submit.prevent="addToCart()">
                            @csrf

                            <!-- Tipe Transaksi -->
                            <input type="hidden" name="type" value="{{ $transaction->type }}">

                            @if ($transaction->type == 'purchase')
                                <!-- Form untuk Pembelian Produk -->
                                <div class="mb-4">
                                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Pilih Produk *
                                    </label>
                                    <select id="product_id" x-model="form.product_id" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Produk</option>
                                        @foreach ($products as $product)
                                            <<option value="{{ $product->id }}"
                                                data-price="{{ $product->price_per_unit }}"
                                                data-stock="{{ $product->stock }}"
                                                {{ old('product_id', optional($transaction->items->first())->product_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - Rp
                                                {{ number_format($product->price_per_unit, 0, ',', '.') }}
                                                </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jumlah *
                                    </label>
                                    <input type="number" id="quantity" x-model="form.quantity" min="1"
                                        value="1" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <p id="stockInfo" class="text-xs text-gray-500 mt-1" x-text="stockInfo"></p>
                                </div>
                            @else
                                <!-- Form untuk Pesanan Layanan -->
                                <div class="mb-4">
                                    <label for="printing_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Pilih Layanan *
                                    </label>
                                    <select id="printing_id" x-model="form.printing_id" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Layanan</option>
                                        @foreach ($printings as $printing)
                                            <option value="{{ $printing->id }}" data-price="{{ $printing->biaya }}"
                                                data-hitungan="{{ $printing->hitungan }}"
                                                {{ old('printing_id', optional($transaction->items->first())->printing_id) == $printing->id ? 'selected' : '' }}>
                                                {{ $printing->nama_layanan }} - Rp
                                                {{ number_format($printing->biaya, 0, ',', '.') }}
                                                {{ $printing->hitungan }}
                                            </option>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="tinggi" class="block text-sm font-medium text-gray-700 mb-1">
                                            Tinggi (cm)
                                        </label>
                                        <input type="number" id="tinggi" x-model="form.tinggi" min="1"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Tinggi dalam cm">
                                    </div>
                                    <div>
                                        <label for="lebar" class="block text-sm font-medium text-gray-700 mb-1">
                                            Lebar (cm)
                                        </label>
                                        <input type="number" id="lebar" x-model="form.lebar" min="1"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Lebar dalam cm">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Perhitungan Harga
                                    </label>
                                    <div class="text-sm text-gray-600 p-2 bg-gray-50 rounded">
                                        <template x-if="form.tinggi && form.lebar">
                                            <div>
                                                Luas: <span x-text="calculateArea()"></span> cm²
                                            </div>
                                        </template>
                                        <div>
                                            Biaya per cm²: Rp <span x-text="formatCurrency(getBasePrice())"></span>
                                        </div>
                                        <template x-if="form.tinggi && form.lebar">
                                            <div>
                                                Harga per item: Rp <span
                                                    x-text="formatCurrency(calculateUnitPrice())"></span>
                                            </div>
                                        </template>
                                        <div class="font-semibold mt-1">
                                            Total Harga: Rp <span x-text="formatCurrency(calculateTotalPrice())"></span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Rumus: Biaya × Tinggi × Lebar × Jumlah
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="service_quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jumlah *
                                    </label>
                                    <input type="number" id="service_quantity" x-model="form.quantity" min="1"
                                        value="1" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="mb-4">
                                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                                        File Desain (Opsional)
                                    </label>
                                    <input type="file" id="file"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        accept=".jpg,.jpeg,.png,.pdf,.ai,.psd,.cdr">
                                </div>
                            @endif

                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Catatan (Opsional)
                                </label>
                                <textarea id="notes" x-model="form.notes" rows="3"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Tambahkan catatan khusus..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Keranjang dan Form Transaksi -->
                <div class="lg:col-span-2">
                    <!-- Keranjang -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Keranjang</h2>
                            <span x-text="`${cart.length} item${cart.length !== 1 ? 's' : ''}`"
                                class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                            </span>
                        </div>

                        <div class="space-y-4">
                            <template x-if="cart.length === 0">
                                <p class="text-gray-500 text-center py-8">
                                    Keranjang masih kosong. Tambahkan item terlebih dahulu.
                                </p>
                            </template>

                            <template x-for="(item, index) in cart" :key="item.id">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900"
                                            x-text="item.type === 'product' ? item.product_name : item.service_name">
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <template x-if="item.type === 'service' && item.tinggi && item.lebar">
                                                <span x-text="`${item.tinggi} x ${item.lebar} cm | `"></span>
                                            </template>
                                            <span
                                                x-text="`${item.quantity} x Rp ${safeFormatCurrency(item.price)}`"></span>
                                        </div>
                                        <template x-if="item.notes">
                                            <div class="text-xs text-gray-500 mt-1" x-text="item.notes"></div>
                                        </template>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="font-semibold text-gray-900"
                                            x-text="`Rp ${safeFormatCurrency(item.total_price)}`"></span>
                                        <button @click="removeFromCart(index)"
                                            class="text-red-600 hover:text-red-800 p-1 rounded-full hover:bg-red-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <template x-if="cart.length > 0">
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-600">Subtotal:</span>
                                    <span class="text-sm font-medium text-gray-900"
                                        x-text="`Rp ${safeFormatCurrency(cartSubtotal)}`"></span>
                                </div>
                                <div class="flex justify-between items-center text-lg font-semibold">
                                    <span>Total:</span>
                                    <span class="text-blue-600"
                                        x-text="`Rp ${safeFormatCurrency(cartSubtotal)}`"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Form Data Pelanggan dan Pembayaran -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Data Transaksi</h2>

                        <form id="transactionForm" action="{{ route('transaction.update', $transaction->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="{{ $transaction->type }}">
                            <input type="hidden" name="cart_items" :value="JSON.stringify(cart)">

                            <!-- Data Pelanggan -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Pelanggan *
                                    </label>
                                    <input type="text" id="customer_name" name="customer_name" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value="{{ old('customer_name', $transaction->customer_name) }}">
                                </div>
                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        No. Telepon *
                                    </label>
                                    <input type="tel" id="customer_phone" name="customer_phone"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value="{{ old('customer_phone', $transaction->customer_phone) }}">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email (Opsional)
                                    </label>
                                    <input type="email" id="customer_email" name="customer_email"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value="{{ old('customer_email', $transaction->customer_email) }}">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="customer_address"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        Alamat (Opsional)
                                    </label>
                                    <textarea id="customer_address" name="customer_address" rows="2"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('customer_address', $transaction->customer_address) }}</textarea>
                                </div>
                            </div>

                            <!-- Informasi Pembayaran -->
                            <div class="border-t border-gray-200 pt-6 mb-6">
                                <h3 class="text-md font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="payment_method"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Metode Pembayaran *
                                        </label>
                                        <select id="payment_method" name="payment_method" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Pilih Metode</option>
                                            <option value="cash"
                                                {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>
                                                Cash</option>
                                            <option value="transfer"
                                                {{ old('payment_method', $transaction->payment_method) == 'transfer' ? 'selected' : '' }}>
                                                Transfer
                                            </option>
                                            <option value="credit_card"
                                                {{ old('payment_method', $transaction->payment_method) == 'credit_card' ? 'selected' : '' }}>
                                                Kartu
                                                Kredit</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="paid_at" class="block text-sm font-medium text-gray-700 mb-1">
                                            Tanggal Pembayaran
                                        </label>
                                        <input type="datetime-local" id="paid_at" name="paid_at"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            value="{{ old('paid_at', $transaction->paid_at ? \Carbon\Carbon::parse($transaction->paid_at)->format('Y-m-d\TH:i') : '') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-6">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Status *
                                </label>
                                <select id="status" name="status" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pending"
                                        {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="completed"
                                        {{ old('status', $transaction->status) == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled"
                                        {{ old('status', $transaction->status) == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex justify-between space-x-3 pt-6 border-t border-gray-200">
                                <div>
                                    <button type="button" @click="confirmDelete()"
                                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        Hapus Transaksi
                                    </button>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="{{ route('transaction.index') }}?tab={{ $transaction->type == 'purchase' ? 'purchases' : 'orders' }}"
                                        class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                        Batal
                                    </a>
                                    <button type="submit" :disabled="cart.length === 0"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Update Transaksi
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Form Hapus Transaksi (Hidden) -->
                        <form id="deleteForm" action="{{ route('transaction.destroy', $transaction->id) }}"
                            method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function transactionApp() {
            return {
                cart: {!! json_encode(
                    $transaction->items->map(function ($item) use ($transaction) {
                            return [
                                'id' => $item->id,
                                'type' => $transaction->type == 'purchase' ? 'product' : 'service',
                                'product_id' => $item->product_id,
                                'printing_id' => $item->printing_id,
                                'product_name' => optional($item->product)->name,
                                'service_name' => optional($item->printing)->nama_layanan,
                                'quantity' => $item->quantity,
                                'tinggi' => $item->tinggi,
                                'lebar' => $item->lebar,
                                'price' => $item->unit_price,
                                'total_price' => $item->total_price,
                                'notes' => $item->notes,
                            ];
                        })->toArray(),
                    JSON_UNESCAPED_UNICODE,
                ) !!},
                form: {
                    product_id: '',
                    printing_id: '',
                    quantity: 1,
                    tinggi: '',
                    lebar: '',
                    notes: '',
                    file: null
                },
                stockInfo: '',

                init() {
                    this.setupEventListeners();
                    // Pastikan semua nilai numerik dalam cart sudah benar
                    this.normalizeCartData();
                },

                normalizeCartData() {
                    // Normalisasi data cart untuk memastikan tipe data numerik
                    this.cart = this.cart.map(item => {
                        return {
                            ...item,
                            quantity: parseInt(item.quantity) || 1,
                            price: parseFloat(item.price) || 0,
                            total_price: parseFloat(item.total_price) || 0,
                            tinggi: item.tinggi ? parseInt(item.tinggi) : null,
                            lebar: item.lebar ? parseInt(item.lebar) : null
                        };
                    });
                },

                setupEventListeners() {
                    const productSelect = document.getElementById('product_id');
                    if (productSelect) {
                        productSelect.addEventListener('change', (e) => {
                            const selectedOption = e.target.options[e.target.selectedIndex];
                            const stock = selectedOption.getAttribute('data-stock');
                            this.stockInfo = stock ? `Stok tersedia: ${stock}` : '';
                        });
                    }

                    const transactionForm = document.getElementById('transactionForm');
                    if (transactionForm) {
                        transactionForm.addEventListener('submit', (e) => {
                            if (this.cart.length === 0) {
                                e.preventDefault();
                                alert('Tambahkan minimal satu item ke keranjang!');
                                return;
                            }
                        });
                    }
                },

                getBasePrice() {
                    if (!this.form.printing_id) return 0;
                    const printingSelect = document.getElementById('printing_id');
                    const selectedOption = printingSelect.options[printingSelect.selectedIndex];
                    return parseFloat(selectedOption.getAttribute('data-price')) || 0;
                },

                calculateArea() {
                    if (!this.form.tinggi || !this.form.lebar) return 0;
                    const area = parseFloat(this.form.tinggi) * parseFloat(this.form.lebar);
                    return area.toFixed(2);
                },

                calculateUnitPrice() {
                    const basePrice = this.getBasePrice();
                    if (this.form.tinggi && this.form.lebar) {
                        const area = this.calculateArea();
                        return basePrice * parseFloat(area);
                    }
                    return basePrice;
                },

                calculateTotalPrice() {
                    const unitPrice = this.calculateUnitPrice();
                    return unitPrice * parseInt(this.form.quantity || 1);
                },

                addToCart() {
                    const type = "{{ $transaction->type }}";
                    let item = {};

                    if (type === 'purchase') {
                        if (!this.form.product_id) {
                            alert('Pilih produk terlebih dahulu!');
                            return;
                        }

                        const productSelect = document.getElementById('product_id');
                        const selectedOption = productSelect.options[productSelect.selectedIndex];
                        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;

                        if (this.form.quantity > stock) {
                            alert(`Stok tidak mencukupi! Stok tersedia: ${stock}`);
                            return;
                        }

                        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                        const quantity = parseInt(this.form.quantity);
                        const totalPrice = price * quantity;

                        item = {
                            id: `prod_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
                            type: 'product',
                            product_id: this.form.product_id,
                            product_name: selectedOption.text.split(' - ')[0],
                            quantity: quantity,
                            price: price,
                            total_price: totalPrice,
                            notes: this.form.notes ? this.form.notes.trim() : null,
                        };

                    } else {
                        if (!this.form.printing_id) {
                            alert('Pilih layanan terlebih dahulu!');
                            return;
                        }

                        const printingSelect = document.getElementById('printing_id');
                        const selectedOption = printingSelect.options[printingSelect.selectedIndex];

                        const basePrice = this.getBasePrice();
                        let unitPrice = basePrice;

                        if (this.form.tinggi && this.form.lebar) {
                            unitPrice = basePrice * parseFloat(this.form.tinggi) * parseFloat(this.form.lebar);
                        }

                        const quantity = parseInt(this.form.quantity);
                        const totalPrice = unitPrice * quantity;

                        item = {
                            id: `serv_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
                            type: 'service',
                            printing_id: this.form.printing_id,
                            service_name: selectedOption.text.split(' - ')[0],
                            quantity: quantity,
                            tinggi: this.form.tinggi ? parseInt(this.form.tinggi) : null,
                            lebar: this.form.lebar ? parseInt(this.form.lebar) : null,
                            price: unitPrice,
                            total_price: totalPrice,
                            notes: this.form.notes ? this.form.notes.trim() : null,
                        };
                    }

                    // Validasi data sebelum masuk cart
                    if (item.total_price <= 0) {
                        alert('Harga item tidak valid! Silakan periksa input Anda.');
                        return;
                    }

                    // Pastikan tipe data numerik
                    item.quantity = parseInt(item.quantity);
                    item.price = parseFloat(item.price);
                    item.total_price = parseFloat(item.total_price);

                    this.cart.push(item);
                    this.resetForm();
                    this.showToast('Item berhasil ditambahkan ke keranjang', 'success');
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.showToast('Item berhasil dihapus dari keranjang', 'info');
                },

                resetForm() {
                    this.form = {
                        product_id: '',
                        printing_id: '',
                        quantity: 1,
                        tinggi: '',
                        lebar: '',
                        notes: '',
                        file: null
                    };
                    this.stockInfo = '';
                },

                confirmDelete() {
                    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.')) {
                        document.getElementById('deleteForm').submit();
                    }
                },

                showToast(message, type = 'info') {
                    const toast = document.createElement('div');
                    toast.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg text-white ${
                    type === 'success' ? 'bg-green-500' : 
                    type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                }`;
                    toast.textContent = message;

                    document.body.appendChild(toast);

                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 3000);
                },

                // Fungsi formatCurrency yang aman
                formatCurrency(amount) {
                    if (typeof amount !== 'number' || isNaN(amount)) {
                        return '0';
                    }
                    return new Intl.NumberFormat('id-ID').format(amount.toFixed(0));
                },

                // Fungsi formatCurrency yang lebih aman untuk template
                safeFormatCurrency(amount) {
                    try {
                        const num = parseFloat(amount);
                        if (isNaN(num)) return '0';
                        return new Intl.NumberFormat('id-ID').format(num.toFixed(0));
                    } catch (error) {
                        return '0';
                    }
                },

                get cartSubtotal() {
                    return this.cart.reduce((total, item) => {
                        const itemTotal = parseFloat(item.total_price) || 0;
                        return total + itemTotal;
                    }, 0);
                }
            }
        }
    </script>
</x-layout.default>
