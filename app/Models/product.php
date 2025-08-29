<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable = [
        'name',
        'price_per_unit',
        'cost_per_unit',
        'type',
    ];

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
