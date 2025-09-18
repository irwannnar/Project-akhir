<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'material',
        'quantity',
        'ukuran',
        'notes',
        'file_path',
        'total_price',
        'total_cost',
        'profit',
        'payment_method',
        'paid_at',
        'status',
        'type'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'total_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'profit' => 'decimal:2'
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function printing()
    {
        return $this->belongsTo(Printing::class);
    }
}