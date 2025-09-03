<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    protected $fillable = [
        'transaction_id'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}
