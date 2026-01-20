<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPoints extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'balance', 'total_earned', 'total_redeemed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(LoyaltyTransaction::class, 'user_id', 'user_id');
    }

    public function earn($points, $description, $orderId = null)
    {
        $this->increment('balance', $points);
        $this->increment('total_earned', $points);

        LoyaltyTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'earned',
            'points' => $points,
            'description' => $description,
            'order_id' => $orderId,
            'balance_after' => $this->balance,
        ]);
    }

    public function redeem($points, $description)
    {
        if ($this->balance < $points) {
            throw new \Exception('رصيد النقاط غير كافي');
        }

        $this->decrement('balance', $points);
        $this->increment('total_redeemed', $points);

        LoyaltyTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'redeemed',
            'points' => -$points,
            'description' => $description,
            'balance_after' => $this->balance,
        ]);
    }

    public function getTierAttribute()
    {
        return match(true) {
            $this->total_earned >= 10000 => 'platinum',
            $this->total_earned >= 5000 => 'gold',
            $this->total_earned >= 1000 => 'silver',
            default => 'bronze',
        };
    }
}
