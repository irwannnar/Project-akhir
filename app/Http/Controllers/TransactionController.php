<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Printing;
use App\Models\Spending;
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
        $activeTab = $request->get('tab', 'orders');

        // Query untuk orders (services)
        $orders = Transaction::with(['items.printing'])
            ->where('type', 'order')
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->payment_method, function ($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->when($request->start_date, function ($query, $startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($request->end_date, function ($query, $endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'orders_page')
            ->appends(['tab' => 'orders'] + $request->except('orders_page'));

        // Query untuk purchases (products)
        $purchases = Transaction::with(['items.product'])
            ->where('type', 'purchase')
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->payment_method, function ($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->when($request->start_date, function ($query, $startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($request->end_date, function ($query, $endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'purchases_page')
            ->appends(['tab' => 'purchases'] + $request->except('purchases_page'));

        return view('transaction.index', compact('orders', 'purchases', 'activeTab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'order');

        $products = Product::where('stock', '>', 0)->get();
        $printings = Printing::all();

        return view('transaction.create', compact('type', 'products', 'printings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'status' => 'required|string',
            'cart_items' => 'required|json',
        ]);

        try {
            DB::beginTransaction();

            $cartItems = json_decode($request->cart_items, true);

            if (empty($cartItems)) {
                return back()->with('error', 'Keranjang kosong!');
            }

            // Validasi stok
            foreach ($cartItems as $item) {
                if ($item['type'] === 'product') {
                    $product = Product::find($item['product_id']);
                    if (!$product) {
                        return back()->with('error', 'Produk tidak ditemukan!');
                    }

                    if ($product->stock < $item['quantity']) {
                        return back()->with('error', "Stok {$product->name} tidak mencukupi! Stok tersedia: {$product->stock}");
                    }
                }
            }

            // Hitung total price (ini yang akan disimpan di transactions.total_price)
            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $totalPrice += (float) ($item['total_price'] ?? 0);
            }

            if ($totalPrice <= 0) {
                return back()->with('error', 'Total harga tidak valid!');
            }

            // BUAT TRANSAKSI - SESUAIKAN DENGAN STRUCTURE DATABASE
            $transaction = Transaction::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'payment_method' => $request->payment_method,
                'paid_at' => $request->paid_at ?: null,
                'status' => $request->status,
                'type' => $request->type,
                'subtotal' => $totalPrice, // Sama dengan total_price untuk sementara
                'total_price' => $totalPrice, // INI FIELD YANG DIMINTA
                'notes' => $request->notes ?? null,
                // total_cost dan profit bisa diisi nanti
            ]);

            // Simpan items ke transaction_items
            foreach ($cartItems as $item) {
                $quantity = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? 0);
                $itemTotalPrice = (float) ($item['total_price'] ?? 0);

                if ($item['type'] === 'product') {
                    // Kurangi stok produk
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock', $quantity);

                        // Hitung cost dan profit untuk produk
                        $unitCost = $product->cost_price ?? 0;
                        $totalCost = $unitCost * $quantity;
                        $profit = $itemTotalPrice - $totalCost;
                    }

                    $transaction->items()->create([
                        'product_id' => $item['product_id'],
                        'printing_id' => null,
                        'quantity' => $quantity,
                        'unit_price' => $price,
                        'total_price' => $itemTotalPrice,
                        'unit_cost' => $unitCost ?? 0,
                        'profit' => $profit ?? 0,
                        'notes' => $item['notes'] ?? null,
                    ]);
                } else {
                    $transaction->items()->create([
                        'product_id' => null,
                        'printing_id' => $item['printing_id'],
                        'quantity' => $quantity,
                        'tinggi' => isset($item['tinggi']) ? (int) $item['tinggi'] : null,
                        'lebar' => isset($item['lebar']) ? (int) $item['lebar'] : null,
                        'unit_price' => $price,
                        'total_price' => $itemTotalPrice,
                        'notes' => $item['notes'] ?? null,
                        // Untuk service, cost dan profit bisa dihitung berbeda
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transaction.show', $transaction->id)
                ->with('success', 'Transaksi berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with(['items.product', 'items.printing'])->findOrFail($id);

        return view('transaction.show', compact('transaction'));
    }

    private function calculateTotalAmount($cartItems)
    {
        return array_reduce($cartItems, function ($total, $item) {
            return $total + $item['total_price'];
        }, 0);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaction = Transaction::with(['items'])->findOrFail($id);

        $transactionItem = $transaction->items->first();

        $products = Product::all();
        $services = Printing::all();

        return view('transaction.edit', compact('transaction', 'transactionItem', 'products', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);

            // Validasi: gunakan cart_items (JSON) bukan field item tunggal
            $validated = $request->validate([
                'customer_name'  => 'required|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_email' => 'nullable|email|max:255',
                'customer_address' => 'nullable|string',
                'payment_method' => 'required|string',
                'paid_at' => 'nullable|date',
                'status' => 'required|string',
                'cart_items' => 'required|json',
            ]);

            $cartItems = json_decode($request->cart_items, true);
            if (empty($cartItems) || !is_array($cartItems)) {
                DB::rollBack();
                return back()->with('error', 'Keranjang kosong atau format tidak valid!');
            }

            // Validasi stok untuk produk jika transaksi bertipe purchase
            if ($transaction->type === 'purchase') {
                foreach ($cartItems as $item) {
                    if (($item['type'] ?? '') === 'product') {
                        $product = Product::find($item['product_id']);
                        if (!$product) {
                            DB::rollBack();
                            return back()->with('error', 'Produk tidak ditemukan!');
                        }
                        $qty = (int) ($item['quantity'] ?? 0);
                        if ($qty < 1) {
                            DB::rollBack();
                            return back()->with('error', 'Jumlah produk harus minimal 1!');
                        }
                        // Note: nanti akan sesuaikan stok (restore old, kurangi new)
                    }
                }
            }

            // Hitung total harga dari cart_items
            $totalPrice = array_reduce($cartItems, function ($sum, $it) {
                return $sum + (float)($it['total_price'] ?? 0);
            }, 0);

            if ($totalPrice <= 0) {
                DB::rollBack();
                return back()->with('error', 'Total harga tidak valid!');
            }

            // Restore stok dari item lama (untuk purchase) sebelum mengganti
            if ($transaction->type === 'purchase') {
                foreach ($transaction->items as $oldItem) {
                    if ($oldItem->product_id) {
                        $prod = Product::find($oldItem->product_id);
                        if ($prod) {
                            $prod->increment('stock', $oldItem->quantity);
                        }
                    }
                }
            }

            // Hapus item lama
            $transaction->items()->delete();

            // Update header transaksi
            $transaction->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
                'payment_method' => $validated['payment_method'],
                'paid_at' => $validated['paid_at'] ?? null,
                'status' => $validated['status'],
                'subtotal' => $totalPrice,
                'total_price' => $totalPrice,
                'notes' => $request->notes ?? null,
            ]);

            // Simpan item baru dan sesuaikan stok (jika purchase)
            foreach ($cartItems as $item) {
                $quantity = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? 0);
                $itemTotalPrice = (float) ($item['total_price'] ?? ($price * $quantity));

                if (($item['type'] ?? '') === 'product') {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        // Kurangi stok sesuai item baru
                        $product->decrement('stock', $quantity);

                        $unitCost = $product->cost_price ?? 0;
                        $totalCost = $unitCost * $quantity;
                        $profit = $itemTotalPrice - $totalCost;
                    } else {
                        $unitCost = 0;
                        $profit = 0;
                    }

                    $transaction->items()->create([
                        'product_id' => $item['product_id'],
                        'printing_id' => null,
                        'quantity' => $quantity,
                        'unit_price' => $price,
                        'total_price' => $itemTotalPrice,
                        'unit_cost' => $unitCost ?? 0,
                        'profit' => $profit ?? 0,
                        'notes' => $item['notes'] ?? null,
                    ]);
                } else {
                    $transaction->items()->create([
                        'product_id' => null,
                        'printing_id' => $item['printing_id'] ?? null,
                        'quantity' => $quantity,
                        'tinggi' => isset($item['tinggi']) ? $item['tinggi'] : null,
                        'lebar' => isset($item['lebar']) ? $item['lebar'] : null,
                        'unit_price' => $price,
                        'total_price' => $itemTotalPrice,
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            // Handle file upload for services (update last item file if provided)
            if ($transaction->type === 'order' && $request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('transaction_files', $filename, 'public');
                // set file_path ke item terakhir sebagai contoh
                $lastItem = $transaction->items()->latest()->first();
                if ($lastItem) {
                    $lastItem->update(['file_path' => $path]);
                }
            }

            DB::commit();

            return redirect()
                ->route('transaction.index', ['tab' => $transaction->type === 'purchase' ? 'purchases' : 'orders'])
                ->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transaction update error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::with('transactionItems')->findOrFail($id);

            // Restore stock for product transactions
            if ($transaction->type === 'purchase') {
                foreach ($transaction->transactionItems as $item) {
                    if ($item->product_id) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->decrement('stock', $item->quantity);
                        }
                    }
                }
            }

            $transaction->transactionItems()->delete();
            $transaction->delete();

            DB::commit();

            return redirect()
                ->route('transaction.index', ['tab' => $transaction->type === 'purchase' ? 'purchases' : 'orders'])
                ->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transaction deletion error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mark transaction as completed
     */
    public function markCompleted($id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);

            $transaction->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('transaction.index', ['tab' => $transaction->type === 'purchase' ? 'purchases' : 'orders'])
                ->with('success', 'Transaksi berhasil ditandai sebagai selesai!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Mark completed error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
