<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Transaksi</h1>
                <p class="text-gray-600 text-sm mt-1">Buat transaksi pembelian atau pesanan baru</p>
            </div>
            <a href="{{ route('transaction.index') }}"
                class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Transaksi
            </a>
        </div>

        <!-- Form Section -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <form action="{{ route('transaction.store') }}" method="POST" class="p-6" id="transaction-form">
                @csrf

                <!-- Hidden field untuk type -->
                <input type="hidden" name="type" id="type" value="{{ old('type', 'purchase') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Transaction Type Toggle -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi</label>
                            <div class="flex space-x-2">
                                <button type="button" id="purchase-type"
                                    class="transaction-type-btn px-4 py-2 rounded-lg font-medium transition duration-200 bg-blue-600 text-white">
                                    Pembelian Produk
                                </button>
                                <button type="button" id="order-type"
                                    class="transaction-type-btn px-4 py-2 rounded-lg font-medium transition duration-200 bg-gray-300 text-gray-700 hover:bg-gray-400">
                                    Pesanan Cetak
                                </button>
                            </div>
                        </div>

                        <!-- Product Selection (for purchases) -->
                        <div id="product-section">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Produk</label>
                                <button type="button" id="add-product"
                                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Produk
                                </button>
                            </div>

                            <div id="product-items" class="space-y-4">
                                <!-- Product item template -->
                                <div class="product-item border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="font-medium text-gray-700">Produk #1</span>
                                        <button type="button" class="remove-product text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Pilih Produk *</label>
                                            <select name="product_items[0][product_id]"
                                                class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                                <option value="">Pilih Produk</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-price="{{ $product->price_per_unit }}"
                                                        {{ old('product_items.0.product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} - Rp
                                                        {{ number_format($product->price_per_unit, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Kuantitas *</label>
                                            <input type="number" name="product_items[0][quantity]"
                                                class="product-quantity w-full px-3 py-2 border border-gray-300 rounded-lg"
                                                min="1" value="{{ old('product_items.0.quantity', 1) }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Printing Selection (for orders) -->
                        <div id="printing-section" class="hidden">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Layanan Cetak</label>
                                <button type="button" id="add-printing"
                                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Layanan
                                </button>
                            </div>

                            <div id="printing-items" class="space-y-4">
                                <!-- Printing item template -->
                                <div class="printing-item border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="font-medium text-gray-700">Layanan #1</span>
                                        <button type="button" class="remove-printing text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Pilih Layanan *</label>
                                            <select name="printing_items[0][printing_id]"
                                                class="printing-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Pilih Layanan Cetak</option>
                                                @foreach ($printings as $printing)
                                                    <option value="{{ $printing->id }}"
                                                        data-sizes="{{ json_encode($printing->ukuran ?? []) }}"
                                                        data-base-price="{{ $printing->biaya ?? 0 }}"
                                                        {{ old('printing_items.0.printing_id') == $printing->id ? 'selected' : '' }}>
                                                        {{ $printing->nama_layanan }} - Mulai Rp
                                                        {{ number_format($printing->biaya ?? 0, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="printing-size-container">
                                            <label class="block text-sm text-gray-600 mb-1">Ukuran *</label>
                                            <select name="printing_items[0][size]"
                                                class="printing-size w-full px-3 py-2 border border-gray-300 rounded-lg"
                                                required>
                                                <option value="">Pilih Ukuran</option>
                                                <!-- Options akan diisi oleh JavaScript -->
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Kuantitas *</label>
                                            <input type="number" name="printing_items[0][quantity]"
                                                class="printing-quantity w-full px-3 py-2 border border-gray-300 rounded-lg"
                                                min="1" value="{{ old('printing_items.0.quantity', 1) }}"
                                                required>
                                        </div>

                                        <div class="material-field">
                                            <label class="block text-sm text-gray-600 mb-1">Material</label>
                                            <input type="text" name="printing_items[0][material]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                                placeholder="Jenis material yang digunakan"
                                                value="{{ old('printing_items.0.material') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Customer Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Pelanggan</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="customer_name"
                                        class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan *</label>
                                    <input type="text" name="customer_name" id="customer_name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                        value="{{ old('customer_name') }}" required>
                                </div>

                                <div>
                                    <label for="customer_phone"
                                        class="block text-sm font-medium text-gray-700 mb-2">Telepon Pelanggan</label>
                                    <input type="text" name="customer_phone" id="customer_phone"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                        value="{{ old('customer_phone') }}">
                                </div>

                                <div>
                                    <label for="customer_email"
                                        class="block text-sm font-medium text-gray-700 mb-2">Email Pelanggan</label>
                                    <input type="email" name="customer_email" id="customer_email"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                        value="{{ old('customer_email') }}">
                                </div>

                                <div>
                                    <label for="customer_address"
                                        class="block text-sm font-medium text-gray-700 mb-2">Alamat Pelanggan</label>
                                    <textarea name="customer_address" id="customer_address" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">{{ old('customer_address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Status -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Pembayaran & Status</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="payment_method"
                                        class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran
                                        *</label>
                                    <select name="payment_method" id="payment_method"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                        required>
                                        <option value="cash"
                                            {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                        <option value="transfer"
                                            {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer
                                        </option>
                                        <option value="credit_card"
                                            {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status
                                        *</label>
                                    <select name="status" id="status"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                        required>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="processing"
                                            {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed"
                                            {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled"
                                            {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Section -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Ringkasan Transaksi</h3>

                            <div id="summary-content" class="space-y-2 text-sm text-gray-700">
                                <p>Pilih produk atau layanan untuk melihat ringkasan</p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-blue-200">
                                <div class="flex justify-between items-center font-semibold">
                                    <span>Total Harga:</span>
                                    <span id="total-price">Rp 0</span>
                                </div>
                                <input type="hidden" name="total_price" id="total_price_input" value="0"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 font-medium flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Templates for dynamic items -->
    <template id="product-item-template">
        <div class="product-item border rounded-lg p-4 bg-gray-50">
            <div class="flex justify-between items-start mb-3">
                <span class="font-medium text-gray-700">Produk #<span class="product-index"></span></span>
                <button type="button" class="remove-product text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Pilih Produk *</label>
                    <select name="product_items[INDEX][product_id]"
                        class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price_per_unit }}">
                                {{ $product->name }} - Rp {{ number_format($product->price_per_unit, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Kuantitas *</label>
                    <input type="number" name="product_items[INDEX][quantity]"
                        class="product-quantity w-full px-3 py-2 border border-gray-300 rounded-lg" min="1"
                        value="1" required>
                </div>
            </div>
        </div>
    </template>

    <template id="printing-item-template">
        <div class="printing-item border rounded-lg p-4 bg-gray-50">
            <div class="flex justify-between items-start mb-3">
                <span class="font-medium text-gray-700">Layanan #<span class="printing-index"></span></span>
                <button type="button" class="remove-printing text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Pilih Layanan *</label>
                    <select name="printing_items[INDEX][printing_id]"
                        class="printing-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="">Pilih Layanan Cetak</option>
                        @foreach ($printings as $printing)
                            <option value="{{ $printing->id }}"
                                data-sizes="{{ json_encode($printing->ukuran ?? []) }}"
                                data-base-price="{{ $printing->biaya ?? 0 }}">
                                {{ $printing->nama_layanan }} - Mulai Rp
                                {{ number_format($printing->biaya ?? 0, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="printing-size-container">
                    <label class="block text-sm text-gray-600 mb-1">Ukuran *</label>
                    <select name="printing_items[INDEX][size]"
                        class="printing-size w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                        <option value="">Pilih Ukuran</option>
                        <!-- Options akan diisi oleh JavaScript -->
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Kuantitas *</label>
                    <input type="number" name="printing_items[INDEX][quantity]"
                        class="printing-quantity w-full px-3 py-2 border border-gray-300 rounded-lg" min="1"
                        value="1" required>
                </div>

                <div class="material-field">
                    <label class="block text-sm text-gray-600 mb-1">Material</label>
                    <input type="text" name="printing_items[INDEX][material]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        placeholder="Jenis material yang digunakan">
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize counters
            let productCounter = 1;
            let printingCounter = 1;

            // Toggle between product and printing sections
            const purchaseBtn = document.getElementById('purchase-type');
            const orderBtn = document.getElementById('order-type');
            const typeInput = document.getElementById('type');
            const productSection = document.getElementById('product-section');
            const printingSection = document.getElementById('printing-section');

            function setTransactionType(type) {
                typeInput.value = type;

                if (type === 'purchase') {
                    purchaseBtn.classList.remove('bg-gray-300', 'text-gray-700');
                    purchaseBtn.classList.add('bg-blue-600', 'text-white');
                    orderBtn.classList.remove('bg-blue-600', 'text-white');
                    orderBtn.classList.add('bg-gray-300', 'text-gray-700');
                    productSection.classList.remove('hidden');
                    printingSection.classList.add('hidden');
                } else {
                    purchaseBtn.classList.remove('bg-blue-600', 'text-white');
                    purchaseBtn.classList.add('bg-gray-300', 'text-gray-700');
                    orderBtn.classList.remove('bg-gray-300', 'text-gray-700');
                    orderBtn.classList.add('bg-blue-600', 'text-white');
                    productSection.classList.add('hidden');
                    printingSection.classList.remove('hidden');
                }
                calculateTotalPrice();
            }

            purchaseBtn.addEventListener('click', () => setTransactionType('purchase'));
            orderBtn.addEventListener('click', () => setTransactionType('order'));

            // Set initial type
            setTransactionType('{{ old('type', 'purchase') }}');

            // Add product item
            document.getElementById('add-product').addEventListener('click', function() {
                const template = document.getElementById('product-item-template');
                const newItem = template.content.cloneNode(true);
                const index = productCounter++;

                // Update index placeholders
                newItem.querySelector('.product-index').textContent = index;
                newItem.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace('INDEX', index);
                });

                // Add event listeners
                const select = newItem.querySelector('.product-select');
                const quantity = newItem.querySelector('.product-quantity');

                select.addEventListener('change', calculateTotalPrice);
                quantity.addEventListener('input', calculateTotalPrice);

                // Add remove functionality
                newItem.querySelector('.remove-product').addEventListener('click', function() {
                    this.closest('.product-item').remove();
                    calculateTotalPrice();
                });

                document.getElementById('product-items').appendChild(newItem);
            });

            // Add printing item
            document.getElementById('add-printing').addEventListener('click', function() {
                const template = document.getElementById('printing-item-template');
                const newItem = template.content.cloneNode(true);
                const index = printingCounter++;

                // Update index placeholders
                newItem.querySelector('.printing-index').textContent = index;
                newItem.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace('INDEX', index);
                });

                // Add event listeners
                const select = newItem.querySelector('.printing-select');
                const sizeSelect = newItem.querySelector('.printing-size');
                const quantity = newItem.querySelector('.printing-quantity');

                select.addEventListener('change', function() {
                    updateSizeOptions(this, sizeSelect);
                    calculateTotalPrice();
                });

                sizeSelect.addEventListener('change', calculateTotalPrice);
                quantity.addEventListener('input', calculateTotalPrice);

                // Add remove functionality
                newItem.querySelector('.remove-printing').addEventListener('click', function() {
                    this.closest('.printing-item').remove();
                    calculateTotalPrice();
                });

                document.getElementById('printing-items').appendChild(newItem);
            });

            // Function to update size options
            function updateSizeOptions(selectElement, sizeSelect) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const sizes = selectedOption ? JSON.parse(selectedOption.getAttribute('data-sizes') || '[]') : [];
                
                // Clear existing options
                sizeSelect.innerHTML = '<option value="">Pilih Ukuran</option>';
                
                // Add new options
                sizes.forEach(size => {
                    const option = document.createElement('option');
                    option.value = size.nama;
                    option.textContent = `${size.nama} - Rp ${formatCurrency(size.harga)}`;
                    option.setAttribute('data-price', size.harga);
                    sizeSelect.appendChild(option);
                });
            }

            // Initialize size options for existing printing items
            document.querySelectorAll('.printing-select').forEach(select => {
                const sizeSelect = select.closest('.printing-item').querySelector('.printing-size');
                if (select.value) {
                    updateSizeOptions(select, sizeSelect);
                }
                
                select.addEventListener('change', function() {
                    updateSizeOptions(this, sizeSelect);
                    calculateTotalPrice();
                });
            });

            // Add event listeners for existing elements
            document.querySelectorAll('.product-select, .product-quantity').forEach(el => {
                el.addEventListener('change', calculateTotalPrice);
                el.addEventListener('input', calculateTotalPrice);
            });

            document.querySelectorAll('.printing-size, .printing-quantity').forEach(el => {
                el.addEventListener('change', calculateTotalPrice);
                el.addEventListener('input', calculateTotalPrice);
            });

            document.querySelectorAll('.remove-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.product-item').remove();
                    calculateTotalPrice();
                });
            });

            document.querySelectorAll('.remove-printing').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.printing-item').remove();
                    calculateTotalPrice();
                });
            });

            // Calculate total price
            function calculateTotalPrice() {
                let total = 0;
                let summaryHTML = '';

                if (typeInput.value === 'purchase') {
                    // Calculate product total
                    document.querySelectorAll('.product-item').forEach((item, index) => {
                        const select = item.querySelector('.product-select');
                        const quantityInput = item.querySelector('.product-quantity');

                        if (select.value && quantityInput.value) {
                            const selectedOption = select.options[select.selectedIndex];
                            const price = selectedOption ? parseFloat(selectedOption.getAttribute('data-price')) || 0 : 0;
                            const quantity = parseInt(quantityInput.value) || 0;
                            const itemTotal = price * quantity;

                            total += itemTotal;

                            summaryHTML += `
                            <div class="flex justify-between">
                                <span>${selectedOption.text.split(' - ')[0]} (${quantity} pcs)</span>
                                <span>Rp ${formatCurrency(itemTotal)}</span>
                            </div>
                        `;
                        }
                    });
                } else {
                    // Calculate printing total
                    document.querySelectorAll('.printing-item').forEach((item, index) => {
                        const select = item.querySelector('.printing-select');
                        const sizeSelect = item.querySelector('.printing-size');
                        const quantityInput = item.querySelector('.printing-quantity');

                        if (select.value && quantityInput.value) {
                            const selectedOption = select.options[select.selectedIndex];
                            const basePrice = selectedOption ? parseFloat(selectedOption.getAttribute('data-base-price')) || 0 : 0;
                            const quantity = parseInt(quantityInput.value) || 0;
                            let itemTotal = 0;
                            let itemDescription = selectedOption.text.split(' - ')[0];

                            if (sizeSelect && sizeSelect.value) {
                                const sizeOption = sizeSelect.options[sizeSelect.selectedIndex];
                                const sizePrice = sizeOption ? parseFloat(sizeOption.getAttribute('data-price')) || 0 : 0;
                                itemTotal = sizePrice * quantity;
                                itemDescription += ` - ${sizeOption.value}`;
                            } else {
                                itemTotal = basePrice * quantity;
                            }

                            total += itemTotal;

                            summaryHTML += `
                            <div class="flex justify-between">
                                <span>${itemDescription} (${quantity} pcs)</span>
                                <span>Rp ${formatCurrency(itemTotal)}</span>
                            </div>
                        `;
                        }
                    });
                }

                // Update summary and total
                document.getElementById('summary-content').innerHTML = summaryHTML ||
                    '<p>Pilih produk atau layanan untuk melihat ringkasan</p>';
                document.getElementById('total-price').textContent = `Rp ${formatCurrency(total)}`;
                document.getElementById('total_price_input').value = total;
            }

            // Format currency helper
            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID').format(amount);
            }

            // Initial calculation
            calculateTotalPrice();

            // Form validation
            document.getElementById('transaction-form').addEventListener('submit', function(e) {
                const type = document.getElementById('type').value;
                
                if (type === 'purchase') {
                    const productItems = document.querySelectorAll('.product-item');
                    if (productItems.length === 0) {
                        e.preventDefault();
                        alert('Silakan tambahkan minimal satu produk');
                        return;
                    }
                } else {
                    const printingItems = document.querySelectorAll('.printing-item');
                    if (printingItems.length === 0) {
                        e.preventDefault();
                        alert('Silakan tambahkan minimal satu layanan cetak');
                        return;
                    }
                }
            });
        });
    </script>
</x-layout.default>