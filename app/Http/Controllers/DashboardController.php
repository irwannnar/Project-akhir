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
        $estimatedExpenses = Spending::whereYear('created_at', $currentYear)
            ->sum('amount');

        $totalProductsSold = Transaction::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->sum('quantity');

        $totalOrdersCompleted = Transaction::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->count();

        //total saldo yang dipunya saat ini
        $totalBalance = $totalProfit - $estimatedExpenses;

        //total transaksi hari ini 
        $dailyOrderCompleted = Transaction::whereDate('created_at', now()->toDateString())
            ->where('status', 'completed')
            ->count();

        //total transaksi minggu ini
        $weeklyOrderCompleted = Transaction::WhereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])
            ->where('status', 'completed')
            ->count();

        //total transaksi bulan ini
        $monthlyOrderCompleted = Transaction::WhereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])
            ->where('status', 'completed')
            ->count();

        // Transaksi terbaru
        $recentTransactions = Transaction::with(['product', 'printing'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($trx) {
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

        // Data untuk chart pembelian vs pesanan - PERBAIKAN DI SINI
        $monthlyPurchaseData = array_fill(0, 12, 0);
        $monthlyOrderData = array_fill(0, 12, 0);

        // Query untuk mendapatkan data transaksi per bulan
        $transactionStats = Transaction::selectRaw('
            MONTH(created_at) as month,
            type,
            COUNT(*) as count
        ')
            ->whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->groupBy('month', 'type')
            ->get();

        // Isi data ke array
        foreach ($transactionStats as $stat) {
            $monthIndex = $stat->month - 1;
            if ($stat->type === 'purchase') {
                $monthlyPurchaseData[$monthIndex] = $stat->count;
            } else if ($stat->type === 'order') {
                $monthlyOrderData[$monthIndex] = $stat->count;
            }
        }

        // Data distribusi layanan
        $allServiceTransaction = Transaction::with('printing')
            ->where('type', 'order')
            ->whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->get();

        $serviceDistribution = [];
        foreach ($allServiceTransaction as $transaction) {
            if ($transaction->printing) {
                $serviceName = $transaction->printing->nama_layanan;
                if (!isset($serviceDistribution[$serviceName])) {
                    $serviceDistribution[$serviceName] = 0;
                }
                $serviceDistribution[$serviceName]++;
            }
        }

        arsort($serviceDistribution);

        // Produk terlaris
        $bestSellingProducts = Product::withSum(['transactions as total_sold' => function ($query) use ($currentYear) {
            $query->whereYear('created_at', $currentYear)
                ->where('status', 'completed');
        }], 'quantity')
            ->orderBy('total_sold', 'desc')
            ->take(3)
            ->get();

        return view('dashboard.index', compact(
            'combinedMonthlyProfit',
            'combinedMonthlyExpenses',
            'totalProfit',
            'estimatedExpenses',
            'totalProductsSold',
            'totalOrdersCompleted',
            'totalBalance',
            'recentTransactions',
            'bestSellingProducts',
            'dailyOrderCompleted',
            'weeklyOrderCompleted',
            'monthlyOrderCompleted',
            'monthlyPurchaseData',
            'monthlyOrderData',
            'serviceDistribution',
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
