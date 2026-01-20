<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_make_id',
        'name',
        'name_en',
        'slug',
        'year_from',
        'year_to',
        'body_type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function make()
    {
        return $this->belongsTo(CarMake::class, 'car_make_id');
    }

    public function years()
    {
        return $this->hasMany(CarYear::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullNameAttribute()
    {
        return $this->make->name . ' ' . $this->name;
    }
}
