<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'amount', 'balance', 'purchaser_id', 'recipient_id',
        'recipient_email', 'recipient_name', 'message', 'design',
        'send_date', 'expires_at', 'redeemed_at', 'status',
    ];

    protected $casts = [
        'send_date' => 'datetime',
        'expires_at' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    public function purchaser()
    {
        return $this->belongsTo(User::class, 'purchaser_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function transactions()
    {
        return $this->hasMany(GiftCardTransaction::class);
    }

    public function getFormattedCodeAttribute()
    {
        return implode('-', str_split($this->code, 4));
    }
}
