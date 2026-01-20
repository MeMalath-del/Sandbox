<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'content',
        'content_en',
        'meta_title',
        'meta_description',
        'is_active',
        'show_in_footer',
        'show_in_header',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_footer' => 'boolean',
        'show_in_header' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInFooter($query)
    {
        return $query->where('show_in_footer', true);
    }

    public function scopeInHeader($query)
    {
        return $query->where('show_in_header', true);
    }
}
