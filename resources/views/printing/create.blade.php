<x-layout.default>
    <div class="container mx-auto mt-4 max-w-4xl">
        <div class="mb-6">
            <h1 class="font-bold text-2xl text-gray-800">Tambah Layanan Printing</h1>
            <p class="text-gray-600 mt-1">Tambahkan layanan printing baru dengan ukuran dan harga yang sesuai</p>
        </div>
        
        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('printing.store') }}" method="POST" id="printing-form">
                @csrf
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="nama_layanan" class="block font-medium text-gray-700 mb-2">Nama Layanan *</label>
                        <input type="text" name="nama_layanan" id="nama_layanan" placeholder="Contoh: Cetak Foto, Cetak Banner"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            value="{{ old('nama_layanan') }}" required>
                    </div>
                    
                    <div>
                        <label for="hitungan" class="block font-medium text-gray-700 mb-2">Jenis Perhitungan</label>
                        <select name="hitungan" id="hitungan" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="per_lembar" {{ old('hitungan') == 'per_lembar' ? 'selected' : '' }}>Per Lembar</option>
                            <option value="per_cm2" {{ old('hitungan') == 'per_cm2' ? 'selected' : '' }}>Per cm²</option>
                            <option value="tetap" {{ old('hitungan') == 'tetap' ? 'selected' : '' }}>Harga Tetap</option>
                        </select>
                    </div>
                </div>

                <!-- Ukuran dan Harga -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <label class="block font-medium text-gray-700">Daftar Ukuran dan Harga *</label>
                        <button type="button" id="add-size" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Ukuran
                        </button>
                    </div>
                    
                    <div id="size-items" class="space-y-3">
                        <!-- Size item template -->
                        <div class="size-item border rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-start mb-3">
                                <span class="font-medium text-gray-700">Ukuran #1</span>
                                <button type="button" class="remove-size text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Nama Ukuran *</label>
                                    <input type="text" name="sizes[0][nama]" placeholder="Contoh: A4, A3, 10x15 cm"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Harga (Rp) *</label>
                                    <input type="number" name="sizes[0][harga]" min="0" step="100"
                                        placeholder="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden field untuk menyimpan data ukuran sebagai JSON -->
                    <input type="hidden" name="ukuran" id="ukuran" value="{{ old('ukuran', '[]') }}">
                </div>

                <!-- Biaya Dasar -->
                <div class="mb-6">
                    <label for="biaya" class="block font-medium text-gray-700 mb-2">Biaya Dasar (Rp)</label>
                    <input type="number" name="biaya" id="biaya" min="0" step="100"
                        placeholder="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        value="{{ old('biaya') }}" required>
                    <p class="text-sm text-gray-500 mt-1">Biaya dasar untuk perhitungan per cm² atau jika tidak ada ukuran khusus</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('printing.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Template untuk dynamic size items -->
    <template id="size-item-template">
        <div class="size-item border rounded-lg p-4 bg-gray-50">
            <div class="flex justify-between items-start mb-3">
                <span class="font-medium text-gray-700">Ukuran #<span class="size-index"></span></span>
                <button type="button" class="remove-size text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nama Ukuran *</label>
                    <input type="text" name="sizes[INDEX][nama]" placeholder="Contoh: A4, A3, 10x15 cm"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                </div>
                
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Harga (Rp) *</label>
                    <input type="number" name="sizes[INDEX][harga]" min="0" step="100"
                        placeholder="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                </div>
            </div>
        </div>
    </template>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let sizeCounter = 1;
        
        // Add size item
        document.getElementById('add-size').addEventListener('click', function() {
            const template = document.getElementById('size-item-template');
            const newItem = template.content.cloneNode(true);
            const index = sizeCounter++;
            
            // Update index placeholders
            newItem.querySelector('.size-index').textContent = index;
            newItem.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace('INDEX', index);
            });
            
            // Add remove functionality
            newItem.querySelector('.remove-size').addEventListener('click', function() {
                this.closest('.size-item').remove();
                updateUkuranField();
            });
            
            // Add input listeners
            const inputs = newItem.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('input', updateUkuranField);
            });
            
            document.getElementById('size-items').appendChild(newItem);
        });
        
        // Update ukuran hidden field
        function updateUkuranField() {
            const sizes = [];
            document.querySelectorAll('.size-item').forEach(item => {
                const nama = item.querySelector('input[name$="[nama]"]').value;
                const harga = item.querySelector('input[name$="[harga]"]').value;
                
                if (nama && harga) {
                    sizes.push({
                        nama: nama,
                        harga: parseFloat(harga) || 0
                    });
                }
            });
            
            document.getElementById('ukuran').value = JSON.stringify(sizes);
        }
        
        // Initialize event listeners for existing items
        document.querySelectorAll('.size-item input').forEach(input => {
            input.addEventListener('input', updateUkuranField);
        });
        
        document.querySelectorAll('.remove-size').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.size-item').remove();
                updateUkuranField();
            });
        });
        
        // Form validation
        document.getElementById('printing-form').addEventListener('submit', function(e) {
            // Validate at least one size
            const sizes = JSON.parse(document.getElementById('ukuran').value || '[]');
            if (sizes.length === 0) {
                e.preventDefault();
                alert('Harap tambahkan minimal satu ukuran!');
                return false;
            }
        });
        
        // Initial update
        updateUkuranField();
    });
    </script>
</x-layout.default>