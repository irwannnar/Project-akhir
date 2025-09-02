<x-layout.default>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .form-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Buat Pesanan {{ ucfirst($printingType) }} Printing</h1>
            <p class="text-gray-600">Isi formulir di bawah ini untuk membuat pesanan</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex justify-center mb-12">
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center">1</div>
                    <div class="mt-2 text-sm font-medium text-blue-500">Pilih Layanan</div>
                </div>
                <div class="w-16 h-1 bg-blue-500 mx-2"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center">2</div>
                    <div class="mt-2 text-sm font-medium text-blue-500">Isi Data</div>
                </div>
                <div class="w-16 h-1 bg-gray-300 mx-2"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center">3</div>
                    <div class="mt-2 text-sm font-medium text-gray-500">Konfirmasi</div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-section p-8 mb-8">
            <form action="/order" method="POST">
                @csrf
                <input type="hidden" name="printing_type" value="{{ $printingType }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Information -->
                    <div class="md:col-span-2">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pelanggan</h2>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2" for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2" for="phone">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2" for="email">Email</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2" for="address">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="3" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <!-- Order Information -->
                    <div class="md:col-span-2 mt-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Detail Pesanan</h2>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2" for="material">Jenis Material</label>
                        <select id="material" name="material" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Material</option>
                            <option value="kertas">Kertas</option>
                            <option value="plastik">Plastik</option>
                            <option value="kain">Kain</option>
                            <option value="vinyl">Vinyl</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2" for="quantity">Jumlah</label>
                        <input type="number" id="quantity" name="quantity" min="1" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2" for="width">Lebar (cm)</label>
                        <input type="number" id="width" name="width" min="1" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2" for="height">Tinggi (cm)</label>
                        <input type="number" id="height" name="height" min="1" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2" for="notes">Catatan Tambahan</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2" for="file">Unggah File Desain</label>
                        <input type="file" id="file" name="file" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="flex justify-between mt-8">
                    <a href="/" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200">
                        Kembali
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                        Buat Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout.default>