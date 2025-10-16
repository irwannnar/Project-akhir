<x-layout.default>
    <div class="container mx-auto mt-4 max-w-4xl">
        <div class="mb-6">
            <h1 class="font-bold text-2xl text-gray-800">Tambah Layanan Printing</h1>
            <p class="text-gray-600 mt-1">Tambahkan layanan printing baru dengan ukuran dan harga yang sesuai</p>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('printing.store') }}" method="POST">
                @csrf

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="nama_layanan" class="block font-medium text-gray-700 mb-2">Nama Layanan *</label>
                        <input type="text" name="nama_layanan" id="nama_layanan"
                            placeholder="Contoh: Cetak Foto, Cetak Banner"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            value="{{ old('nama_layanan') }}" required>
                        @error('nama_layanan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hitungan" class="block font-medium text-gray-700 mb-2">Jenis Perhitungan *</label>
                        <input type="text" name="hitungan" id="hitungan" placeholder="Contoh: per_lembar, per_cm2, tetap"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            value="{{ old('hitungan') }}" required>
                        @error('hitungan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Biaya Dasar -->
                <div class="mb-6">
                    <label for="biaya" class="block font-medium text-gray-700 mb-2">Biaya *</label>
                    <input type="number" name="biaya" id="biaya" min="0" step="100" placeholder="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        value="{{ old('biaya') }}" required>
                    @error('biaya')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('printing.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center gap-2 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 flex items-center gap-2 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout.default>