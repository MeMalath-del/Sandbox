<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarMake extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'logo',
        'country_of_origin',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function models()
    {
        return $this->hasMany(CarModel::class);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo 
            ? asset('storage/' . $this->logo) 
            : asset('images/default-car-make.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
