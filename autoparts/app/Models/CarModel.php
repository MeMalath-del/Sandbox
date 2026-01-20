<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = ['make_id', 'name', 'name_ar', 'slug', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function make()
    {
        return $this->belongsTo(CarMake::class, 'make_id');
    }

    public function years()
    {
        return $this->hasMany(CarYear::class, 'model_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
