<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id', 'type', 'amount', 'description', 'reference',
        'payment_method', 'status', 'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'credit' => 'success',
            'debit' => 'danger',
            'withdrawal' => 'warning',
            default => 'secondary',
        };
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'credit' => 'bi-arrow-down-circle',
            'debit' => 'bi-arrow-up-circle',
            'withdrawal' => 'bi-bank',
            default => 'bi-circle',
        };
    }
}
