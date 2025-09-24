<x-layout.default>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">Detail Pengeluaran</h1>
                        <p class="text-gray-600">Informasi lengkap tentang pengeluaran</p>
                    </div>
                    <a href="{{ route('spending.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Card Detail -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header Card -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold">{{ $spending->name }}</h2>
                            <p class="text-blue-100 mt-1">{{ $spending->category }}</p>
                        </div>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">
                            {{ \Carbon\Carbon::parse($spending->spending_date)->format('d M Y') }}
                        </span>
                    </div>
                </div>

                <!-- Body Card -->
                <div class="p-6">
                    <!-- Informasi Utama -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Jumlah</label>
                                <p class="text-2xl font-bold text-gray-800 mt-1">
                                    Rp {{ number_format($spending->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Kuantitas</label>
                                <p class="text-lg text-gray-800 mt-1">{{ $spending->quantity }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Metode Pembayaran</label>
                                <p class="text-lg text-gray-800 mt-1">
                                    @switch($spending->payment_method)
                                        @case('cash')
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Cash</span>
                                            @break
                                        @case('credit_card')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Kartu Kredit</span>
                                            @break
                                        @case('debit_card')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Kartu Debit</span>
                                            @break
                                        @case('transfer')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Transfer</span>
                                            @break
                                    @endswitch
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Status</label>
                                <p class="text-lg text-gray-800 mt-1">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Tercatat</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    @if($spending->description)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Deskripsi</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $spending->description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Informasi Sistem -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Informasi Sistem</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Dibuat:</span>
                                <span>{{ $spending->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Diupdate:</span>
                                <span>{{ $spending->updated_at->format('d M Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-medium">ID:</span>
                                <span class="font-mono">{{ $spending->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-500">
                            Terakhir diupdate {{ $spending->updated_at->diffForHumans() }}
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('spending.edit', $spending->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('spending.destroy', $spending->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengeluaran ini?')"
                                        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.default>