<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Transaksi</h1>
                <p class="text-gray-600 text-sm mt-1">Ubah data transaksi pembelian atau pesanan</p>
            </div>
            <a href="{{ route('transaction.index') }}" 
               class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Transaksi
            </a>
        </div>

        <!-- Form Section -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <form action="{{ route('transaction.update', $transaction->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Transaction Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi</label>
                            <select name="type" id="type" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="purchase" {{ $transaction->type == 'purchase' ? 'selected' : '' }}>Pembelian Produk</option>
                                <option value="order" {{ $transaction->type == 'order' ? 'selected' : '' }}>Pesanan Cetak</option>
                            </select>
                        </div>

                        <!-- Product Selection -->
                        <div id="product-field" class="{{ $transaction->type == 'purchase' ? '' : 'hidden' }}">
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                            <select name="product_id" id="product_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Pilih Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $transaction->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - Rp {{ number_format($product->price_per_unit, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Printing Selection -->
                        <div id="printing-field" class="{{ $transaction->type == 'order' ? '' : 'hidden' }}">
                            <label for="printing_id" class="block text-sm font-medium text-gray-700 mb-2">Jenis Cetak</label>
                            <select name="printing_id" id="printing_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Pilih Jenis Cetak</option>
                                @foreach($printings as $printing)
                                    <option value="{{ $printing->id }}" {{ $transaction->printing_id == $printing->id ? 'selected' : '' }}>
                                        {{ $printing->nama_layanan }} - Rp {{ number_format($printing->biaya, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Kuantitas</label>
                            <input type="number" name="quantity" id="quantity" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   value="{{ $transaction->quantity }}" min="1" required 
                                   onchange="calculateTotalPrice()">
                        </div>

                        <!-- Total Price -->
                        <div>
                            <label for="total_price" class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="total_price" id="total_price" 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                       value="{{ $transaction->total_price }}" min="0" required readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Customer Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Pelanggan</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan *</label>
                                    <input type="text" name="customer_name" id="customer_name" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           value="{{ $transaction->customer_name }}" required>
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Telepon Pelanggan</label>
                                    <input type="text" name="customer_phone" id="customer_phone" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           value="{{ $transaction->customer_phone }}">
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Email Pelanggan</label>
                                    <input type="email" name="customer_email" id="customer_email" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                           value="{{ $transaction->customer_email }}">
                                </div>

                                <div>
                                    <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Pelanggan</label>
                                    <textarea name="customer_address" id="customer_address" rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">{{ $transaction->customer_address }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <option value="cash" {{ $transaction->payment_method == 'cash' ? 'selected' : '' }}>Tunai</option>
                                    <option value="transfer" {{ $transaction->payment_method == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="credit_card" {{ $transaction->payment_method == 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" id="status" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $transaction->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 font-medium flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Perbarui Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Toggle between product and printing fields based on transaction type
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        const productField = document.getElementById('product-field');
        const printingField = document.getElementById('printing-field');

        if (type === 'purchase') {
            productField.classList.remove('hidden');
            printingField.classList.add('hidden');
        } else {
            productField.classList.add('hidden');
            printingField.classList.remove('hidden');
        }
        
        calculateTotalPrice();
    });

    // Calculate total price based on selected product/printing and quantity
    function calculateTotalPrice() {
        const type = document.getElementById('type').value;
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        let pricePerUnit = 0;

        if (type === 'purchase') {
            const productSelect = document.getElementById('product_id');
            const selectedProduct = productSelect.options[productSelect.selectedIndex];
            pricePerUnit = selectedProduct ? parseFloat(selectedProduct.text.split('Rp ')[1]?.replace(/\./g, '')) || 0 : 0;
        } else {
            const printingSelect = document.getElementById('printing_id');
            const selectedPrinting = printingSelect.options[printingSelect.selectedIndex];
            pricePerUnit = selectedPrinting ? parseFloat(selectedPrinting.text.split('Rp ')[1]?.replace(/\./g, '')) || 0 : 0;
        }

        const totalPrice = pricePerUnit * quantity;
        document.getElementById('total_price').value = totalPrice;
    }

    // Add event listeners for product/printing selection and quantity changes
    document.getElementById('product_id').addEventListener('change', calculateTotalPrice);
    document.getElementById('printing_id').addEventListener('change', calculateTotalPrice);
    document.getElementById('quantity').addEventListener('input', calculateTotalPrice);

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial field visibility based on transaction type
        const type = document.getElementById('type').value;
        const productField = document.getElementById('product-field');
        const printingField = document.getElementById('printing-field');

        if (type === 'purchase') {
            printingField.classList.add('hidden');
        } else {
            productField.classList.add('hidden');
        }
        
        calculateTotalPrice();
    });
    </script>
</x-layout.default>