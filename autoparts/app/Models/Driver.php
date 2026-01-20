<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'license_number',
        'license_type',
        'license_expiry',
        'license_image',
        'driver_type',
        'status',
        'is_verified',
        'current_latitude',
        'current_longitude',
        'location_updated_at',
        'service_areas',
        'working_hours',
        'rating',
        'rating_count',
        'total_deliveries',
        'completed_deliveries',
        'cancelled_deliveries',
        'total_earnings',
        'wallet_balance',
        'commission_rate',
        'accepts_cash',
        'max_orders_per_day',
        'current_orders_today',
        'auto_accept',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'location_updated_at' => 'datetime',
        'service_areas' => 'array',
        'working_hours' => 'array',
        'is_verified' => 'boolean',
        'accepts_cash' => 'boolean',
        'auto_accept' => 'boolean',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'rating' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'wallet_balance' => 'decimal:2',
        'commission_rate' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function activeVehicle()
    {
        return $this->hasOne(Vehicle::class)->where('is_active', true)->where('is_default', true);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function isAvailable()
    {
        return $this->status === 'available' && $this->is_verified;
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_verified', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function updateLocation($latitude, $longitude)
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'location_updated_at' => now(),
        ]);
    }

    public function updateRating()
    {
        $this->rating = $this->reviews()->avg('rating') ?? 0;
        $this->rating_count = $this->reviews()->count();
        $this->save();
    }

    public function addEarnings($amount)
    {
        $this->increment('total_earnings', $amount);
        $this->increment('wallet_balance', $amount);
    }
}
