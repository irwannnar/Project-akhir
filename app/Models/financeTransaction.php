<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    protected $fillable = [
        'finance_id', 'sourceable_id', 'sourceable_type', 
        'amount', 'type', 'description'
    ];

    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }

    public function sourceable()
    {
        return $this->morphTo();
    }
}