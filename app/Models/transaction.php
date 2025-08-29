<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'total_price',
        'total_cost',
        'profit',
        'customer_name',
        'type'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
