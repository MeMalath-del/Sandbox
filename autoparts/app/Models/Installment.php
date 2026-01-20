<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_id', 'original_amount', 'total_amount',
        'monthly_amount', 'interest_rate', 'months', 'status', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(InstallmentPayment::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getNextPaymentAttribute()
    {
        return $this->payments()->where('status', 'pending')->orderBy('due_date')->first();
    }
}
