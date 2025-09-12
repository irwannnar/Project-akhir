<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'product_id',
        'quantity',
        'total_price',
        'payment_method',
        'paid_at',
        'status',
    ];

    public function product() {
        return $this->belongsTo(product::class);
    }
}
