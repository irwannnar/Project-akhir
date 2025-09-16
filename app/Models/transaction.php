<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    protected $fillable = [
        'product_id',
        'printing_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'material',
        'quantity',
        'width',
        'height',
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

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function Printing() {
        return $this->belongsTo(Printing::class);
    }

    public function sale() {
        return $this->hasOne(Sale::class);
    }
}
