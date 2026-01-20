<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function credit($amount, $description, $reference = null)
    {
        $this->increment('balance', $amount);

        return $this->transactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'description' => $description,
            'reference' => $reference,
            'status' => 'completed',
        ]);
    }

    public function debit($amount, $description, $reference = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('رصيد غير كافي');
        }

        $this->decrement('balance', $amount);

        return $this->transactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description,
            'reference' => $reference,
            'status' => 'completed',
        ]);
    }
}
