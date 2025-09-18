<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printing extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_layanan',
        'biaya',
        'hitungan',
        'ukuran'
    ];

    protected $casts = [
        'biaya' => 'decimal:2',
        'ukuran' => 'array'
    ];

    // Accessor untuk mendapatkan daftar ukuran
    public function getUkuranOptionsAttribute()
    {
        return $this->ukuran ?? [];
    }

    public function order() {
        return $this->HasMany(Order::class);
    }

    public function transaction() {
        return $this->hasMany(Transaction::class);
    }
}
