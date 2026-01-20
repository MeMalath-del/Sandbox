<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'order_id',
        'store_id',
        'type',
        'subject',
        'last_message_at',
        'last_message_by',
        'user_one_archived',
        'user_two_archived',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'user_one_archived' => 'boolean',
        'user_two_archived' => 'boolean',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'desc');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function lastMessageBy()
    {
        return $this->belongsTo(User::class, 'last_message_by');
    }

    public function getOtherUser(User $user)
    {
        return $this->user_one_id === $user->id ? $this->userTwo : $this->userOne;
    }

    public function isArchivedBy(User $user)
    {
        if ($this->user_one_id === $user->id) {
            return $this->user_one_archived;
        }
        return $this->user_two_archived;
    }

    public function archiveFor(User $user)
    {
        if ($this->user_one_id === $user->id) {
            $this->update(['user_one_archived' => true]);
        } else {
            $this->update(['user_two_archived' => true]);
        }
    }

    public function unarchiveFor(User $user)
    {
        if ($this->user_one_id === $user->id) {
            $this->update(['user_one_archived' => false]);
        } else {
            $this->update(['user_two_archived' => false]);
        }
    }

    public function getUnreadCountFor(User $user)
    {
        return $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
    }

    public function markAsReadFor(User $user)
    {
        $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public static function findOrCreateBetween(User $userOne, User $userTwo, $type = 'user_store', $orderId = null, $storeId = null)
    {
        $conversation = static::where(function ($query) use ($userOne, $userTwo) {
            $query->where('user_one_id', $userOne->id)->where('user_two_id', $userTwo->id);
        })->orWhere(function ($query) use ($userOne, $userTwo) {
            $query->where('user_one_id', $userTwo->id)->where('user_two_id', $userOne->id);
        })->first();

        if (!$conversation) {
            $conversation = static::create([
                'user_one_id' => $userOne->id,
                'user_two_id' => $userTwo->id,
                'type' => $type,
                'order_id' => $orderId,
                'store_id' => $storeId,
            ]);
        }

        return $conversation;
    }
}
