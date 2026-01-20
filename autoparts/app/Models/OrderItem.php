<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_image',
        'quantity',
        'unit_price',
        'discount_amount',
        'tax_amount',
        'total',
        'status',
        'returned_quantity',
        'refunded_amount',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function returnItems()
    {
        return $this->hasMany(ReturnItem::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    public function calculateTotal()
    {
        $subtotal = $this->quantity * $this->unit_price;
        $this->total = $subtotal - $this->discount_amount + $this->tax_amount;
        $this->save();
    }
}
