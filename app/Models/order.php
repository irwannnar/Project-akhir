<?php

namespace App\Models;

use App\Http\Controllers\PrintingController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'printing_id',
        'material',
        'quantity',
        'width',
        'height',
        'notes',
        'file_path',
        'total_price',
        'status'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

     public static function getStatuses()
    {
        return [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public function printing() {
        return $this->belongsTo(Printing::class);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by printing type.
     */
    public function scopePrintingType($query, $type)
    {
        return $query->where('printing_type', $type);
    }

    /**
     * Scope a query to search orders.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('order_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
    }
}