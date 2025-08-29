<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PrintingService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Menampilkan semua pesanan
    public function index()
    {
        $orders = Order::with('service')->latest()->get();
        
        return view('orders.index', compact('orders')); // Perbaiki: 'order' -> 'orders'
    }

    // Menampilkan form pembuatan pesanan
    public function create()
    {
        $services = PrintingService::where('is_available', true)->get();
        
        return view('orders.create', compact('services'));
    }

    // Menyimpan pesanan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'service_id' => 'required|exists:printing_services,id',
            'order_details' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $service = PrintingService::findOrFail($validated['service_id']);
        
        // Validasi quantity
        if ($validated['quantity'] < $service->min_order) {
            return back()->withErrors([
                'quantity' => 'Jumlah pesanan minimal ' . $service->min_order
            ])->withInput();
        }
        
        if ($validated['quantity'] > $service->max_order) {
            return back()->withErrors([
                'quantity' => 'Jumlah pesanan maksimal ' . $service->max_order
            ])->withInput();
        }

        $totalPrice = $service->base_price + ($service->price_per_page * $validated['quantity']);
        
        Order::create([
            'customer_name' => $validated['customer_name'],
            'service_id' => $validated['service_id'],
            'order_details' => $validated['order_details'],
            'quantity' => $validated['quantity'],
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
            'status' => Order::STATUS_PENDING
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    // Menampilkan detail pesanan
    public function show(Order $order)
    {
        $order->load('service'); // Eager load relationship
        return view('orders.show', compact('order'));
    }

    // Menampilkan form edit pesanan
    public function edit(Order $order)
    {
        $services = PrintingService::where('is_available', true)->get();
        $statuses = Order::getStatuses();
        
        return view('orders.edit', compact('order', 'services', 'statuses'));
    }

    // Update pesanan
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'service_id' => 'required|exists:printing_services,id',
            'order_details' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $service = PrintingService::findOrFail($validated['service_id']);
        
        // Validasi quantity
        if ($validated['quantity'] < $service->min_order) {
            return back()->withErrors([
                'quantity' => 'Jumlah pesanan minimal ' . $service->min_order
            ])->withInput();
        }
        
        if ($validated['quantity'] > $service->max_order) {
            return back()->withErrors([
                'quantity' => 'Jumlah pesanan maksimal ' . $service->max_order
            ])->withInput();
        }

        // Hitung ulang harga jika service atau quantity berubah
        if ($order->service_id != $validated['service_id'] || $order->quantity != $validated['quantity']) {
            $validated['total_price'] = $service->base_price + ($service->price_per_page * $validated['quantity']);
        }

        $order->update($validated);

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil diperbarui!');
    }

    // Hapus pesanan
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index') // Perbaiki: 'order.index' -> 'orders.index'
            ->with('success', 'Pesanan berhasil dihapus!');
    }
}