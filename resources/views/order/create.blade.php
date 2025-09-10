<x-layout.default>
<div class="py-6 min-h-screen max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Tambah Pesanan</h1>
        <a href="{{ route('order.index') }}"
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

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6" x-data="orderForm()">
        <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Kode Pesanan (Generated Automatically) -->
            <input type="hidden" name="order_code" value="{{ 'ORD' . time() }}">
            
            <!-- Informasi Pelanggan -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informasi Pelanggan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="customer_name" class="block text-gray-700 font-medium mb-2">Nama Pelanggan *</label>
                        <input 
                            type="text" 
                            id="customer_name" 
                            name="customer_name" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan nama pelanggan"
                            required
                            value="{{ old('customer_name') }}"
                        >
                    </div>
                    
                    <div>
                        <label for="customer_phone" class="block text-gray-700 font-medium mb-2">Telepon Pelanggan *</label>
                        <input 
                            type="text" 
                            id="customer_phone" 
                            name="customer_phone" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan nomor telepon"
                            required
                            value="{{ old('customer_phone') }}"
                        >
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="customer_email" class="block text-gray-700 font-medium mb-2">Email Pelanggan</label>
                    <input 
                        type="email" 
                        id="customer_email" 
                        name="customer_email" 
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan email pelanggan"
                        value="{{ old('customer_email') }}"
                    >
                </div>
                
                <div class="mb-4">
                    <label for="customer_address" class="block text-gray-700 font-medium mb-2">Alamat Pelanggan *</label>
                    <textarea 
                        id="customer_address" 
                        name="customer_address" 
                        rows="3"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan alamat lengkap pelanggan"
                        required
                    >{{ old('customer_address') }}</textarea>
                </div>
            </div>

            <!-- Detail Pesanan -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Detail Pesanan</h2>
                
                <div class="mb-4">
                    <label for="printing_id" class="block text-gray-700 font-medium mb-2">Jenis Layanan *</label>
                    <select 
                        id="printing_id" 
                        name="printing_id" 
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                        x-model="selectedService"
                        x-on:change="calculateTotal"
                    >
                        <option value="">Pilih Layanan</option>
                        @foreach($printing as $cetak)
                            <option 
                                value="{{ $cetak->id }}" 
                                data-price="{{ $cetak->biaya }}"
                                {{ old('printing_id') == $cetak->id ? 'selected' : '' }}
                            >
                                {{ $cetak->nama_layanan }} - Rp {{ number_format($cetak->biaya, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="material" class="block text-gray-700 font-medium mb-2">Material *</label>
                    <input 
                        type="text" 
                        id="material" 
                        name="material" 
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Contoh: PLA, ABS, Resin"
                        required
                        value="{{ old('material') }}"
                    >
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="quantity" class="block text-gray-700 font-medium mb-2">Jumlah *</label>
                        <input 
                            type="number" 
                            id="quantity" 
                            name="quantity" 
                            min="1" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Jumlah"
                            required
                            x-model="quantity"
                            x-on:change="calculateTotal"
                            value="{{ old('quantity', 1) }}"
                        >
                    </div>
                    
                    <div>
                        <label for="width" class="block text-gray-700 font-medium mb-2">Lebar (cm) *</label>
                        <input 
                            type="number" 
                            id="width" 
                            name="width" 
                            min="0.1" 
                            step="0.1"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00"
                            required
                            x-model="width"
                            x-on:change="calculateTotal"
                            value="{{ old('width', 1) }}"
                        >
                    </div>
                    
                    <div>
                        <label for="height" class="block text-gray-700 font-medium mb-2">Tinggi (cm) *</label>
                        <input 
                            type="number" 
                            id="height" 
                            name="height" 
                            min="0.1" 
                            step="0.1"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00"
                            required
                            x-model="height"
                            x-on:change="calculateTotal"
                            value="{{ old('height', 1) }}"
                        >
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="file_path" class="block text-gray-700 font-medium mb-2">Unggah File Desain</label>
                    <input 
                        type="file" 
                        id="file_path" 
                        name="file_path" 
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        accept=".stl,.obj,.3mf,.jpg,.jpeg,.png,.pdf"
                    >
                    <p class="text-sm text-gray-500 mt-1">Format yang diterima: STL, OBJ, 3MF, JPG, PNG, PDF</p>
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-gray-700 font-medium mb-2">Catatan Tambahan</label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="3"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan catatan tambahan untuk pesanan ini"
                    >{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Total Harga -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Total Harga</h2>
                
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-700 font-medium">Harga per item:</span>
                    <span x-text="formatCurrency(servicePrice)"></span>
                </div>
                
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-700 font-medium">Jumlah:</span>
                    <span x-text="quantity"></span>
                </div>
                
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-700 font-medium">Dimensi (cm):</span>
                    <span x-text="width + ' x ' + height"></span>
                </div>
                
                <div class="border-t border-gray-300 my-3 pt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">Total Harga:</span>
                        <span class="text-lg font-bold text-blue-600" x-text="formatCurrency(totalPrice)"></span>
                    </div>
                </div>
                
                <input type="hidden" name="total_price" x-bind:value="totalPrice">
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-gray-700 font-medium mb-2">Status Pesanan *</label>
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
                    class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition duration-300 font-medium"
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
            selectedService: '{{ old('printing_id', '') }}',
            quantity: {{ old('quantity', 1) }},
            width: {{ old('width', 1) }},
            height: {{ old('height', 1) }},
            servicePrice: 0,
            totalPrice: 0,
            
            init() {
                // Hitung total harga saat pertama kali load
                this.calculateTotal();
            },
            
            calculateTotal() {
                // Dapatkan harga layanan
                if (this.selectedService) {
                    const serviceSelect = document.getElementById('printing_id');
                    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                    if (selectedOption) {
                        this.servicePrice = parseFloat(selectedOption.getAttribute('data-price'));
                    }
                } else {
                    this.servicePrice = 0;
                }
                
                // Hitung total harga berdasarkan jumlah dan dimensi
                if (this.servicePrice > 0 && this.quantity > 0 && this.width > 0 && this.height > 0) {
                    const area = this.width * this.height;
                    this.totalPrice = this.servicePrice * this.quantity * area;
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