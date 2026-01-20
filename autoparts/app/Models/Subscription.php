<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan_id', 'starts_at', 'ends_at', 'status',
        'amount_paid', 'cancelled_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && $this->ends_at > now();
    }

    public function getDaysRemainingAttribute()
    {
        return max(0, now()->diffInDays($this->ends_at, false));
    }
}
