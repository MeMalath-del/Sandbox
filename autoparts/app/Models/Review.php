<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'quality_rating',
        'value_rating',
        'delivery_rating',
        'title',
        'comment',
        'pros',
        'cons',
        'would_recommend',
        'images',
        'video_url',
        'is_verified_purchase',
        'status',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
        'helpful_count',
        'not_helpful_count',
        'report_count',
        'reply',
        'replied_at',
        'replied_by',
    ];

    protected $casts = [
        'pros' => 'array',
        'cons' => 'array',
        'images' => 'array',
        'would_recommend' => 'boolean',
        'is_verified_purchase' => 'boolean',
        'moderated_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function votes()
    {
        return $this->hasMany(ReviewVote::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function replier()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function addReply($reply, $userId)
    {
        $this->update([
            'reply' => $reply,
            'replied_at' => now(),
            'replied_by' => $userId,
        ]);
    }

    public function approve($moderatorId)
    {
        $this->update([
            'status' => 'approved',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);

        // Update reviewable rating
        if (method_exists($this->reviewable, 'updateRating')) {
            $this->reviewable->updateRating();
        }
    }

    public function reject($reason, $moderatorId)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);
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
