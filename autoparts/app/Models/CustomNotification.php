<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomNotification extends Model
{
    use HasFactory;

    protected $table = 'custom_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'icon',
        'image',
        'action_url',
        'data',
        'is_read',
        'read_at',
        'is_push_sent',
        'is_email_sent',
        'is_sms_sent',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_push_sent' => 'boolean',
        'is_email_sent' => 'boolean',
        'is_sms_sent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public static function send(User $user, $type, $title, $body, $data = [], $actionUrl = null)
    {
        return static::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'action_url' => $actionUrl,
        ]);
    }
}
