<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'customer_name',
        'customer_phone',
        'customer_email', 
        'customer_address',
        'subtotal',
        'total_price',
        'total_cost',
        'profit',
        'payment_method',
        'paid_at',
        'status',
        'type',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'total_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_number)) {
                $transaction->transaction_number = static::generateTransactionNumber($transaction->type);
            }
        });
    }

    public static function generateTransactionNumber($type)
    {
        $prefix = $type == 'purchase' ? 'PUR' : 'ORD';
        $date = now()->format('Ymd');
        
        do {
            $number = $prefix . '-' . $date . '-' . Str::random(6);
        } while (static::where('transaction_number', $number)->exists());
        
        return $number;
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}