<x-layout.default>
    <div class="py-6 min-h-screen">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Tambah Pesanan</h1>
            <a href="{{ route('order.index') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-md hover-bg-gray-600">kembali ke daftar</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6" x-data="orderForm()">
            <form action="{{ route('order.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="customer_name" class="block text-gray-700 font-medium mv-2">Nama Pelanggan</label>
                    <input type="text" name="customer_name" id="customer_name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="masukkan nama pelanggan" required x-model="customerName" value="{{ old('customer_name') }}">
                </div>

                <div class="mb-4">
                        <label for="service_id" class="block text-gray-700 font-medium mb-2">Layanan</label>
                        <select 
                            id="service_id" 
                            name="service_id" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                            x-model="selectedService"
                            x-on:change="calculateTotal"
                        >
                            <option value="">Pilih Layanan</option>
                            @foreach($printing as $service)
                                <option 
                                    value="{{ $service->id }}" 
                                    data-price="{{ $service->price }}"
                                    {{ old('service_id') == $service->id ? 'selected' : '' }}
                                >
                                    {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700 font-medium mb-2">Jumlah</label>
                        <input 
                            type="number" 
                            id="quantity" 
                            name="quantity" 
                            min="1" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan jumlah"
                            required
                            x-model="quantity"
                            x-on:change="calculateTotal"
                            value="{{ old('quantity', 1) }}"
                        >
                    </div>

                    <!-- Total Harga (Otomatis Terisi) -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Total Harga</label>
                        <div class="p-2 bg-gray-100 rounded-md" x-text="formatCurrency(totalPrice)"></div>
                        <input type="hidden" name="total_price" x-bind:value="totalPrice">
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                        <select 
                            id="status" 
                            name="status" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-300"
                        >
                            Simpan Pesanan
                        </button>
                    </div>
            </form>
        </div>
    </div>

     <script>
            function orderForm() {
                return {
                    customerName: '{{ old('customer_name', '') }}',
                    selectedService: '{{ old('service_id', '') }}',
                    quantity: {{ old('quantity', 1) }},
                    totalPrice: 0,
                    
                    init() {
                        // Hitung total harga saat pertama kali load
                        this.calculateTotal();
                    },
                    
                    calculateTotal() {
                        if (this.selectedService && this.quantity > 0) {
                            const serviceSelect = document.getElementById('service_id');
                            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                            if (selectedOption) {
                                const price = selectedOption.getAttribute('data-price');
                                this.totalPrice = price * this.quantity;
                                return;
                            }
                        }
                        this.totalPrice = 0;
                    },
                    
                    formatCurrency(amount) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                    }
                }
            }
        </script>
</x-layout.default>
