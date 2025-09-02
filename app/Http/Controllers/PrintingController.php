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
        $printingType = $request->query('type');
        
        // Validasi jenis printing
        $validTypes = ['digital', 'sablon', 'offset', 'sublimasi'];
        if (!in_array($printingType, $validTypes)) {
            return redirect('/')->with('error', 'Jenis printing tidak valid.');
        }
        
        return view('printing.create', compact('printingType'));
    }

    // Menyimpan order baru
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'printing_type' => 'required|in:digital,sablon,offset,sublimasi',
            'material' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,eps|max:5120'
        ]);
        
        // Hitung harga (contoh sederhana)
        $area = $validated['width'] * $validated['height'] / 10000; // dalam m2
        $basePrice = 0;
        
        switch ($validated['printing_type']) {
            case 'digital':
                $basePrice = 50000;
                break;
            case 'sablon':
                $basePrice = 75000;
                break;
            case 'offset':
                $basePrice = 40000;
                break;
            case 'sublimasi':
                $basePrice = 85000;
                break;
        }
        
        $totalPrice = ($basePrice * $area * $validated['quantity']);
        
        // Simpan file jika ada
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('printing-files');
        }
        
        // Buat order
        $order = Order::create([
            'order_code' => 'PRINT' . now()->format('YmdHis'),
            'customer_name' => $validated['name'],
            'customer_phone' => $validated['phone'],
            'customer_email' => $validated['email'],
            'customer_address' => $validated['address'],
            'printing_type' => $validated['printing_type'],
            'material' => $validated['material'],
            'quantity' => $validated['quantity'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'notes' => $validated['notes'],
            'file_path' => $filePath,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);
        
        return redirect('order.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    // Menampilkan daftar order
    public function orderList()
    {
        $orders = Order::latest()->get();
        return view('order.index', compact('orders'));
    }

    // Menampilkan detail order
    public function orderDetail($id)
    {
        $order = Order::findOrFail($id);
        return view('printing.order-detail', compact('order'));
    }
}