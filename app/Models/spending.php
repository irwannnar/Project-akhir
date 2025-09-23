<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class spending extends Model
{
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'amount',
        'category',
        'payment_method',
        'spending_date'
    ];
}
