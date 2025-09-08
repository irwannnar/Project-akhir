<x-layout.default>
    <div class="containermx-auto px-4 py-6">
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
        <div>
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class=""></div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Layanan</th>
                            <th class="px-6 py-5 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Biaya</th>
                            <th class="px-6 py-5 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Perhitungan</th>
                            <th class="px-6 py-5 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($printing as $key => $layanan)
                            <tr>
                                <td class="px-6 py-5 text-sm font-medium text-gray-600">{{ $layanan->nama_layanan }}
                                </td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-600">
                                    {{ number_format($layanan->biaya) }}</td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-600">{{ $layanan->hitungan }}</td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-600">
                                    <div>
                                        <a href="{{ route('printing.edit') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('yakin?')"
                                                class="inline active:scale-95 rounded transition duration-200 px-1 py-1">
                                                <svg class="w-5 h-5 text-red-600 hover:text-red-900 " fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
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
    </div>
</x-layout.default>
