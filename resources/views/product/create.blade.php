<x-layout.default>
    <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Tambah Produk Baru</h1>
        </div>

        <form action="{{ route('product.store') }}" method="POST">
            @csrf
            @include('product.partials.form')
            
            <div class="flex items-center justify-between">
                <a href="{{ route('product.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Batal
                </a>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
</x-layout.default>