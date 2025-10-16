<?php

namespace App\Http\Controllers;

use App\Models\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());
        //filter
        $spendings = Spending::query()
            ->when($request->category, function ($query) use ($request) {
                return $query->where('category', $request->category);
            })
            ->when($request->payment_method, function ($query) use ($request) {
                return $query->where('payment_method', $request->payment_method);
            })
            ->when($request->date, function ($query) use ($request) {
                return $query->whereDate('spending_date', $request->date);
            })
            ->orderBy('spending_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('spending.index', compact('spendings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Data untuk dropdown kategori
        $categories = [
            'Bayar gaji' => 'Bayar gaji',
            'Inventory' => 'Inventory',
            'Maintenance' => 'Maintenance',
            'Lainnya' => 'Lainnya'
        ];

        // Data untuk metode pembayaran
        $paymentMethods = [
            'cash' => 'Cash',
            'transfer' => 'Transfer'
        ];

        return view('spending.create', compact('categories', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1',
            'category' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,transfer',
            'spending_date' => 'required|date'
        ]);
            Spending::create($request->all());

            return redirect()->route('spending.index')
                ->with('success', 'Data pengeluaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Spending $spending)
    {
        return view('spending.show', compact('spending'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spending $spending)
    {
        // Data untuk dropdown kategori
        $categories = [
            'Bayar gaji' => 'Bayar gaji',
            'Inventory' => 'Inventory',
            'Maintenance' => 'Maintenance',
            'Lainnya' => 'Lainnya'
        ];

        // Data untuk metode pembayaran
        $paymentMethods = [
            'cash' => 'Cash',
            'transfer' => 'Transfer'
        ];

        return view('spending.edit', compact('spending', 'categories', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spending $spending)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1',
            'category' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,transfer',
            'spending_date' => 'required|date'
        ]);

        try {
            $spending->update($request->all());

            return redirect()->route('spending.index')
                ->with('success', 'Data pengeluaran berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spending $spending)
    {
        try {
            $spending->delete();

            return redirect()->route('spending.index')
                ->with('success', 'Data pengeluaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
