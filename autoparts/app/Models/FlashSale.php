<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'starts_at', 'ends_at', 'status'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(FlashSaleItem::class);
    }

    public function notifications()
    {
        return $this->hasMany(FlashSaleNotification::class);
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
