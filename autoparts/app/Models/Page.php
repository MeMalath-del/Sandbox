<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content', 'status', 'views_count',
        'meta_title', 'meta_description',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
