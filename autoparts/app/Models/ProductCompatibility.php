<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCompatibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'car_make_id',
        'car_model_id',
        'car_year_id',
        'year_from',
        'year_to',
        'engine_type',
        'engine_code',
        'transmission',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function carMake()
    {
        return $this->belongsTo(CarMake::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function carYear()
    {
        return $this->belongsTo(CarYear::class);
    }

    public function getDisplayNameAttribute()
    {
        $parts = [];
        
        if ($this->carMake) {
            $parts[] = $this->carMake->name;
        }
        
        if ($this->carModel) {
            $parts[] = $this->carModel->name;
        }
        
        if ($this->year_from && $this->year_to) {
            $parts[] = "({$this->year_from}-{$this->year_to})";
        } elseif ($this->carYear) {
            $parts[] = "({$this->carYear->year})";
        }
        
        return implode(' ', $parts);
    }
}
