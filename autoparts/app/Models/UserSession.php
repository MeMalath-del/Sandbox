<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'ip_address',
        'user_agent',
        'device_type',
        'device_name',
        'browser',
        'platform',
        'location',
        'last_activity_at',
        'is_current',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'is_current' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }

    public function terminate()
    {
        $this->delete();
    }

    public function scopeActive($query)
    {
        return $query->where('last_activity_at', '>=', now()->subHours(24));
    }

    public static function createForUser(User $user, $token)
    {
        $agent = new \Jenssegers\Agent\Agent();
        
        return static::create([
            'user_id' => $user->id,
            'token' => $token,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_type' => $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
            'device_name' => $agent->device(),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'last_activity_at' => now(),
            'is_current' => true,
        ]);
    }
}
