<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'code', 'type', 'discount_value', 'min_order_amount',
        'max_discount', 'usage_limit', 'usage_limit_per_user', 'times_used',
        'starts_at', 'expires_at', 'is_active', 'is_public',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at > now()) return false;
        if ($this->expires_at && $this->expires_at < now()) return false;
        if ($this->usage_limit && $this->times_used >= $this->usage_limit) return false;
        return true;
    }

    public function canBeUsedBy(User $user)
    {
        if (!$this->isValid()) return false;
        
        if ($this->usage_limit_per_user) {
            $userUsages = $this->usages()->where('user_id', $user->id)->count();
            if ($userUsages >= $this->usage_limit_per_user) return false;
        }
        
        return true;
    }

    public function calculateDiscount($amount)
    {
        if ($this->type === 'fixed') {
            $discount = $this->discount_value;
        } else {
            $discount = $amount * ($this->discount_value / 100);
        }

        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        return min($discount, $amount);
    }
}
