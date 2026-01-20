<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'wallet_id',
        'user_id',
        'type',
        'amount',
        'fee',
        'balance_before',
        'balance_after',
        'status',
        'order_id',
        'payment_id',
        'related_user_id',
        'description',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->transaction_number = 'TXN-' . strtoupper(uniqid());
        });
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    public function isCredit()
    {
        return $this->amount > 0;
    }

    public function isDebit()
    {
        return $this->amount < 0;
    }
}
