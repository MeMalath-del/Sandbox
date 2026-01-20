<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPoints extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'total_earned',
        'total_spent',
        'total_expired',
        'tier',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(LoyaltyTransaction::class, 'user_id', 'user_id');
    }

    public function addPoints($points, $type, $description = null, $orderId = null, $expiresAt = null)
    {
        $balanceBefore = $this->balance;
        $this->increment('balance', $points);
        $this->increment('total_earned', $points);

        LoyaltyTransaction::create([
            'user_id' => $this->user_id,
            'order_id' => $orderId,
            'type' => $type,
            'points' => $points,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description,
            'expires_at' => $expiresAt ?? now()->addYear(),
        ]);

        $this->updateTier();

        return $this;
    }

    public function redeemPoints($points, $orderId = null, $description = null)
    {
        if ($this->balance < $points) {
            throw new \Exception('Insufficient points balance');
        }

        $balanceBefore = $this->balance;
        $this->decrement('balance', $points);
        $this->increment('total_spent', $points);

        LoyaltyTransaction::create([
            'user_id' => $this->user_id,
            'order_id' => $orderId,
            'type' => 'spent',
            'points' => -$points,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description ?? 'Points redeemed',
        ]);

        return $this;
    }

    public function updateTier()
    {
        $totalEarned = $this->total_earned;

        if ($totalEarned >= 50000) {
            $tier = 'vip';
        } elseif ($totalEarned >= 20000) {
            $tier = 'platinum';
        } elseif ($totalEarned >= 10000) {
            $tier = 'gold';
        } elseif ($totalEarned >= 5000) {
            $tier = 'silver';
        } else {
            $tier = 'bronze';
        }

        $this->update(['tier' => $tier]);
    }

    public function getPointsValue($points = null)
    {
        $points = $points ?? $this->balance;
        return $points * 0.01; // 1 point = 0.01 SAR
    }
}
