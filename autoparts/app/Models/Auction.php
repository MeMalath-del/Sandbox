<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id', 'store_id', 'starting_price', 'current_price',
        'reserve_price', 'buy_now_price', 'min_increment', 'starts_at',
        'ends_at', 'status', 'winner_id', 'final_price', 'sold_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'sold_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function bids()
    {
        return $this->hasMany(AuctionBid::class);
    }

    public function highestBid()
    {
        return $this->hasOne(AuctionBid::class)->orderByDesc('amount');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'active')
            ->where('starts_at', '>', now());
    }

    public function scopeEnded($query)
    {
        return $query->where('ends_at', '<', now());
    }

    public function isActive()
    {
        return $this->status === 'active' && 
            $this->starts_at <= now() && 
            $this->ends_at > now();
    }
}
