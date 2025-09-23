<?php

namespace App\Http\Controllers;

use App\Models\spending;
use Illuminate\Http\Request;

class SpendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('spending.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('spending.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string|max:255',
            'description'=>'nullable|string|max:255',
            'quantity'=>'required|numeric',
            'amount'=>'required|numeric',
            'category'=>'required|string|max:255',
            'payment_method'=>'required|in:cash, credit_card, debit_card, transfer',
            'spending_date'=>'required|date'
        ]);

        Spending::create($request->all());
        return redirect()->route('spending.index')->with('success', 'data pengeluaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(spending $spending)
    {
        return view('spending.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(spending $spending)
    {
        return view('spending.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, spending $spending)
    {
         $request->validate([
            'name'=> 'required|string|max:255',
            'description'=>'nullable|string|max:255',
            'quantity'=>'required|numeric',
            'amount'=>'required|numeric',
            'category'=>'required|string|max:255',
            'payment_method'=>'required|in:cash, credit_card, debit_card, transfer',
            'spending_date'=>'required|date'
        ]);

        $spending->update($request->all());
        return redirect()->route('spending.index')->with('success', 'data pengeluaran berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(spending $spending)
    {
        $spending->delete();
       return redirect()->route('spending.index')->with('success', 'data pengeluaran berhasil dihapus');
    }
}
