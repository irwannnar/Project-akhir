<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Printing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nilai tab dari request, default 'purchases'
        $activeTab = $request->get('tab', 'purchases');
        
        // Query untuk purchases (type = purchase)
        $purchasesQuery = Transaction::where('type', 'purchase')
            ->with(['items.product']);
            
        // Query untuk orders (type = order)
        $ordersQuery = Transaction::where('type', 'order')
            ->with(['items.printing']);
        
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
        // Validasi dasar
        $validated = $request->validate([
            'type' => 'required|in:order,purchase',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'product_items' => 'required_if:type,purchase|array|min:1',
            'product_items.*.product_id' => 'required_if:type,purchase|exists:products,id',
            'product_items.*.quantity' => 'required_if:type,purchase|integer|min:1',
            'printing_items' => 'required_if:type,order|array|min:1',
            'printing_items.*.printing_id' => 'required_if:type,order|exists:printings,id',
            'printing_items.*.quantity' => 'required_if:type,order|integer|min:1',
            'printing_items.*.ukuran' => 'required_if:type,order|string|max:100',
            'printing_items.*.material' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Buat transaksi utama
            $transaction = Transaction::create([
                'type' => $validated['type'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'total_price' => $validated['total_price'],
                'payment_method' => $validated['payment_method'],
                'status' => $validated['status'],
            ]);

            // Simpan items berdasarkan type
            if ($validated['type'] === 'purchase' && !empty($validated['product_items'])) {
                foreach ($validated['product_items'] as $item) {
                    $product = Product::find($item['product_id']);
                    
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price_per_unit,
                        'subtotal' => $product->price_per_unit * $item['quantity'],
                    ]);
                }
            } elseif ($validated['type'] === 'order' && !empty($validated['printing_items'])) {
                foreach ($validated['printing_items'] as $item) {
                    $printing = Printing::find($item['printing_id']);
                    
                    // Hitung harga berdasarkan ukuran yang dipilih
                    $sizePrice = $this->getPrintingSizePrice($printing, $item['ukuran']);
                    
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'printing_id' => $item['printing_id'],
                        'quantity' => $item['quantity'],
                        'ukuran' => $item['ukuran'],
                        'material' => $item['material'] ?? null,
                        'unit_price' => $sizePrice,
                        'subtotal' => $sizePrice * $item['quantity'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transaction.index')
                ->with('success', 'Transaksi berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'items.printing']);
        return view('transaction.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $transaction->load(['items.product', 'items.printing']);
        $products = Product::all();
        $printings = Printing::all();
        
        return view('transaction.edit', compact('transaction', 'products', 'printings'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Validasi untuk update
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'product_items' => 'required_if:type,purchase|array|min:1',
            'product_items.*.product_id' => 'required_if:type,purchase|exists:products,id',
            'product_items.*.quantity' => 'required_if:type,purchase|integer|min:1',
            'printing_items' => 'required_if:type,order|array|min:1',
            'printing_items.*.printing_id' => 'required_if:type,order|exists:printings,id',
            'printing_items.*.quantity' => 'required_if:type,order|integer|min:1',
            'printing_items.*.ukuran' => 'required_if:type,order|string|max:100',
            'printing_items.*.material' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Update transaksi utama
            $transaction->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'total_price' => $validated['total_price'],
                'payment_method' => $validated['payment_method'],
                'status' => $validated['status'],
            ]);

            // Hapus items lama
            $transaction->items()->delete();

            // Tambahkan items baru
            if ($transaction->type === 'purchase' && !empty($validated['product_items'])) {
                foreach ($validated['product_items'] as $item) {
                    $product = Product::find($item['product_id']);
                    
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price_per_unit,
                        'subtotal' => $product->price_per_unit * $item['quantity'],
                    ]);
                }
            } elseif ($transaction->type === 'order' && !empty($validated['printing_items'])) {
                foreach ($validated['printing_items'] as $item) {
                    $printing = Printing::find($item['printing_id']);
                    
                    // Hitung harga berdasarkan ukuran yang dipilih
                    $sizePrice = $this->getPrintingSizePrice($printing, $item['size']);
                    
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'printing_id' => $item['printing_id'],
                        'quantity' => $item['quantity'],
                        'ukuran' => $item['ukuran'],
                        'material' => $item['material'] ?? null,
                        'unit_price' => $sizePrice,
                        'subtotal' => $sizePrice * $item['quantity'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transaction.index', ['tab' => $transaction->type . 's'])
                ->with('success', 'Transaksi berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();

        try {
            // Hapus items terlebih dahulu
            $transaction->items()->delete();
            
            // Hapus transaksi
            $type = $transaction->type;
            $transaction->delete();

            DB::commit();

            return redirect()->route('transaction.index', ['tab' => $type . 's'])
                ->with('success', 'Transaksi berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper function untuk mendapatkan harga berdasarkan ukuran printing
     */
    private function getPrintingSizePrice($printing, $selectedSize)
    {
        if (!$printing->ukuran) {
            return $printing->biaya;
        }

        $sizes = json_decode($printing->ukuran, true);
        
        foreach ($sizes as $size) {
            if ($size['nama'] === $selectedSize) {
                return $size['harga'];
            }
        }

        return $printing->biaya; // Fallback ke harga dasar
    }
}