<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomNotification extends Model
{
    use HasFactory;

    protected $table = 'custom_notifications';

    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'data', 'action_url',
        'icon', 'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public static function send($userId, $type, $title, $message, $data = [], $actionUrl = null)
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'icon' => static::getIconForType($type),
        ]);
    }

    protected static function getIconForType($type)
    {
        return match($type) {
            'order' => 'bi-bag-check',
            'payment' => 'bi-credit-card',
            'delivery' => 'bi-truck',
            'review' => 'bi-star',
            'promotion' => 'bi-gift',
            'system' => 'bi-bell',
            default => 'bi-bell',
        };
    }
}
