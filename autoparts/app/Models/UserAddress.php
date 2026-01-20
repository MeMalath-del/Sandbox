<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'recipient_phone',
        'country',
        'region',
        'city',
        'district',
        'street',
        'building_number',
        'apartment_number',
        'postal_code',
        'landmark',
        'latitude',
        'longitude',
        'is_default',
        'is_verified',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_default' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'address_id');
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->building_number,
            $this->street,
            $this->district,
            $this->city,
            $this->region,
            $this->postal_code,
        ]);
        
        return implode(', ', $parts);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
