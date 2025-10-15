<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'printing_id',
        'quantity',
        'unit_price',
        'total_price',
        'tinggi',
        'lebar',
        'notes',
        'file_path',
        'unit_cost',
        'profit',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    // Relasi dengan product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi dengan printing service
    public function printing()
    {
        return $this->belongsTo(Printing::class);
    }

    // Relasi dengan transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}