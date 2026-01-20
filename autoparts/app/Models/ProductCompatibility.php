<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCompatibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'car_make_id', 'car_model_id', 'year_from', 'year_to', 'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function make()
    {
        return $this->belongsTo(CarMake::class, 'car_make_id');
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function getYearRangeAttribute()
    {
        if ($this->year_from && $this->year_to) {
            return $this->year_from . ' - ' . $this->year_to;
        }
        if ($this->year_from) {
            return $this->year_from . ' وما بعد';
        }
        if ($this->year_to) {
            return 'حتى ' . $this->year_to;
        }
        return 'جميع السنوات';
    }
}
