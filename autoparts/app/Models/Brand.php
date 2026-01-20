<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'logo',
        'website',
        'country_of_origin',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'products_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo 
            ? asset('storage/' . $this->logo) 
            : asset('images/default-brand.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function updateProductsCount()
    {
        $this->products_count = $this->products()->count();
        $this->save();
    }
}
