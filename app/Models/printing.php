<?php

namespace App\Models;

use App\Http\Controllers\OrderController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PhpParser\Node\Expr\Cast;

class printing extends Model
{
    protected $fillable =[ 
        'nama_layanan',
        'biaya',
        'ukuran',
        'hitungan'
    ];

    public function order() {
        return $this->HasMany(Order::class);
    }

    public function transaction() {
        return $this->hasMany(Transaction::class);
    }
}
