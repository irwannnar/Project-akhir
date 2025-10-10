<x-layout.default>
    <div class="container mx-auto px-4 py-6" x-data="printingData()" x-init="init()">

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 relative"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="absolute top-3 right-3 text-green-700 hover:text-green-900"
                    aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        <div>
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                <h1 class="font-bold text-2xl text-gray-800">jasa Printing</h1>
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

                                            @case('per_cm')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Per
                                                    cm</span>
                                            @break

                                            @case('tetap')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Harga
                                                    Tetap</span>
                                            @break

                                            @case('per_meter')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full">Per
                                                    Meter</span>
                                            @break

                                            @case('pcs')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold bg-orange-100 text-orange-800 rounded-full">pcs
                                                </span>
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
</x-layout.default>
