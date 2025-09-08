<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\printing;

class PrintingController extends Controller
{
    // Menampilkan halaman utama dengan pilihan printing
    public function index()
    {
        $printing = Printing::paginate(10);
        return view('printing.index', compact('printing'));
    }

    // Menampilkan halaman create order berdasarkan jenis printing
    public function create(Request $request)
    {
        return view('printing.create');
    }

    // Menyimpan order baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan',
            'biaya',
            'hitungan',
        ]);

        Printing::create($request->all());
        return redirect()->route('printing.index')->with('success', 'layanan berhasil ditambah!');
    }

    public function edit(Printing $printing)
    {
        return view('printing.edit');
    }

    public function update(Request $request, Printing $printing)
    {
        $request->validate([
            'nama_layanan',
            'biaya',
            'hitungan',
        ]);

        $printing->update($request->all());
    }

    public function destroy(Printing $printing) {
        $printing->delete();
        return redirect()->route('printing.index')->with('layanan berhasil dihapus!');
    }
}
