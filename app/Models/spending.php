<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    protected $fillable = [
        'name', 'description', 'quantity', 'amount', 
        'category', 'payment_method', 'spending_date'
    ];
}