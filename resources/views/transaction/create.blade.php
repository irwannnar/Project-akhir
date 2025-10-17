<!-- create.blade.php -->
<x-layout.default>
    <div class="py-6" x-data="transactionApp()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Tambah Transaksi Baru</h1>
                <p class="mt-2 text-sm text-gray-600">
                    {{ request('type') == 'purchase' ? 'Pembelian Produk' : 'Pesanan Layanan' }}
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
                            <input type="hidden" name="type" value="{{ request('type') }}">

                            @if (request('type') == 'purchase')
                                <!-- Form untuk Pembelian Produk -->
                                <div class="mb-4">
                                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Pilih Produk *
                                    </label>
                                    <select id="product_id" x-model="form.product_id" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Produk</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                data-price="{{ $product->price_per_unit }}"
                                                data-stock="{{ $product->stock }}">
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
                                                data-hitungan="{{ $printing->hitungan }}">
                                                {{ $printing->nama_layanan }} - Rp
                                                {{ number_format($printing->biaya, 0, ',', '.') }}
                                                {{ $printing->hitungan }}
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
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95">
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
                                                x-text="`${item.quantity} x Rp ${formatCurrency(item.price)}`"></span>
                                        </div>
                                        <template x-if="item.notes">
                                            <div class="text-xs text-gray-500 mt-1" x-text="item.notes"></div>
                                        </template>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="font-semibold text-gray-900"
                                            x-text="`Rp ${formatCurrency(item.total_price)}`"></span>
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
                                        x-text="`Rp ${formatCurrency(cartSubtotal)}`"></span>
                                </div>
                                <div class="flex justify-between items-center text-lg font-semibold">
                                    <span>Total:</span>
                                    <span class="text-blue-600" x-text="`Rp ${formatCurrency(cartSubtotal)}`"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Form Data Pelanggan dan Pembayaran -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Data Transaksi</h2>

                        <form id="transactionForm" action="{{ route('transaction.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="{{ request('type') }}">
                            <input type="hidden" name="cart_items" :value="JSON.stringify(cart)">

                            <!-- Data Pelanggan -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Pelanggan *
                                    </label>
                                    <input type="text" id="customer_name" name="customer_name" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value="{{ old('customer_name') }}">
                                </div>
                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        No. Telepon (Opsional)
                                    </label>
                                    <input type="tel" id="customer_phone" name="customer_phone"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value="{{ old('customer_phone') }}">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email (Opsional)
                                    </label>
                                    <input type="email" id="customer_email" name="customer_email"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        value="{{ old('customer_email') }}">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="customer_address"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        Alamat (Opsional)
                                    </label>
                                    <textarea id="customer_address" name="customer_address" rows="2"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('customer_address') }}</textarea>
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
                                                {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="transfer"
                                                {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer
                                            </option>
                                            <option value="credit_card"
                                                {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu
                                                Kredit</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="paid_at" class="block text-sm font-medium text-gray-700 mb-1">
                                            Tanggal Pembayaran
                                        </label>
                                        <input type="datetime-local" id="paid_at" name="paid_at"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            value="{{ old('paid_at') }}">
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
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                </select>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <a href="{{ route('transaction.index') }}?tab={{ request('type') == 'purchase' ? 'purchases' : 'orders' }}"
                                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95">
                                    Batal
                                </a>
                                <button type="submit" :disabled="cart.length === 0"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95">
                                    Buat Transaksi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function transactionApp() {
            return {
                cart: [],
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
                productsStock: {}, // <- map stok produk

                init() {
                    this.setupEventListeners();
                    // Inisialisasi productsStock dari option <select>
                    const productSelect = document.getElementById('product_id');
                    if (productSelect) {
                        for (let i = 0; i < productSelect.options.length; i++) {
                            const opt = productSelect.options[i];
                            const id = opt.value;
                            if (!id) continue;
                            const stock = parseInt(opt.getAttribute('data-stock')) || 0;
                            this.productsStock[id] = stock;
                        }
                    }
                },

                setupEventListeners() {
                    const productSelect = document.getElementById('product_id');
                    if (productSelect) {
                        productSelect.addEventListener('change', (e) => {
                            const selectedOption = e.target.options[e.target.selectedIndex];
                            const stock = selectedOption.getAttribute('data-stock');
                            // gunakan productsStock agar sinkron dengan cart
                            const id = selectedOption.value;
                            this.stockInfo = id ? `Stok tersedia: ${this.productsStock[id] ?? 0}` : '';
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

                updateProductStockDisplay(productId) {
                    // update atribut data-stock pada option agar select selalu menampilkan stok terbaru
                    const productSelect = document.getElementById('product_id');
                    if (!productSelect || !productId) return;
                    for (let i = 0; i < productSelect.options.length; i++) {
                        const opt = productSelect.options[i];
                        if (opt.value == productId) {
                            opt.setAttribute('data-stock', this.productsStock[productId]);
                            break;
                        }
                    }
                    // update stockInfo bila produk yang dipilih sama
                    const sel = productSelect.options[productSelect.selectedIndex];
                    if (sel && sel.value == productId) {
                        this.stockInfo = `Stok tersedia: ${this.productsStock[productId] ?? 0}`;
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
                    const type = "{{ request('type') }}";
                    let item = {};

                    if (type === 'purchase') {
                        if (!this.form.product_id) {
                            alert('Pilih produk terlebih dahulu!');
                            return;
                        }

                        const productSelect = document.getElementById('product_id');
                        const selectedOption = productSelect.options[productSelect.selectedIndex];
                        const stock = parseInt(this.productsStock[this.form.product_id] || 0);

                        const quantity = parseInt(this.form.quantity);
                        if (quantity > stock) {
                            alert(`Stok tidak mencukupi! Stok tersedia: ${stock}`);
                            return;
                        }

                        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
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

                        this.productsStock[this.form.product_id] = Math.max(0, (this.productsStock[this.form.product_id] ||
                            0) - quantity);
                        this.updateProductStockDisplay(this.form.product_id);

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

                    this.cart.push(item);
                    this.resetForm();
                    this.showToast('Item berhasil ditambahkan ke keranjang', 'success');
                },

                removeFromCart(index) {
                    const item = this.cart[index];
                    // kembalikan stok pada UI jika item produk
                    if (item && item.type === 'product' && item.product_id) {
                        this.productsStock[item.product_id] = (this.productsStock[item.product_id] || 0) + (item.quantity ||
                            0);
                        this.updateProductStockDisplay(item.product_id);
                    }

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

                formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID').format(amount.toFixed(0));
                },

                get cartSubtotal() {
                    return this.cart.reduce((total, item) => total + item.total_price, 0);
                }
            }
        }
    </script>
</x-layout.default>
