<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'total_usage_limit',
        'per_user_limit',
        'times_used',
        'first_order_only',
        'new_users_only',
        'applicable_products',
        'applicable_categories',
        'applicable_brands',
        'excluded_products',
        'applicable_users',
        'applicable_payment_methods',
        'applicable_regions',
        'is_combinable',
        'is_active',
        'is_public',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'applicable_brands' => 'array',
        'excluded_products' => 'array',
        'applicable_users' => 'array',
        'applicable_payment_methods' => 'array',
        'applicable_regions' => 'array',
        'first_order_only' => 'boolean',
        'new_users_only' => 'boolean',
        'is_combinable' => 'boolean',
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

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        if ($this->total_usage_limit && $this->times_used >= $this->total_usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedBy(User $user)
    {
        if (!$this->isValid()) {
            return false;
        }

        // Check user usage limit
        $userUsages = $this->usages()->where('user_id', $user->id)->count();
        if ($userUsages >= $this->per_user_limit) {
            return false;
        }

        // Check if first order only
        if ($this->first_order_only && $user->orders()->completed()->count() > 0) {
            return false;
        }

        // Check if new users only
        if ($this->new_users_only && $user->created_at->lt(now()->subDays(30))) {
            return false;
        }

        // Check applicable users
        if ($this->applicable_users && !in_array($user->id, $this->applicable_users)) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($amount)
    {
        if ($this->min_order_amount && $amount < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->discount_type === 'percentage') {
            $discount = $amount * ($this->discount_value / 100);
        } elseif ($this->discount_type === 'fixed') {
            $discount = $this->discount_value;
        }

        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        return min($discount, $amount);
    }

    public function incrementUsage()
    {
        $this->increment('times_used');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
