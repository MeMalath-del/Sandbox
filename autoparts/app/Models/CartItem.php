<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'store_id',
        'quantity',
        'unit_price',
        'total_price',
        'notes',
        'saved_for_later',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'saved_for_later' => 'boolean',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function moveToCart()
    {
        $this->update(['saved_for_later' => false]);
    }

    public function saveForLater()
    {
        $this->update(['saved_for_later' => true]);
    }
}
