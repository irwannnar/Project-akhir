<?php

namespace App\Http\Controllers;

use App\Models\finance;
use App\Models\Spending;
use App\Models\Transaction;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $finance = Finance::firstOrCreate([], [
            'initial_balance' => 0,
            'balance' => 0,
            'description' => 'Saldo awal sistem percetakan'
        ]);

        // Update balance terlebih dahulu
        $finance->updateBalance();

        // Data untuk chart (7 hari terakhir)
        $weeklyData = $this->getWeeklySummary();

        return view('finance.dashboard', [
            'finance' => $finance,
            'recentTransactions' => $finance->transactions()
                ->with('product')
                ->latest()
                ->limit(10)
                ->get(),
            'recentSpendings' => $finance->spendings()
                ->latest()
                ->limit(10)
                ->get(),
            'weeklyData' => $weeklyData,
            'topProducts' => $this->getTopProducts(),
        ]);
    }

    /**
     * Display finance summary with filters
     */
    public function index(Request $request)
    {
        $finance = Finance::first();
        $finance->updateBalance();

        $query = FinanceTransaction::with(['sourceable'])
            ->where('finance_id', $finance->id);

        // Filter by date
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->paginate(20);

        return view('finance.index', compact('finance', 'transactions'));
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
    public function show(finance $finance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(finance $finance)
    {
        $finance = Finance::first();
        return view('finance.edit', compact('finance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, finance $finance)
    {
        $request->validate([
            'initial_balance' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255'
        ]);

        $finance = Finance::first();

        DB::transaction(function () use ($request, $finance) {
            $finance->update([
                'initial_balance' => $request->initial_balance,
                'description' => $request->description
            ]);

            $finance->updateBalance();
        });

        return redirect()->route('finance.dashboard')
            ->with('success', 'Saldo awal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(finance $finance)
    {
        //
    }

    public function syncBalance()
    {
        $finance = Finance::first();
        $oldBalance = $finance->balance;

        $finance->updateBalance();

        return redirect()->back()
            ->with(
                'success',
                "Balance berhasil disinkronisasi. " .
                    "Sebelum: Rp " . number_format($oldBalance, 0, ',', '.') .
                    ", Sesudah: Rp " . number_format($finance->balance, 0, ',', '.')
            );
    }

    /**
     * Get financial report
     */
    public function report(Request $request)
    {
        $finance = Finance::first();

        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $income = Transaction::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('total_price');

        $expense = Spending::whereBetween('spending_date', [$startDate, $endDate])
            ->sum('amount');

        $transactions = Transaction::with('product')
            ->where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $spendings = Spending::whereBetween('spending_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $profit = $income - $expense;

        return view('finance.report', compact(
            'finance',
            'income',
            'expense',
            'profit',
            'transactions',
            'spendings',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export report to PDF
     */
    public function exportPdf(Request $request)
    {
        // Implement PDF export logic here
        // You can use DomPDF or Laravel Excel
        return response()->json(['message' => 'PDF export feature will be implemented']);
    }

    /**
     * Get weekly summary for charts
     */
    private function getWeeklySummary()
    {
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // Data income dari transactions completed
        $incomeData = Transaction::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('DATE(paid_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // Data expense dari spending
        $expenseData = Spending::whereBetween('spending_date', [$startDate, $endDate])
            ->selectRaw('DATE(spending_date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing dates
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = [
                'date' => $date,
                'income' => $incomeData[$date]->total ?? 0,
                'expense' => $expenseData[$date]->total ?? 0,
            ];
        }

        return $dates;
    }

    /**
     * Get top products by revenue
     */
    private function getTopProducts()
    {
        return Transaction::where('status', 'completed')
            ->where('paid_at', '>=', now()->subDays(30))
            ->with('product')
            ->selectRaw('product_id, SUM(total_price) as revenue, COUNT(*) as transaction_count')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->product->name ?? 'Product Deleted',
                    'revenue' => $item->revenue,
                    'transaction_count' => $item->transaction_count
                ];
            });
    }

    /**
     * API endpoint for financial data (untuk AJAX charts)
     */
    public function apiSummary()
    {
        $finance = Finance::first();
        $finance->updateBalance();

        return response()->json([
            'balance' => $finance->balance,
            'total_income' => $finance->total_income,
            'total_expense' => $finance->total_expense,
            'weekly_data' => $this->getWeeklySummary(),
        ]);
    }
}
