<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'reviewable_type', 'reviewable_id', 'rating', 'title',
        'comment', 'pros', 'cons', 'is_verified_purchase', 'status',
        'helpful_count', 'not_helpful_count', 'store_reply', 'replied_at',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'replied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'reviewable_id')
            ->where('reviewable_type', Product::class);
    }

    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function votes()
    {
        return $this->hasMany(ReviewVote::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
