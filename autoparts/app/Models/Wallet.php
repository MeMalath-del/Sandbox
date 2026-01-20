<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'pending_balance',
        'total_earned',
        'total_spent',
        'total_withdrawn',
        'currency',
        'is_active',
        'pin',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'pin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class)->orderBy('created_at', 'desc');
    }

    public function credit($amount, $type, $description = null, $orderId = null, $relatedUserId = null)
    {
        $balanceBefore = $this->balance;
        $this->increment('balance', $amount);
        $this->increment('total_earned', $amount);

        return $this->transactions()->create([
            'user_id' => $this->user_id,
            'type' => $type,
            'amount' => $amount,
            'fee' => 0,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'status' => 'completed',
            'order_id' => $orderId,
            'related_user_id' => $relatedUserId,
            'description' => $description,
        ]);
    }

    public function debit($amount, $type, $description = null, $orderId = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient wallet balance');
        }

        $balanceBefore = $this->balance;
        $this->decrement('balance', $amount);
        $this->increment('total_spent', $amount);

        return $this->transactions()->create([
            'user_id' => $this->user_id,
            'type' => $type,
            'amount' => -$amount,
            'fee' => 0,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'status' => 'completed',
            'order_id' => $orderId,
            'description' => $description,
        ]);
    }

    public function hasEnoughBalance($amount)
    {
        return $this->balance >= $amount;
    }
}
