<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_number',
        'order_id',
        'user_id',
        'payment_type',
        'payment_method',
        'status',
        'amount',
        'fee',
        'net_amount',
        'currency',
        'gateway',
        'gateway_transaction_id',
        'gateway_reference',
        'gateway_response',
        'card_brand',
        'card_last_four',
        'refund_of',
        'refund_reason',
        'notes',
        'failure_reason',
        'paid_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $payment->payment_number = 'PAY-' . strtoupper(uniqid());
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function refundOf()
    {
        return $this->belongsTo(Payment::class, 'refund_of');
    }

    public function refunds()
    {
        return $this->hasMany(Payment::class, 'refund_of');
    }

    public function isSuccessful()
    {
        return $this->status === 'completed';
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
