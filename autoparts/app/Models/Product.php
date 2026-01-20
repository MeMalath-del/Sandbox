<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'category_id',
        'brand_id',
        'name',
        'name_en',
        'slug',
        'short_description',
        'description',
        'sku',
        'barcode',
        'oem_number',
        'alternative_numbers',
        'manufacturer',
        'country_of_origin',
        'manufacture_year',
        'quality_grade',
        'condition',
        'condition_notes',
        'cost_price',
        'price',
        'sale_price',
        'wholesale_price',
        'company_price',
        'sale_starts_at',
        'sale_ends_at',
        'quantity',
        'reserved_quantity',
        'low_stock_threshold',
        'min_order_quantity',
        'max_order_quantity',
        'allow_backorder',
        'backorder_availability',
        'weight',
        'length',
        'width',
        'height',
        'warranty_months',
        'warranty_terms',
        'return_days',
        'return_policy',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'rejection_reason',
        'is_featured',
        'is_bestseller',
        'is_new_arrival',
        'views_count',
        'sales_count',
        'wishlist_count',
        'rating',
        'rating_count',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'alternative_numbers' => 'array',
        'meta_keywords' => 'array',
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
        'backorder_availability' => 'date',
        'approved_at' => 'datetime',
        'allow_backorder' => 'boolean',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_new_arrival' => 'boolean',
        'cost_price' => 'decimal:2',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'company_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'rating' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order');
    }

    public function compatibilities()
    {
        return $this->hasMany(ProductCompatibility::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function views()
    {
        return $this->hasMany(ProductView::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getCurrentPriceAttribute()
    {
        if ($this->isOnSale()) {
            return $this->sale_price;
        }
        return $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price > 0) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->quantity - $this->reserved_quantity;
    }

    public function getImageUrlAttribute()
    {
        $primary = $this->primaryImage;
        if ($primary) {
            return asset('storage/' . $primary->image_path);
        }
        return asset('images/default-product.png');
    }

    // Methods
    public function isOnSale()
    {
        if (!$this->sale_price) {
            return false;
        }

        $now = now();
        
        if ($this->sale_starts_at && $now->lt($this->sale_starts_at)) {
            return false;
        }
        
        if ($this->sale_ends_at && $now->gt($this->sale_ends_at)) {
            return false;
        }

        return true;
    }

    public function isInStock()
    {
        return $this->available_quantity > 0 || $this->allow_backorder;
    }

    public function isLowStock()
    {
        return $this->available_quantity <= $this->low_stock_threshold;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementSales($quantity = 1)
    {
        $this->increment('sales_count', $quantity);
    }

    public function updateRating()
    {
        $this->rating = $this->reviews()->where('status', 'approved')->avg('rating') ?? 0;
        $this->rating_count = $this->reviews()->where('status', 'approved')->count();
        $this->save();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->whereRaw('quantity - reserved_quantity > 0')
              ->orWhere('allow_backorder', true);
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestseller($query)
    {
        return $query->where('is_bestseller', true);
    }

    public function scopeNewArrival($query)
    {
        return $query->where('is_new_arrival', true);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
            ->where(function ($q) {
                $q->whereNull('sale_starts_at')->orWhere('sale_starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('sale_ends_at')->orWhere('sale_ends_at', '>=', now());
            });
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('name_en', 'like', "%{$term}%")
              ->orWhere('sku', 'like', "%{$term}%")
              ->orWhere('oem_number', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopeInPriceRange($query, $min, $max)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }
}
