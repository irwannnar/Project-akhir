<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Purchase;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentYear = date('Y');
        
        // Data untuk grafik profit per bulan dari Purchases
        $monthlyProfit = Purchase::selectRaw('
            MONTH(created_at) as month,
            SUM(total_price) as total_profit
        ')
        ->whereYear('created_at', $currentYear)
        ->where('status', 'completed')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Data untuk grafik profit per bulan dari Orders
        $monthlyOrderProfit = Order::selectRaw('
            MONTH(created_at) as month,
            SUM(total_price) as total_profit
        ')
        ->whereYear('created_at', $currentYear)
        ->where('status', 'completed')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Gabungkan data purchases dan orders
        $combinedMonthlyProfit = [];
        for ($i = 1; $i <= 12; $i++) {
            $purchaseProfit = $monthlyProfit->firstWhere('month', $i);
            $orderProfit = $monthlyOrderProfit->firstWhere('month', $i);
            
            $combinedMonthlyProfit[] = [
                'month' => $i,
                'total_profit' => ($purchaseProfit->total_profit ?? 0) + ($orderProfit->total_profit ?? 0)
            ];
        }

        // Data statistik
        $totalPurchaseProfit = Purchase::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->sum('total_price');
            
        $totalOrderProfit = Order::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->sum('total_price');
            
        $totalProfit = $totalPurchaseProfit + $totalOrderProfit;

        $totalProductsSold = Purchase::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->sum('quantity');

        $totalOrdersCompleted = Order::whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->count();

        // Transaksi terbaru (gabungan purchases dan orders)
        $recentPurchases = Purchase::with('product')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($purchase) {
                return [
                    'type' => 'purchase',
                    'name' => $purchase->product->name ?? 'Produk',
                    'quantity' => $purchase->quantity,
                    'total_price' => $purchase->total_price,
                    'created_at' => $purchase->created_at
                ];
            });

        $recentOrders = Order::with('printing')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'type' => 'order',
                    'name' => $order->printing->nama_layanan ?? 'Layanan',
                    'quantity' => $order->quantity,
                    'total_price' => $order->total_price,
                    'created_at' => $order->created_at
                ];
            });

        // Gabungkan dan urutkan
        $recentTransactions = $recentPurchases->merge($recentOrders)
            ->sortByDesc('created_at')
            ->take(5);

        // Produk terlaris
        $bestSellingProducts = Product::withSum(['purchases as total_sold' => function($query) use ($currentYear) {
            $query->whereYear('created_at', $currentYear)
                  ->where('status', 'completed');
        }], 'quantity')
        ->orderBy('total_sold', 'desc')
        ->take(5)
        ->get();

        return view('dashboard.index', compact(
            'combinedMonthlyProfit',
            'totalProfit',
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
