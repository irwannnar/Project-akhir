<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Printing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Set active tab from request or default to 'purchases'
        $activeTab = $request->get('tab', 'purchases');
        
        // Query untuk purchases (transaksi dengan type 'purchase')
        $purchasesQuery = Transaction::where('type', 'purchase')
            ->with('product')
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->payment_method, function($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->when($request->start_date && $request->end_date, function($query) use ($request) {
                return $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            });
        
        // Query untuk orders (transaksi dengan type 'order')
        $ordersQuery = Transaction::where('type', 'order')
            ->with('printing')
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            });
        
        $purchases = $purchasesQuery->paginate(10, ['*'], 'purchases_page')
            ->appends(['tab' => 'purchases'] + $request->except('purchases_page'));
        
        $orders = $ordersQuery->paginate(10, ['*'], 'orders_page')
            ->appends(['tab' => 'orders'] + $request->except('orders_page'));
        
        return view('transaction.index', compact('purchases', 'orders', 'activeTab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'order');
        
        $products = Product::all();
        $services = Printing::all();
        
        return view('transaction.create', compact('type', 'products', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        $validated = $request->validate([
            'type' => 'required|in:order,purchase',
            'product_id' => 'required_if:type,purchase|exists:products,id',
            'printing_id' => 'required_if:type,order|exists:printings,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'ukuran' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'total_price' => 'required|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        DB::beginTransaction();
        
        try {
            // Hitung profit jika total_cost diisi
            if (isset($validated['total_cost'])) {
                $validated['profit'] = $validated['total_price'] - $validated['total_cost'];
            }
            
            // Handle file upload
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('transaction_files', 'public');
                $validated['file_path'] = $filePath;
            }
            
            // Set paid_at jika status completed dan payment_method bukan cash
            if ($validated['status'] === 'completed' && $validated['payment_method'] !== 'cash') {
                $validated['paid_at'] = now();
            }
            
            $transaction = Transaction::create($validated);
            
            DB::commit();
            
            return redirect()->route('transaction.index', ['tab' => $validated['type'] == 'purchase' ? 'purchases' : 'orders'])
                ->with('success', 'Transaksi berhasil dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['product', 'printing']);
        
        return view('transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $products = Product::all();
        $services = Printing::all();
        
        return view('transaction.edit', compact('transaction', 'products', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|required_if:type,purchase|exists:products,id',
            'printing_id' => 'nullable|required_if:type,order|exists:printings,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'ukuran' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'total_price' => 'required|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        DB::beginTransaction();
        
        try {
            // Hitung profit jika total_cost diisi
            if (isset($validated['total_cost'])) {
                $validated['profit'] = $validated['total_price'] - $validated['total_cost'];
            } else {
                $validated['profit'] = null;
            }
            
            // Handle file upload
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($transaction->file_path) {
                    Storage::disk('public')->delete($transaction->file_path);
                }
                
                $filePath = $request->file('file')->store('transaction_files', 'public');
                $validated['file_path'] = $filePath;
            }
            
            // Set paid_at jika status completed dan payment_method bukan cash
            if ($validated['status'] === 'completed' && $validated['payment_method'] !== 'cash') {
                $validated['paid_at'] = now();
            } elseif ($validated['status'] !== 'completed') {
                $validated['paid_at'] = null;
            }
            
            $transaction->update($validated);
            
            DB::commit();
            
            return redirect()->route('transaction.index', ['tab' => $transaction->type == 'purchase' ? 'purchases' : 'orders'])
                ->with('success', 'Transaksi berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();
        
        try {
            // Hapus file jika ada
            if ($transaction->file_path) {
                Storage::disk('public')->delete($transaction->file_path);
            }
            
            $transaction->delete();
            
            DB::commit();
            
            return redirect()->route('transaction.index', ['tab' => $transaction->type == 'purchase' ? 'purchases' : 'orders'])
                ->with('success', 'Transaksi berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mark transaction as paid.
     */
    public function markAsPaid(Transaction $transaction)
    {
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'Hanya transaksi dengan status completed yang dapat ditandai sebagai dibayar');
        }
        
        $transaction->update([
            'paid_at' => now()
        ]);
        
        return back()->with('success', 'Transaksi berhasil ditandai sebagai dibayar');
    }
    
    /**
     * Calculate profit for a transaction.
     */
    private function calculateProfit($totalPrice, $totalCost)
    {
        if ($totalCost === null) {
            return null;
        }
        
        return $totalPrice - $totalCost;
    }
}