<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PrintingController extends Controller
{
    // Menampilkan halaman utama dengan pilihan printing
    public function index()
    {
        return view('printing.index');
    }

    // Menampilkan halaman create order berdasarkan jenis printing
    public function create(Request $request)
    {
        return view('printing.create', compact('printingType'));
    }

    // Menyimpan order baru
    public function store(Request $request)
    {
        return redirect('printing.index')->with('success', 'layanan berhasil dibuat!');
    }
}