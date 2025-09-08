<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast;

class printing extends Model
{
    protected $fillable =[ 
        'nama_layanan',
        'biaya',
        'hitungan'
    ];
}
