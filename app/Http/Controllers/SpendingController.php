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

        // Hitung statistik
        $statistics = $this->getSpendingStatistics();

        return view('spending.index', compact('spendings', 'statistics'));
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
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
            'transfer' => 'Transfer'
        ];

        return view('spending.create', compact('categories', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1',
            'category' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,credit_card,debit_card,transfer',
            'spending_date' => 'required|date'
        ]);

        try {
            Spending::create($validated);

            return redirect()->route('spending.index')
                ->with('success', 'Data pengeluaran berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
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
            'payment_method' => 'required|in:cash,credit_card,debit_card,transfer',
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

    /**
     * Get spending statistics for the current month
     */
    private function getSpendingStatistics()
    {
        $currentMonth = now()->format('Y-m');

        // Total pengeluaran bulan ini
        $totalSpending = Spending::where('spending_date', 'like', $currentMonth . '%')
            ->sum('amount');

        // Rata-rata per hari
        $daysInMonth = now()->daysInMonth;
        $averagePerDay = $daysInMonth > 0 ? $totalSpending / $daysInMonth : 0;

        // Kategori dengan pengeluaran terbanyak
        $topCategory = Spending::where('spending_date', 'like', $currentMonth . '%')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->first();

        // Metode pembayaran paling sering digunakan
        $topPaymentMethod = Spending::where('spending_date', 'like', $currentMonth . '%')
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->orderBy('count', 'desc')
            ->first();

        return [
            'total_spending' => $totalSpending,
            'average_per_day' => $averagePerDay,
            'top_category' => $topCategory ? $topCategory->category : 'Tidak ada data',
            'top_payment_method' => $topPaymentMethod ? $topPaymentMethod->payment_method : 'Tidak ada data'
        ];
    }

    /**
     * API endpoint untuk mendapatkan data spending (optional)
     */
    public function apiIndex()
    {
        $spendings = Spending::orderBy('spending_date', 'desc')
            ->get()
            ->map(function ($spending) {
                return [
                    'id' => $spending->id,
                    'name' => $spending->name,
                    'description' => $spending->description,
                    'amount' => $spending->amount,
                    'quantity' => $spending->quantity,
                    'category' => $spending->category,
                    'payment_method' => $spending->payment_method,
                    'spending_date' => $spending->spending_date,
                    'formatted_date' => $spending->spending_date->format('d M Y'),
                    'formatted_amount' => 'Rp ' . number_format($spending->amount, 0, ',', '.')
                ];
            });

        return response()->json($spendings);
    }

    /**
     * Filter spending by date range
     */
    public function filter(Request $request)
    {
        $query = Spending::query();

        if ($request->has('start_date') && $request->start_date) {
            $query->where('spending_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('spending_date', '<=', $request->end_date);
        }

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $spendings = $query->orderBy('spending_date', 'desc')
            ->paginate(10);

        return view('spending.index', compact('spendings'));
    }

    /**
     * Export data to CSV
     */
    public function exportCsv()
    {
        $spendings = Spending::orderBy('spending_date', 'desc')->get();

        $fileName = 'spending_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function () use ($spendings) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Nama',
                'Deskripsi',
                'Kategori',
                'Jumlah',
                'Kuantitas',
                'Metode Pembayaran',
                'Tanggal Pengeluaran'
            ]);

            // Data
            foreach ($spendings as $spending) {
                fputcsv($file, [
                    $spending->name,
                    $spending->description,
                    $spending->category,
                    $spending->amount,
                    $spending->quantity,
                    $spending->payment_method,
                    $spending->spending_date
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
