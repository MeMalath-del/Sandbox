<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'order_item_id',
        'product_id',
        'quantity',
        'refund_amount',
        'condition',
        'notes',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
    ];

    public function return()
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
