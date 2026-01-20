<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'status',
        'referrer_reward',
        'referred_reward',
        'referrer_rewarded',
        'referred_rewarded',
        'completed_at',
    ];

    protected $casts = [
        'referrer_reward' => 'decimal:2',
        'referred_reward' => 'decimal:2',
        'referrer_rewarded' => 'boolean',
        'referred_rewarded' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function rewardReferrer()
    {
        if ($this->referrer_rewarded || $this->status !== 'completed') {
            return false;
        }

        $wallet = $this->referrer->wallet ?? $this->referrer->wallet()->create([]);
        $wallet->credit($this->referrer_reward, 'bonus', 'Referral reward');

        $this->update(['referrer_rewarded' => true]);

        return true;
    }

    public function rewardReferred()
    {
        if ($this->referred_rewarded || $this->status !== 'completed') {
            return false;
        }

        $wallet = $this->referred->wallet ?? $this->referred->wallet()->create([]);
        $wallet->credit($this->referred_reward, 'bonus', 'Welcome bonus from referral');

        $this->update(['referred_rewarded' => true]);

        return true;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
