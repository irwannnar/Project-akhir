<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Spending;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentYear = date('Y');

        // Data untuk grafik profit per bulan (semua transaksi)
        $monthlyProfit = Transaction::selectRaw('
                MONTH(created_at) as month,
                SUM(total_price) as total_profit
            ')
            ->whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyExpenses = Spending::selectRaw('
                MONTH(created_at) as month,
                SUM(amount) as total_expense
            ')
            ->whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Susun array 12 bulan untuk profit
        $combinedMonthlyProfit = [];
        for ($i = 1; $i <= 12; $i++) {
            $profit = $monthlyProfit->firstWhere('month', $i);
            $combinedMonthlyProfit[] = [
                'month' => $i,
                'total_profit' => $profit->total_profit ?? 0
            ];
        }

        // Susun array 12 bulan untuk pengeluaran
        $combinedMonthlyExpenses = [];
        for ($i = 1; $i <= 12; $i++) {
            $expense = $monthlyExpenses->firstWhere('month', $i);
            $combinedMonthlyExpenses[] = [
                'month' => $i,
                'total_expense' => $expense->total_expense ?? 0
            ];
        }

        // Data statistik
        $totalProfit = Transaction::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->sum('total_price');

        // Data pengeluaran dari Spending
        $estimatedExpenses = $spendings = Spending::whereYear('created_at', $currentYear)
        ->sum('amount');

        $totalProductsSold = Transaction::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->sum('quantity');

        $totalOrdersCompleted = Transaction::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->count();

        // Transaksi terbaru
        $recentTransactions = Transaction::with(['product', 'printing'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($trx) {
                return [
                    'type' => $trx->type,
                    'name' => $trx->product->name 
                                ?? $trx->printing->nama_layanan 
                                ?? 'Item',
                    'quantity' => $trx->quantity,
                    'total_price' => $trx->total_price,
                    'created_at' => $trx->created_at
                ];
            });

        // Produk terlaris
        $bestSellingProducts = Product::withSum(['transactions as total_sold' => function($query) use ($currentYear) {
                $query->whereYear('created_at', $currentYear)
                      ->where('status', 'completed');
            }], 'quantity')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'combinedMonthlyProfit',
            'combinedMonthlyExpenses',
            'totalProfit',
            'estimatedExpenses',
            'totalProductsSold',
            'totalOrdersCompleted',
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
