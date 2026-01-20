<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSalePurchase extends Model
{
    use HasFactory;

    protected $fillable = ['flash_sale_item_id', 'user_id', 'order_id', 'quantity'];

    public function item()
    {
        return $this->belongsTo(FlashSaleItem::class, 'flash_sale_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
