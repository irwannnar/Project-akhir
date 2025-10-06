<x-layout.default>
    <div class="container mx-auto px-4 py-6" x-data="printingData()" x-init="init()">
        <div>
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                <h1>jasa Printing</h1>
                <a href="{{ route('printing.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg> Tambah Layanan
                </a>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Layanan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Biaya
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Perhitungan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($printings as $layanan)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $layanan->nama_layanan }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">Rp
                                        {{ number_format($layanan->biaya, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @switch($layanan->hitungan)
                                            @case('per_lembar')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Per
                                                    Lembar</span>
                                            @break

                                            @case('per_cm2')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Per
                                                    cm²</span>
                                            @break

                                            @case('tetap')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full">Harga
                                                    Tetap</span>
                                            @break

                                            @default
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">{{ $layanan->hitungan }}</span>
                                        @endswitch
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('printing.edit', $layanan->id) }}"
                                            class="text-blue-600 hover:text-blue-900 transition duration-200"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('printing.destroy', $layanan->id) }}" method="POST"
                                            class="inline" x-ref="deleteForm-{{ $layanan->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" @click="confirmDelete({{ $layanan->id }})"
                                                class="text-red-600 hover:text-red-900 transition duration-200"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                        <button type="button"
                                            onclick='showSizes({{ $layanan->id }}, {!! json_encode($layanan->ukuran) !!})'
                                            class="text-green-600 hover:text-green-900 transition duration-200"
                                            title="Lihat Detail Ukuran">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($printings->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $printings->links() }}
            </div>
        @endif
    </div>

    <!-- Modal untuk menampilkan detail ukuran -->
    <div id="sizesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Ukuran</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mt-2">
                    <div id="sizesList" class="space-y-2 max-h-60 overflow-y-auto">
                        <!-- Daftar ukuran akan diisi oleh JavaScript -->
                    </div>
                </div>

                <div class="mt-4">
                    <button onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan modal detail ukuran
        function showSizes(layananId, sizes) {
            try {
                const modal = document.getElementById('sizesModal');
                const sizesList = document.getElementById('sizesList');
                const modalTitle = document.getElementById('modalTitle');

                if (!modal || !sizesList || !modalTitle) {
                    console.error('Element modal tidak ditemukan');
                    return;
                }

                // Handle both array and JSON string
                let sizesArray = [];
                if (typeof sizes === 'string') {
                    try {
                        sizesArray = JSON.parse(sizes) || [];
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        sizesArray = [];
                    }
                } else if (Array.isArray(sizes)) {
                    sizesArray = sizes;
                }

                modalTitle.textContent = `Detail Ukuran - Layanan #${layananId}`;
                sizesList.innerHTML = '';

                if (sizesArray.length > 0) {
                    sizesArray.forEach((size) => {
                        if (size && size.nama && size.harga) {
                            const sizeItem = document.createElement('div');
                            sizeItem.className = 'flex justify-between items-center p-2 bg-gray-50 rounded';
                            sizeItem.innerHTML = `
                        <span class="font-medium">${size.nama}</span>
                        <span class="text-green-600 font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(size.harga)}</span>
                    `;
                            sizesList.appendChild(sizeItem);
                        }
                    });
                } else {
                    sizesList.innerHTML = '<p class="text-gray-500 text-center">Tidak ada ukuran tersedia</p>';
                }

                modal.classList.remove('hidden');
            } catch (error) {
                console.error('Error dalam showSizes:', error);
            }
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('sizesModal').classList.add('hidden');
        }

        // Fungsi untuk konfirmasi delete (jika menggunakan Alpine.js)
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
                document.querySelector(`form[x-ref="deleteForm-${id}"]`).submit();
            }
        }

        // Close modal ketika klik di luar area modal
        window.onclick = function(event) {
            const modal = document.getElementById('sizesModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</x-layout.default>
