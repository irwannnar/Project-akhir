<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\transaction;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Data untuk grafik profit per bulan
        $monthlyProfit = Transaction::selectRaw('
            MONTH(created_at) as month,
            SUM(profit) as total_profit
        ')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Data statistik
        $totalProfit = Transaction::whereYear('created_at', date('Y'))->sum('profit');
        $totalProductsSold = Transaction::whereYear('created_at', date('Y'))->sum('quantity');
        $totalExpenses = Transaction::whereYear('created_at', date('Y'))->sum('total_cost');

        // Transaksi terakhir
        $recentTransactions = Transaction::with('product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Produk terlaris
        $bestSellingProducts = Product::withSum(['transactions as total_sold' => function($query) {
            $query->whereYear('created_at', date('Y'));
        }], 'quantity')
        ->orderBy('total_sold', 'desc')
        ->take(5)
        ->get();

        return view('dashboard.index', compact(
            'monthlyProfit',
            'totalProfit',
            'totalProductsSold',
            'totalExpenses',
            'recentTransactions',
            'bestSellingProducts'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(dashboard $dashboard)
    {
        //
    }
}
