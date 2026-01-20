<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'flash_sale_id', 'product_id', 'sale_price', 'quantity_limit',
        'per_user_limit', 'sold_quantity',
    ];

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(FlashSalePurchase::class);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->product->price > 0) {
            return round((1 - $this->sale_price / $this->product->price) * 100);
        }
        return 0;
    }

    public function getRemainingQuantityAttribute()
    {
        return $this->quantity_limit - $this->sold_quantity;
    }
}
