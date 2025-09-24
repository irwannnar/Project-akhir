<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'product_id', 'printing_id', 'customer_name', 'customer_phone', 
        'customer_email', 'customer_address', 'material', 'quantity', 
        'ukuran', 'notes', 'file_path', 'total_price', 'total_cost', 
        'profit', 'payment_method', 'paid_at', 'status', 'type'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function printing()
    {
        return $this->belongsTo(Printing::class);
    }

    // Polymorphic relation ke finance_transactions
    public function financeTransaction()
    {
        return $this->morphOne(FinanceTransaction::class, 'sourceable');
    }

    // Event untuk auto-create finance transaction ketika transaksi completed
    protected static function booted()
    {
        static::updated(function ($transaction) {
            if ($transaction->isDirty('status') && $transaction->status === 'completed') {
                $transaction->createFinanceTransaction();
            }
        });

        static::created(function ($transaction) {
            if ($transaction->status === 'completed') {
                $transaction->createFinanceTransaction();
            }
        });
    }

    public function createFinanceTransaction()
    {
        $finance = Finance::first(); // atau logic berdasarkan kebutuhan
        
        if (!$this->financeTransaction) {
            return $this->financeTransaction()->create([
                'finance_id' => $finance->id,
                'amount' => $this->total_price,
                'type' => 'income',
                'description' => 'Pendapatan dari transaksi: ' . $this->customer_name
            ]);
        }
    }
}