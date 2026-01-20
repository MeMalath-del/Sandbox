<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'name_en',
        'value',
        'value_en',
        'unit',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getDisplayValueAttribute()
    {
        return $this->unit ? "{$this->value} {$this->unit}" : $this->value;
    }
}
