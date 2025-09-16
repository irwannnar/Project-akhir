<x-layout.default>
    <div class="container mx-auto mt-4 max-w-4xl">
        <div class="mb-2">
            <h1 class="font-bold text-2xl">Tambah layanan</h1>
        </div>
        <!-- Form Section -->
        <div class="bg-white rounded shadow p-8 mb-8">

            <form action="{{ route('printing.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                    <div>
                        <label for="nama_layanan" class="font-bold">Nama layanan</label>
                        <input type="text" name="nama_layanan" id="nama_layanan" placeholder="nama layanan"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label for="biaya" class="font-bold">Biaya</label>
                        <input type="text" name="biaya" id="biaya" placeholder="biaya per cm"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                </div>
                <div class="mt-2">
                    <label for="hitungan" class="font-bold">Perhitungan</label>
                    <input type="text" name="hitungan" id="hitungan" placeholder="hitungan"
                    class="shadow w-full border rounded text-gray-700 py-2 px-3 appearance-none leading-tight focus:outline-none focus:shadow-outline"> 
                </div>
                <div class="flex justify-between mt-8">
                    <a href="/printing"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2 active:scale-95">
                        Kembali
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2 active:scale-95">
                        Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout.default>
