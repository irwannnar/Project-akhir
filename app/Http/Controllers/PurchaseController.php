<?php

namespace App\Http\Controllers;

use App\Models\purchase;
use App\Models\product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Menambahkan filter dan pagination
        $purchases = Purchase::query()
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->payment_method, function ($query) use ($request) {
                return $query->where('payment_method', $request->payment_method);
            })
            ->when($request->start_date, function ($query) use ($request) {
                return $query->whereDate('paid_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                return $query->whereDate('paid_at', '<=', $request->end_date);
            })
            ->paginate(10);  // Pagination dengan 10 data per halaman

        return view('purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = Product::all();
        $purchases = Purchase::all();
        return view('purchase.create', compact('purchases', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string',
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'total_price' => 'required|numeric',
            'payment_method' => 'required|string|max:255',
            'paid_at' => 'required',
            'status' => 'required'
        ]);

        Purchase::create($request->all());
        return redirect()->route('purchase.index')->with('success', 'data pembelian berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::all();
        $purchase = Purchase::findOrFail($id);
        return view('purchase.edit', compact('purchase', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, purchase $purchase)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string',
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'total_price' => 'required|numeric',
            'payment_method' => 'required|string|max:255',
            'paid_at' => 'required',
            'status' => 'required'
        ]);

        $purchase->update($request->all());
        return redirect()->route('purchase.index')->with('success', 'data pembelian berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        // Hapus data pembelian
        $purchase->delete();

        // Redirect kembali ke halaman daftar pembelian dengan pesan sukses
        return redirect()->route('purchase.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
