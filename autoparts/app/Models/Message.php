<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'type',
        'content',
        'attachments',
        'product_id',
        'order_id',
        'location_lat',
        'location_lng',
        'is_read',
        'read_at',
        'is_deleted_by_sender',
        'is_deleted_by_receiver',
    ];

    protected $casts = [
        'attachments' => 'array',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_deleted_by_sender' => 'boolean',
        'is_deleted_by_receiver' => 'boolean',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
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

    public function deleteForUser(User $user)
    {
        if ($this->sender_id === $user->id) {
            $this->update(['is_deleted_by_sender' => true]);
        } else {
            $this->update(['is_deleted_by_receiver' => true]);
        }
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
