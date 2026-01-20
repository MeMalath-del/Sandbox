<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'title_en',
        'subtitle',
        'image',
        'image_mobile',
        'link_url',
        'link_type',
        'link_id',
        'position',
        'starts_at',
        'ends_at',
        'is_active',
        'sort_order',
        'views_count',
        'clicks_count',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }

    public function getMobileImageUrlAttribute()
    {
        return $this->image_mobile 
            ? asset('storage/' . $this->image_mobile) 
            : $this->image_url;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementClicks()
    {
        $this->increment('clicks_count');
    }

    public function isActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }
}
