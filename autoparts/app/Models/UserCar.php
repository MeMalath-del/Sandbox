<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'car_make_id',
        'car_model_id',
        'car_year_id',
        'custom_make',
        'custom_model',
        'year',
        'vin',
        'plate_number',
        'color',
        'engine_type',
        'transmission',
        'mileage',
        'purchase_date',
        'images',
        'nickname',
        'is_default',
    ];

    protected $casts = [
        'images' => 'array',
        'purchase_date' => 'date',
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function make()
    {
        return $this->belongsTo(CarMake::class, 'car_make_id');
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function carYear()
    {
        return $this->belongsTo(CarYear::class, 'car_year_id');
    }

    public function getDisplayNameAttribute()
    {
        if ($this->nickname) {
            return $this->nickname;
        }

        $make = $this->make ? $this->make->name : $this->custom_make;
        $model = $this->model ? $this->model->name : $this->custom_model;
        
        return "{$make} {$model} ({$this->year})";
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
