<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Printing;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = Order::with('printing')->get();
        return view('order.index', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $printing = Printing::all();
        return view('order.create', compact('printing'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi data
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:15',
        'customer_email' => 'nullable|email',
        'customer_address' => 'required|string',
        'printing_id' => 'required|exists:printings,id',
        'material' => 'required|string|max:255',
        'quantity' => 'required|integer|min:1',
        'width' => 'required|numeric|min:1',
        'height' => 'required|numeric|min:1',
        'file_path' => 'nullable|file|mimes:stl,obj,3mf,jpg,jpeg,png,pdf',
        'notes' => 'nullable|string',
        'status' => 'required|in:pending,processing,completed,cancelled',
        'total_price' => 'required|numeric',
    ]);

    $validated['order_code'] = 'ORD' . time();

    // Jika validasi berhasil, simpan data pesanan
    $order = new Order($validated);
    
    // Jika ada file yang di-upload
    if ($request->hasFile('file_path')) {
        $path = $request->file('file_path')->store('orders');
        $order->file_path = $path;
    }
    
    // Simpan pesanan
    $order->save();

    return redirect()->route('order.index')->with('success', 'Pesanan berhasil disimpan');
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
        $printing = Printing::all();
        return view('order.edit', compact('order', 'printing'));
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
            'material' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,eps,cdr,psd|max:10240'
        ]);
        
        $order->update($request->all());
        return redirect()->route('order.index')
                         ->with('success', 'Order berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('order.index')
                         ->with('success', 'Order berhasil dihapus!');
    }
}