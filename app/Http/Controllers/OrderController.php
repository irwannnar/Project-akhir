<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $printingTypes = ['digital', 'screen', 'offset', 'sublimation'];
        
        return view('order.index', compact('orders', 'statuses', 'printingTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $printingTypes = ['digital' => 'Digital Printing', 'screen' => 'Screen Printing', 
                         'offset' => 'Offset Printing', 'sublimation' => 'Sublimation Printing'];
        $materials = ['kertas' => 'Kertas', 'plastik' => 'Plastik', 'kain' => 'Kain', 'vinyl' => 'Vinyl'];
        
        return view('order.create', compact('printingTypes', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:15',
            'customer_email' => 'required|email|max:255',
            'customer_address' => 'required|string',
            'printing_type' => 'required|in:digital,screen,offset,sublimation',
            'material' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,eps,cdr,psd|max:10240'
        ]);
        
        // Hitung harga
        $totalPrice = $this->calculatePrice(
            $validated['printing_type'],
            $validated['material'],
            $validated['quantity'],
            $validated['width'],
            $validated['height']
        );
        
        // Simpan file jika ada
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('order-files', 'public');
        }
        
        // Buat order
        $order = Order::create([
            'order_code' => 'PRINT' . now()->format('YmdHis') . Str::random(4),
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'customer_address' => $validated['customer_address'],
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
        
        return redirect()->route('order.show', $order->id)
                         ->with('success', 'Order berhasil dibuat! Kode order: ' . $order->order_code);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $printingTypes = ['digital' => 'Digital Printing', 'screen' => 'Screen Printing', 
                         'offset' => 'Offset Printing', 'sublimation' => 'Sublimation Printing'];
        $materials = ['kertas' => 'Kertas', 'plastik' => 'Plastik', 'kain' => 'Kain', 'vinyl' => 'Vinyl'];
        $statuses = ['pending' => 'Pending', 'processing' => 'Processing', 
                    'completed' => 'Completed', 'cancelled' => 'Cancelled'];
        
        return view('order.edit', compact('order', 'printingTypes', 'materials', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:15',
            'customer_email' => 'required|email|max:255',
            'customer_address' => 'required|string',
            'printing_type' => 'required|in:digital,screen,offset,sublimation',
            'material' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,eps,cdr,psd|max:10240'
        ]);
        
        // Hitung harga
        $totalPrice = $this->calculatePrice(
            $validated['printing_type'],
            $validated['material'],
            $validated['quantity'],
            $validated['width'],
            $validated['height']
        );
        
        // Update file jika ada
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($order->file_path) {
                Storage::disk('public')->delete($order->file_path);
            }
            
            $filePath = $request->file('file')->store('order-files', 'public');
            $validated['file_path'] = $filePath;
        }
        
        $validated['total_price'] = $totalPrice;
        
        $order->update($validated);
        
        return redirect()->route('order.show', $order->id)
                         ->with('success', 'Order berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Hapus file jika ada
        if ($order->file_path) {
            Storage::disk('public')->delete($order->file_path);
        }
        
        $order->delete();
        
        return redirect()->route('order.index')
                         ->with('success', 'Order berhasil dihapus!');
    }

    /**
     * Calculate order price based on various factors
     */
    private function calculatePrice($printingType, $material, $quantity, $width, $height)
    {
        // Harga dasar per cm2 untuk setiap jenis printing
        $basePrices = [
            'digital' => 0.05,
            'screen' => 0.08,
            'offset' => 0.03,
            'sublimation' => 0.12
        ];
        
        // Faktor material
        $materialFactors = [
            'kertas' => 1.0,
            'plastik' => 1.5,
            'kain' => 2.0,
            'vinyl' => 1.8
        ];
        
        // Hitung luas dalam cm2
        $area = $width * $height;
        
        // Hitung harga dasar
        $basePrice = $basePrices[$printingType] * $area;
        
        // Terapkan faktor material
        $materialPrice = $basePrice * $materialFactors[$material];
        
        // Hitung harga total dengan kuantitas
        $total = $materialPrice * $quantity;
        
        // Diskon untuk jumlah besar
        if ($quantity > 100) {
            $total *= 0.9; // Diskon 10%
        } elseif ($quantity > 50) {
            $total *= 0.95; // Diskon 5%
        }
        
        return round($total, 2);
    }

    /**
     * Filter orders by status
     */
    public function filterByStatus($status)
    {
        $orders = Order::status($status)->latest()->paginate(10);
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $printingTypes = ['digital', 'screen', 'offset', 'sublimation'];
        
        return view('order.index', compact('orders', 'statuses', 'printingTypes', 'status'));
    }

    /**
     * Search orders
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $orders = Order::search($search)->latest()->paginate(10);
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $printingTypes = ['digital', 'screen', 'offset', 'sublimation'];
        
        return view('order.index', compact('orders', 'statuses', 'printingTypes', 'search'));
    }
}