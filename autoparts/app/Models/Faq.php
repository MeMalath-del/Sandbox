<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'question',
        'question_en',
        'answer',
        'answer_en',
        'is_active',
        'sort_order',
        'views_count',
        'helpful_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function markHelpful()
    {
        $this->increment('helpful_count');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
