<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'phone_secondary',
        'username',
        'avatar',
        'birth_date',
        'gender',
        'nationality',
        'id_number',
        'id_image_front',
        'id_image_back',
        'status',
        'is_verified',
        'phone_verified',
        'email_verified',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'preferred_language',
        'preferred_currency',
        'timezone',
        'dark_mode',
        'notifications_email',
        'notifications_sms',
        'notifications_push',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'is_verified' => 'boolean',
            'phone_verified' => 'boolean',
            'email_verified' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'dark_mode' => 'boolean',
            'notifications_email' => 'boolean',
            'notifications_sms' => 'boolean',
            'notifications_push' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // User Types
    public function isBuyerIndividual(): bool
    {
        return $this->user_type === 'buyer_individual';
    }

    public function isBuyerCompany(): bool
    {
        return $this->user_type === 'buyer_company';
    }

    public function isDriverIndividual(): bool
    {
        return $this->user_type === 'driver_individual';
    }

    public function isDriverCompany(): bool
    {
        return $this->user_type === 'driver_company';
    }

    public function isStoreIndividual(): bool
    {
        return $this->user_type === 'store_individual';
    }

    public function isStoreCompany(): bool
    {
        return $this->user_type === 'store_company';
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    public function isBuyer(): bool
    {
        return in_array($this->user_type, ['buyer_individual', 'buyer_company']);
    }

    public function isDriver(): bool
    {
        return in_array($this->user_type, ['driver_individual', 'driver_company']);
    }

    public function isStore(): bool
    {
        return in_array($this->user_type, ['store_individual', 'store_company']);
    }

    // Relationships
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(UserAddress::class)->where('is_default', true);
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function cars()
    {
        return $this->hasMany(UserCar::class);
    }

    public function defaultCar()
    {
        return $this->hasOne(UserCar::class)->where('is_default', true);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoints::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'user_one_id')
            ->orWhere('user_two_id', $this->id);
    }

    public function notifications()
    {
        return $this->hasMany(CustomNotification::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredBy()
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }

    public function followedStores()
    {
        return $this->belongsToMany(Store::class, 'store_followers')
            ->withPivot('notifications_enabled')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('user_type', $type);
    }

    public function scopeBuyers($query)
    {
        return $query->whereIn('user_type', ['buyer_individual', 'buyer_company']);
    }

    public function scopeDrivers($query)
    {
        return $query->whereIn('user_type', ['driver_individual', 'driver_company']);
    }

    public function scopeStores($query)
    {
        return $query->whereIn('user_type', ['store_individual', 'store_company']);
    }

    // Helpers
    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    public function getReferralCodeAttribute()
    {
        return 'REF' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
