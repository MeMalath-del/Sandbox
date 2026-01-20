<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'name',
        'slug',
        'description',
        'short_description',
        'logo',
        'cover_image',
        'phone',
        'email',
        'whatsapp',
        'address',
        'city',
        'region',
        'latitude',
        'longitude',
        'working_hours',
        'holidays',
        'is_open',
        'store_type',
        'specializations',
        'brands',
        'commission_rate',
        'rating',
        'rating_count',
        'total_products',
        'total_orders',
        'total_sales',
        'return_policy',
        'warranty_policy',
        'terms_conditions',
        'payment_methods',
        'shipping_methods',
        'is_featured',
        'is_verified',
        'status',
        'meta_title',
        'meta_description',
        'social_links',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'holidays' => 'array',
        'specializations' => 'array',
        'brands' => 'array',
        'payment_methods' => 'array',
        'shipping_methods' => 'array',
        'social_links' => 'array',
        'is_open' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'commission_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'total_sales' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employees()
    {
        return $this->hasMany(StoreEmployee::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'store_followers')
            ->withPivot('notifications_enabled')
            ->withTimestamps();
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo 
            ? asset('storage/' . $this->logo) 
            : asset('images/default-store.png');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function updateRating()
    {
        $this->rating = $this->reviews()->avg('rating') ?? 0;
        $this->rating_count = $this->reviews()->count();
        $this->save();
    }

    public function incrementOrdersCount()
    {
        $this->increment('total_orders');
    }

    public function addToSales($amount)
    {
        $this->increment('total_sales', $amount);
    }
}
