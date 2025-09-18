<x-layout.default>
    <div class="container mx-auto mt-4 max-w-4xl">
        <div class="mb-2">
            <h1 class="font-bold text-2xl"></h1>
        </div>
        <div class="bg-white rounded shadow p-8 mb-8">
            <form action="{{ route('printing.update', $printing->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="nama_layanan">Nama layanan</label>
                        <input type="text" name="nama_layanan" id="nama_layanan" placeholder="nama layanan"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            value="{{ $printing->nama_layanan }}"
                            required>
                    </div>
                    <div>
                        <label for="biaya">biaya</label>
                        <input type="number" name="biaya" id="biaya" placeholder="biaya"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            value="{{ $printing->biaya }}"
                            required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                    <div>
                        <label for="biaya" class="font-bold">Ukuran</label>
                        <input type="text" name="ukuran" id="ukuran" placeholder="ukuran"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            value="{{ $printing->ukuran }}"
                            required>
                    </div>
                    <div>
                    <label for="hitungan">Perhitungan</label>
                    <input type="text" name="hitungan" id="hitungan" placeholder="hitungan"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        value="{{ $printing->hitungan }}"
                        required>
                    </div>
                </div>
                <div class="flex justify-between mt-8">
                    <a href="{{ route('printing.index') }}" class="border border-gray-600 bg-gray-600 hover:bg-gray-800 text-white px-2 py-1 rounded active:scale-95">batal</a>
                    <button type="submit" class="border border-blue-600 bg-blue-600 hover:bg-blue-800 text-white px-2 py-1 rounded active:scale-95">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-layout.default>
