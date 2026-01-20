<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_code',
        'discount_amount',
        'notes',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class)->where('saved_for_later', false);
    }

    public function savedItems()
    {
        return $this->hasMany(CartItem::class)->where('saved_for_later', true);
    }

    public function allItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum('total_price');
    }

    public function getItemsCountAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getTotalAttribute()
    {
        return $this->subtotal - $this->discount_amount;
    }

    public function addItem(Product $product, $quantity = 1)
    {
        $item = $this->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->total_price = $item->quantity * $item->unit_price;
            $item->save();
        } else {
            $item = $this->items()->create([
                'product_id' => $product->id,
                'store_id' => $product->store_id,
                'quantity' => $quantity,
                'unit_price' => $product->current_price,
                'total_price' => $quantity * $product->current_price,
            ]);
        }

        return $item;
    }

    public function updateItemQuantity(CartItem $item, $quantity)
    {
        if ($quantity <= 0) {
            $item->delete();
            return null;
        }

        $item->quantity = $quantity;
        $item->total_price = $quantity * $item->unit_price;
        $item->save();

        return $item;
    }

    public function removeItem(CartItem $item)
    {
        $item->delete();
    }

    public function clear()
    {
        $this->items()->delete();
        $this->update([
            'coupon_code' => null,
            'discount_amount' => 0,
        ]);
    }
}
