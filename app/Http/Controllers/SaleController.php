<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthlySales = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(quantity) as total_sales'),
            DB::raw('SUM(total_price) as total_revenue'),
            DB::raw('SUM(profit) as total_profit'),
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy('month')
        ->get();

        $formattedSales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlySales->firstWhere('month', $i);
            $formattedSales[] = [
                'month' => $i,
                'total_sales' => $monthData ? $monthData->total_sales : 0,
                'total_revenue' => $monthData ? $monthData->total_revenue : 0,
                'total_profit' => $monthData ? $monthData->total_profit : 0,
            ];
        }

        // Ambil produk terlaris untuk ditampilkan di halaman utama
        $bestSellingProduct = Transaction::selectRaw('product_id, SUM(quantity) as total_sold')
            ->with('product')
            ->whereYear('created_at', date('Y'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->first();

        $totalSales = Transaction::whereYear('created_at', date('Y'))->sum('quantity');
        $totalRevenue = Transaction::whereYear('created_at', date('Y'))->sum('total_price');
        $totalProfit = Transaction::whereYear('created_at', date('Y'))->sum('profit');
        
        return view('sale.index', compact(
            'monthlySales', 
            'formattedSales', 
            'totalSales', 
            'totalRevenue', 
            'totalProfit',
            'bestSellingProduct'
        ));
    }

    /**
     * Halaman statistik produk
     */
    public function product()
    {
        $productSales = Transaction::selectRaw('product_id, SUM(quantity) as total_sold, SUM(total_price) as total_revenue')
            ->with('product')
            ->whereYear('created_at', date('Y'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->get();

        $totalProductsSold = $productSales->sum('total_sold');
        $totalRevenue = $productSales->sum('total_revenue');

        return view('sale.product', compact('productSales', 'totalProductsSold', 'totalRevenue'));
    }

    public function getSalesData(Request $request) {
        $year = $request->get('year', date('Y'));

        $monthlySales = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(quantity) as total_sales'),
        )
        ->whereYear('created_at', $year)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy('month')
        ->get();

        return response()->json($monthlySales);
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
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}