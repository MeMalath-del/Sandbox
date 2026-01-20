<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'driver_id',
        'vehicle_type',
        'make',
        'model',
        'year',
        'color',
        'plate_number',
        'registration_number',
        'registration_image',
        'registration_expiry',
        'insurance_number',
        'insurance_image',
        'insurance_expiry',
        'inspection_image',
        'inspection_expiry',
        'vehicle_images',
        'max_weight_capacity',
        'max_volume_capacity',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'vehicle_images' => 'array',
        'registration_expiry' => 'date',
        'insurance_expiry' => 'date',
        'inspection_expiry' => 'date',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'max_weight_capacity' => 'decimal:2',
        'max_volume_capacity' => 'decimal:2',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->make} {$this->model} ({$this->year})";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
