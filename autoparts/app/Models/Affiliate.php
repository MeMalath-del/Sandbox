<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'code', 'commission_rate', 'status',
        'total_clicks', 'total_orders', 'total_earnings', 'available_balance',
        'bank_name', 'account_number', 'account_holder',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clicks()
    {
        return $this->hasMany(AffiliateClick::class);
    }

    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(AffiliateWithdrawal::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
