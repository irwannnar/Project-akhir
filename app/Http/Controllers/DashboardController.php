<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
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
        $estimatedExpenses = Spending::whereYear('created_at', $currentYear)
            ->sum('amount');

        // Total produk terjual dari TransactionItem
        $totalProductsSold = TransactionItem::whereHas('transaction', function($query) use ($currentYear) {
                $query->whereYear('created_at', $currentYear)
                      ->where('status', 'completed');
            })
            ->sum('quantity');

        // Total transaksi completed
        $totalOrdersCompleted = Transaction::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->count();

        // Total saldo (profit - expenses)
        $totalBalance = $totalProfit - $estimatedExpenses;

        // Transaksi hari ini
        $dailyOrderCompleted = Transaction::whereDate('created_at', now()->toDateString())
            ->where('status', 'completed')
            ->count();

        // Transaksi minggu ini
        $weeklyOrderCompleted = Transaction::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->where('status', 'completed')
            ->count();

        // Transaksi bulan ini
        $monthlyOrderCompleted = Transaction::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])
            ->where('status', 'completed')
            ->count();

        // Data untuk distribusi layanan dari TransactionItem - PERBAIKAN
        $serviceDistribution = TransactionItem::selectRaw('
                printings.nama_layanan as service_name,
                COUNT(*) as total_orders
            ')
            ->join('printings', 'transaction_items.printing_id', '=', 'printings.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereYear('transactions.created_at', $currentYear)
            ->where('transactions.status', 'completed')
            ->whereNotNull('transaction_items.printing_id')
            ->groupBy('printings.nama_layanan')
            ->orderBy('total_orders', 'desc')
            ->get()
            ->pluck('total_orders', 'service_name')
            ->toArray();

        // Produk terlaris - QUERY YANG DIPERBAIKI
        $bestSellingProducts = Product::selectRaw('
                products.id,
                products.name,
                COALESCE(SUM(transaction_items.quantity), 0) as total_sold
            ')
            ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->leftJoin('transactions', function($join) use ($currentYear) {
                $join->on('transaction_items.transaction_id', '=', 'transactions.id')
                     ->whereYear('transactions.created_at', $currentYear)
                     ->where('transactions.status', 'completed');
            })
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(3)
            ->get();

        // Alternatif 2: Jika cara di atas masih error, gunakan cara ini
        // $bestSellingProducts = Product::withSum(['transactionItems as total_sold' => function($query) use ($currentYear) {
        //     $query->whereHas('transaction', function($q) use ($currentYear) {
        //         $q->whereYear('created_at', $currentYear)
        //           ->where('status', 'completed');
        //     });
        // }], 'quantity')
        // ->orderBy('total_sold', 'desc')
        // ->take(3)
        // ->get();

        // Data untuk chart pembelian vs pesanan per bulan
        $monthlyTransactionStats = Transaction::selectRaw('
                MONTH(created_at) as month,
                type,
                COUNT(*) as count
            ')
            ->whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->groupBy('month', 'type')
            ->get();

        $monthlyPurchaseData = array_fill(0, 12, 0);
        $monthlyOrderData = array_fill(0, 12, 0);

        foreach ($monthlyTransactionStats as $stat) {
            $monthIndex = $stat->month - 1;
            if ($stat->type === 'purchase') {
                $monthlyPurchaseData[$monthIndex] = $stat->count;
            } else if ($stat->type === 'order') {
                $monthlyOrderData[$monthIndex] = $stat->count;
            }
        }

        return view('dashboard.index', compact(
            'combinedMonthlyProfit',
            'combinedMonthlyExpenses',
            'totalProfit',
            'estimatedExpenses',
            'totalProductsSold',
            'totalOrdersCompleted',
            'totalBalance',
            'bestSellingProducts',
            'dailyOrderCompleted',
            'weeklyOrderCompleted',
            'monthlyOrderCompleted',
            'monthlyPurchaseData',
            'monthlyOrderData',
            'serviceDistribution'
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}