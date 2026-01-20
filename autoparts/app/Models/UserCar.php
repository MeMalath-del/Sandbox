<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'make_id', 'model_id', 'year', 'nickname',
        'vin', 'plate_number', 'color', 'mileage', 'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function make()
    {
        return $this->belongsTo(CarMake::class, 'make_id');
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    public function getFullNameAttribute()
    {
        return $this->make->name . ' ' . $this->model->name . ' ' . $this->year;
    }
}
