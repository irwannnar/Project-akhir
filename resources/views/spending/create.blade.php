<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Tambah Pengeluaran Baru</h1>
                <p class="text-gray-600">Isi form berikut untuk menambahkan data pengeluaran baru</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('spending.store') }}" method="POST" id="spendingForm">
                    @csrf
                    
                    <!-- Nama Pengeluaran -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pengeluaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name')  @enderror"
                               placeholder="Contoh: perbaikan mesin, restock barang, dll."
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="mb-6">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" 
                                id="category"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category')  @enderror"
                                required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah dan Kuantitas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Jumlah -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="number" 
                                       name="amount" 
                                       id="amount"
                                       value="{{ old('amount') }}"
                                       step="0.01"
                                       min="0.01"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount')  @enderror"
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kuantitas -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Kuantitas <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity"
                                   value="{{ old('quantity', 1) }}"
                                   step="0.01"
                                   min="0.01"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity')  @enderror"
                                   placeholder="1"
                                   required>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Metode Pembayaran dan Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Metode Pembayaran -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="payment_method" 
                                    id="payment_method"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method')  @enderror"
                                    required>
                                <option value="">Pilih Metode</option>
                                @foreach($paymentMethods as $key => $value)
                                    <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Pengeluaran -->
                        <div>
                            <label for="spending_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Pengeluaran <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="spending_date" 
                                   id="spending_date"
                                   value="{{ old('spending_date', date('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('spending_date')  @enderror"
                                   required>
                            @error('spending_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi (Opsional)
                        </label>
                        <textarea name="description" 
                                  id="description"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description')  @enderror"
                                  placeholder="Tambahkan deskripsi atau catatan tentang pengeluaran ini">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Pengeluaran
                        </button>
                        
                        <a href="{{ route('spending.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2 text-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set tanggal maksimum ke hari ini
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing form...');
            
            // Set max date for spending_date
            const spendingDateElement = document.getElementById('spending_date');
            if (spendingDateElement) {
                spendingDateElement.max = new Date().toISOString().split('T')[0];
                console.log('Spending date max set to:', spendingDateElement.max);
            } else {
                console.warn('Element with id "spending_date" not found');
            }
            
            // Auto-focus pada input pertama
            const nameInput = document.getElementById('name');
            if (nameInput) {
                nameInput.focus();
                console.log('Auto-focused on name input');
            } else {
                console.warn('Element with id "name" not found');
            }
            
            // Format input jumlah saat kehilangan fokus
            const amountInput = document.getElementById('amount');
            if (amountInput) {
                amountInput.addEventListener('blur', function(e) {
                    const value = parseFloat(e.target.value);
                    console.log('Amount blur event, value:', value);
                    if (!isNaN(value) && value > 0) {
                        e.target.value = value.toFixed(2);
                        console.log('Formatted amount to:', e.target.value);
                    }
                });
            } else {
                console.warn('Element with id "amount" not found');
            }
            
            // Validasi form sebelum submit
            // const spendingForm = document.getElementById('spendingForm');
            // if (spendingForm) {
            //     spendingForm.addEventListener('submit', function(e) {
            //         console.log('Form submit event triggered');
                    
            //         const amountElement = document.getElementById('amount');
            //         const quantityElement = document.getElementById('quantity');
            //         const spendingDateElement = document.getElementById('spending_date');
                    
            //         if (!amountElement || !quantityElement || !spendingDateElement) {
            //             console.error('Required form elements not found');
            //             return true; // Biarkan server yang handle validation
            //         }
                    
            //         const amount = amountElement.value;
            //         const quantity = quantityElement.value;
            //         const spendingDate = spendingDateElement.value;
                    
            //         console.log('Validating - Amount:', amount, 'Quantity:', quantity, 'Date:', spendingDate);
                    
            //         // Validasi amount
            //         if (!amount || parseFloat(amount) <= 0) {
            //             e.preventDefault();
            //             alert('Jumlah harus lebih besar dari 0');
            //             amountElement.focus();
            //             return false;
            //         }
                    
            //         // Validasi quantity
            //         if (!quantity || parseFloat(quantity) <= 0) {
            //             e.preventDefault();
            //             alert('Kuantitas harus lebih besar dari 0');
            //             quantityElement.focus();
            //             return false;
            //         }
                    
            //         // Validasi tanggal
            //         if (!spendingDate) {
            //             e.preventDefault();
            //             alert('Tanggal pengeluaran harus diisi');
            //             spendingDateElement.focus();
            //             return false;
            //         }
                    
            //         console.log('Form validation passed');
            //         return true;
            //     });
            // } else {
            //     console.warn('Element with id "spendingForm" not found');
            // }
        });
    </script>
</x-layout.default>