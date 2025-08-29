<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintingService extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'base_price',
        'price_per_page',
        'min_order',
        'max_order',
        'is_available'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'price_per_page' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}