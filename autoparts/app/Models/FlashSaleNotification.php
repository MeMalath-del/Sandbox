<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleNotification extends Model
{
    use HasFactory;

    protected $fillable = ['flash_sale_id', 'user_id', 'email', 'notified_at'];

    protected $casts = [
        'notified_at' => 'datetime',
    ];

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
