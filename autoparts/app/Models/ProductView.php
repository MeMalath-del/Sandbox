<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductView extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a product view
     */
    public static function record(Product $product): void
    {
        $userId = Auth::id();
        $sessionId = session()->getId();
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();
        $referrer = request()->header('referer');

        // Avoid duplicate views from the same user/session within 1 hour
        $exists = static::where('product_id', $product->id)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        if (!$exists) {
            static::create([
                'product_id' => $product->id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'referrer' => $referrer,
            ]);
        }
    }
}
