<x-layout.default>
    <div class="container mx-auto px-4 py-6" x-data="spendingData()" x-init="init()">
        <div>
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengeluaran</h1>
                <a href="{{ route('spending.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg> 
                    Tambah Pengeluaran
                </a>
            </div>
        </div>

        <!-- Statistik Pengeluaran -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-500">
                <div class="text-sm font-medium text-gray-500"></div>
                <div class="text-2xl font-bold text-gray-800"></div>
                <div class="text-xs text-gray-400"></div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-green-500">
                <div class="text-sm font-medium text-gray-500"></div>
                <div class="text-2xl font-bold text-gray-800"></div>
                <div class="text-xs text-gray-400"></div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-purple-500">
                <div class="text-sm font-medium text-gray-500"></div>
                <div class="text-2xl font-bold text-gray-800"></div>
                <div class="text-xs text-gray-400"></div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-red-500">
                <div class="text-sm font-medium text-gray-500"></div>
                <div class="text-2xl font-bold text-gray-800"></div>
                <div class="text-xs text-gray-400"></div>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex flex-wrap gap-2">
                    <button class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Semua</button>
                    <button class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Hari Ini</button>
                    <button class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Minggu Ini</button>
                    <button class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Bulan Ini</button>
                </div>
                <div class="relative w-full md:w-64">
                    <input type="text" placeholder="Cari pengeluaran..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Kuantitas
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Metode Pembayaran
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($spendings as $spending)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $spending->name }}</div>
                                    @if($spending->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($spending->description, 30) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $spending->category == 'Makanan' ? 'bg-red-100 text-red-800' : 
                                           ($spending->category == 'Transportasi' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($spending->category == 'Tagihan' ? 'bg-green-100 text-green-800' : 
                                           'bg-blue-100 text-blue-800')) }}">
                                        {{ $spending->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($spending->amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $spending->quantity }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @switch($spending->payment_method)
                                            @case('cash')
                                                <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Cash</span>
                                                @break
                                            @case('credit_card')
                                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Kartu Kredit</span>
                                                @break
                                            @case('debit_card')
                                                <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Kartu Debit</span>
                                                @break
                                            @case('transfer')
                                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Transfer</span>
                                                @break
                                            @default
                                                <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">{{ $spending->payment_method }}</span>
                                        @endswitch
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($spending->spending_date)->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('spending.show', $spending->id) }}"
                                            class="text-blue-600 hover:text-blue-900 transition duration-200"
                                            title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('spending.edit', $spending->id) }}"
                                            class="text-yellow-600 hover:text-yellow-900 transition duration-200"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('spending.destroy', $spending->id) }}" method="POST"
                                            class="inline" x-ref="deleteForm-{{ $spending->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" @click="confirmDelete({{ $spending->id }})"
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
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($spendings->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 mt-4 rounded-b-lg">
                {{ $spendings->links() }}
            </div>
        @endif
    </div>

    <!-- Modal untuk menampilkan detail pengeluaran -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Pengeluaran</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mt-2">
                    <div id="detailContent" class="space-y-3">
                        <!-- Detail pengeluaran akan diisi oleh JavaScript -->
                    </div>
                </div>

                <div class="mt-4">
                    <button onclick="closeDetailModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Alpine.js untuk data spending
        function spendingData() {
            return {
                init() {
                    // Inisialisasi data atau event listener jika diperlukan
                    console.log('Spending data initialized');
                },
                
                // Konfirmasi penghapusan data
                confirmDelete(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus data pengeluaran ini?')) {
                        document.querySelector(`form[x-ref="deleteForm-${id}"]`).submit();
                    }
                },
                
                // Filter data berdasarkan periode
                filterByPeriod(period) {
                    // Implementasi filter berdasarkan periode
                    console.log('Filter by period:', period);
                    // Di sini akan ada logika untuk memfilter data berdasarkan periode
                }
            }
        }

        // Fungsi untuk menampilkan modal detail
        function showDetail(id, name, description, amount, quantity, category, paymentMethod, spendingDate) {
            const modal = document.getElementById('detailModal');
            const detailContent = document.getElementById('detailContent');
            const modalTitle = document.getElementById('modalTitle');

            if (!modal || !detailContent || !modalTitle) {
                console.error('Element modal tidak ditemukan');
                return;
            }

            modalTitle.textContent = `Detail Pengeluaran - ${name}`;
            
            // Format tanggal
            const date = new Date(spendingDate);
            const formattedDate = date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            // Format jumlah uang
            const formattedAmount = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);

            detailContent.innerHTML = `
                <div class="flex justify-between">
                    <span class="font-medium">Nama:</span>
                    <span>${name}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Kategori:</span>
                    <span>${category}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Jumlah:</span>
                    <span class="font-semibold">${formattedAmount}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Kuantitas:</span>
                    <span>${quantity}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Metode Pembayaran:</span>
                    <span>${paymentMethod}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Tanggal:</span>
                    <span>${formattedDate}</span>
                </div>
                ${description ? `
                <div class="mt-3">
                    <span class="font-medium">Deskripsi:</span>
                    <p class="text-gray-700 mt-1">${description}</p>
                </div>
                ` : ''}
            `;

            modal.classList.remove('hidden');
        }

        // Fungsi untuk menutup modal detail
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Close modal ketika klik di luar area modal
        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target === modal) {
                closeDetailModal();
            }
        }
    </script>
</x-layout.default>