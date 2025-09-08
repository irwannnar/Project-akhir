<x-layout.default>
    <div class="containermx-auto px-4 py-6">
        <div>
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                <h1>jasa Printing</h1>
                <a href="{{ route('printing.create') }}"
                    class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-1 py-1 rounded active:scale-95">Tambah
                    Layanan</a>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($printing as $key => $layanan)
                            <tr>
                                <td class="px-6 py-5 text-sm font-medium text-gray-600">{{ $layanan->nama_layanan }}
                                </td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-600">{{ $layanan->biaya }}</td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-600">{{ $layanan->hitungan }}</td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-600">
                                    <div>
                                        <a href="/">Edit</a>
                                        <form action="" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('yakin?')"
                                                class="inline bg-red-600 text-white active:scale-95 rounded hover:bg-red-800 transition duration-200">Hapus</button>
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
