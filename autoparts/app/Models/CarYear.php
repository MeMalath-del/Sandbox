<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_model_id',
        'year',
        'engine_type',
        'engine_size',
        'transmission',
        'drive_type',
        'engine_code',
        'chassis_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function compatibilities()
    {
        return $this->hasMany(ProductCompatibility::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
