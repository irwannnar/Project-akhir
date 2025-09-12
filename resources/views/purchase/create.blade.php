<x-layout.default>
    <div class="py-6 min-h-screen max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Tambah Pembelian Baru</h1>
            <a href="{{ route('purchase.index') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition duration-300">
                Kembali ke Daftar
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6" x-data="purchaseForm()">
            <form action="{{ route('purchase.store') }}" method="POST">
                @csrf

                <!-- Informasi Pelanggan -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Pelanggan</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="customer_name" class="block text-gray-700 font-medium mb-2">Nama Pelanggan
                                *</label>
                            <input type="text" id="customer_name" name="customer_name"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan nama pelanggan" required value="{{ old('customer_name') }}">
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-gray-700 font-medium mb-2">Telepon
                                Pelanggan</label>
                            <input type="text" id="customer_phone" name="customer_phone"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan nomor telepon" value="{{ old('customer_phone') }}">
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="customer_email" class="block text-gray-700 font-medium mb-2">Email Pelanggan</label>
                        <input type="email" id="customer_email" name="customer_email"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan email pelanggan" value="{{ old('customer_email') }}">
                        @error('customer_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Informasi Produk -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Produk</h2>

                    <div class="mb-4">
                        <label for="product_id" class="block text-gray-700 font-medium mb-2">Pilih Produk *</label>
                        <select id="product_id" name="product_id"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required x-model="selectedProduct" x-on:change="calculateTotal">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($product as $produk)
                                <option value="{{ $produk->id }}" data-price="{{ $produk->price_per_unit }}"
                                    {{ old('product_id') == $produk->id ? 'selected' : '' }}>
                                    {{ $produk->name }} - Rp {{ number_format($produk->price_per_unit, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="quantity" class="block text-gray-700 font-medium mb-2">Jumlah *</label>
                            <input type="number" id="quantity" name="quantity" min="1"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Jumlah" required x-model="quantity" x-on:change="calculateTotal"
                                value="{{ old('quantity', 1) }}">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="total_price" class="block text-gray-700 font-medium mb-2">Total Harga</label>
                            <input type="text" id="total_price" name="total_price"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                                readonly x-bind:value="formatCurrency(totalPrice)">
                            <input type="hidden" name="total_price" x-bind:value="totalPrice">
                            @error('total_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Pembayaran -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Pembayaran</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="payment_method" class="block text-gray-700 font-medium mb-2">Metode Pembayaran
                                *</label>
                            <select id="payment_method" name="payment_method"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="cash" {{ old('payment_method', 'cash') == 'cash' ? 'selected' : '' }}>
                                    Cash</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>
                                    Transfer</option>
                                <option value="credit_card"
                                    {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit
                                </option>
                                <option value="debit_card"
                                    {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>Kartu Debit</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="paid_at" class="block text-gray-700 font-medium mb-2">Tanggal
                                Pembayaran</label>
                            <input type="datetime-local" id="paid_at" name="paid_at"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}">
                            @error('paid_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 font-medium mb-2">Status *</label>
                        <select id="status" name="status"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>
                                Processing</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('purchase.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition duration-300">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                        Simpan Pembelian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function purchaseForm() {
            return {
                selectedProduct: '{{ old('product_id', '') }}',
                quantity: {{ old('quantity', 1) }},
                productPrice: 0,
                totalPrice: 0,

                init() {
                    // Hitung total harga saat pertama kali load
                    this.calculateTotal();
                },

                calculateTotal() {
                    // Dapatkan harga produk
                    if (this.selectedProduct) {
                        const productSelect = document.getElementById('product_id');
                        const selectedOption = productSelect.options[productSelect.selectedIndex];
                        if (selectedOption) {
                            this.productPrice = parseFloat(selectedOption.getAttribute('data-price'));
                        }
                    } else {
                        this.productPrice = 0;
                    }

                    // Hitung total harga berdasarkan jumlah
                    if (this.productPrice > 0 && this.quantity > 0) {
                        this.totalPrice = this.productPrice * this.quantity;
                    } else {
                        this.totalPrice = 0;
                    }
                },

                formatCurrency(amount) {
                    if (isNaN(amount)) return 'Rp 0';
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount.toFixed(2));
                }
            }
        }
    </script>
</x-layout.default>
