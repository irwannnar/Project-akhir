<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Printing;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nilai tab dari request, default 'purchases'
        $activeTab = $request->get('tab', 'purchases');
        
        // Query untuk purchases (type = purchase)
        $purchasesQuery = Transaction::where('type', 'purchase')
            ->with('product');
            
        // Query untuk orders (type = order)
        $ordersQuery = Transaction::where('type', 'order')
            ->with('printing');
        
        // Filter untuk purchases
        if ($activeTab === 'purchases') {
            if ($request->has('status') && $request->status != '') {
                $purchasesQuery->where('status', $request->status);
            }
            
            if ($request->has('payment_method') && $request->payment_method != '') {
                $purchasesQuery->where('payment_method', $request->payment_method);
            }
            
            if ($request->has('start_date') && $request->start_date != '') {
                $purchasesQuery->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->has('end_date') && $request->end_date != '') {
                $purchasesQuery->whereDate('created_at', '<=', $request->end_date);
            }
        }
        
        // Filter untuk orders (jika diperlukan)
        if ($activeTab === 'orders' && $request->has('status') && $request->status != '') {
            $ordersQuery->where('status', $request->status);
        }
        
        // Pagination
        $purchases = $purchasesQuery->paginate(10, ['*'], 'purchase_page');
        $orders = $ordersQuery->paginate(10, ['*'], 'order_page');
        
        return view('transaction.index', compact('purchases', 'orders', 'activeTab'));
    }

    public function create()
    {
        $products = Product::all();
        $printings = Printing::all();
        return view('transaction.create', compact('products', 'printings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:order,purchase',
            'product_id' => 'required_if:type,purchase|nullable|exists:products,id',
            'printing_id' => 'required_if:type,order|nullable|exists:printings,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'file_path' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'paid_at' => 'nullable|date',
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        // Hitung profit jika total_cost disediakan
        if (isset($validated['total_cost'])) {
            $validated['profit'] = $validated['total_price'] - $validated['total_cost'];
        }

        Transaction::create($validated);

        return redirect()->route('transaction.index')
            ->with('success', 'Transaksi berhasil dibuat');
    }

    public function show(Transaction $transaction)
    {
        return view('transaction.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $products = Product::all();
        $printings = Printing::all();
        return view('transaction.edit', compact('transaction', 'products', 'printings'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'product_id' => 'required_if:type,purchase|nullable|exists:products,id',
            'printing_id' => 'required_if:type,order|nullable|exists:printings,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'file_path' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'paid_at' => 'nullable|date',
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        // Hitung profit jika total_cost diubah
        if (isset($validated['total_cost'])) {
            $validated['profit'] = $validated['total_price'] - $validated['total_cost'];
        } else {
            $validated['profit'] = null;
        }

        $transaction->update($validated);

        return redirect()->route('transaction.index', ['tab' => $transaction->type . 's'])
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy(Transaction $transaction)
    {
        $type = $transaction->type;
        $transaction->delete();

        return redirect()->route('transaction.index', ['tab' => $type . 's'])
            ->with('success', 'Transaksi berhasil dihapus');
    }
}