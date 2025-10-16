<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\printing;

class PrintingController extends Controller
{
    // Menampilkan halaman utama dengan pilihan printing
    public function index()
    {
        $printings = Printing::orderBy('created_at', 'desc')->paginate(10);
        return view('printing.index', compact('printings'));
    }

    // Menampilkan halaman create order berdasarkan jenis printing
    public function create()
    {
        return view('printing.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'biaya' => 'required|numeric|min:0',
            'hitungan' => 'required|max:50',
        ]);

        Printing::create($request->all());
        return redirect()->route('printing.index')
            ->with('success', 'Layanan printing berhasil ditambahkan');
    }

    public function show(Printing $printing)
    {
        // return view('printing.show', compact('printing'));
    }

    public function edit(Request $request, Printing $printing)
    {
        // dd($request->all(), $printing);
        return view('printing.edit', compact('printing'));
    }

    public function update(Request $request, Printing $printing)
    {
        $request->validate([
            'nama_layanan'=> 'required|string|max:255',
            'biaya'=>'required|numeric|min:0',
            'hitungan'=>'required|string|max:50',
        ]);

        $printing->update($request->all());
        return redirect()->route('printing.index')->with('success', 'data berhasil diubah');
    }

    public function destroy(Printing $printing)
    {
        $printing->delete();
        return redirect()->route('printing.index')->with('success','data berhasil dihapus!');
    }
}
