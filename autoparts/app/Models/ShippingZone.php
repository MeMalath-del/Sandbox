<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'regions',
        'base_cost',
        'per_kg_cost',
        'free_shipping_threshold',
        'estimated_days_min',
        'estimated_days_max',
        'is_active',
    ];

    protected $casts = [
        'regions' => 'array',
        'base_cost' => 'decimal:2',
        'per_kg_cost' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function calculateCost($weight, $orderTotal)
    {
        if ($this->free_shipping_threshold && $orderTotal >= $this->free_shipping_threshold) {
            return 0;
        }

        return $this->base_cost + ($weight * $this->per_kg_cost);
    }

    public function getEstimatedDaysAttribute()
    {
        if ($this->estimated_days_min === $this->estimated_days_max) {
            return $this->estimated_days_min . ' يوم';
        }

        return $this->estimated_days_min . '-' . $this->estimated_days_max . ' أيام';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function findByRegion($region)
    {
        return static::active()
            ->get()
            ->first(function ($zone) use ($region) {
                return in_array($region, $zone->regions ?? []);
            });
    }
}
