<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    protected $fillable = [
        'name', 'description', 'quantity', 'amount', 
        'category', 'payment_method', 'spending_date'
    ];

    // Polymorphic relation ke finance_transactions
    public function financeTransaction()
    {
        return $this->morphOne(FinanceTransaction::class, 'sourceable');
    }

    // Event untuk auto-create finance transaction
    protected static function booted()
    {
        static::created(function ($spending) {
            $spending->createFinanceTransaction();
        });

        static::deleted(function ($spending) {
            if ($spending->financeTransaction) {
                $spending->financeTransaction->delete();
            }
        });
    }

    public function createFinanceTransaction()
    {
        $finance = Finance::first(); // atau logic berdasarkan kebutuhan
        
        return $this->financeTransaction()->create([
            'finance_id' => $finance->id,
            'amount' => $this->amount,
            'type' => 'expense',
            'description' => 'Pengeluaran: ' . $this->name
        ]);
    }
}