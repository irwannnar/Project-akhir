<?php

namespace App\Http\Controllers;

use App\Models\PrintingService;
use App\Models\Order;
use Illuminate\Http\Request;

class PrintingController extends Controller
{
    // Menampilkan list printing services
    public function index()
    {
        return view('printing.index');
    }

    // Menampilkan form pembuatan pesanan berdasarkan jenis printing
    public function create()
    {
        return view('printing.create');
    }

    // Menyimpan pesanan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_details' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);


        return redirect()->route('orders.index')
            ->with('success', 'order created successfuly');
    }
}