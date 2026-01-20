<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachments',
        'is_internal',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $ticket = $reply->ticket;
            
            // Update first response time if this is staff reply
            if (!$ticket->first_response_at && $reply->user_id !== $ticket->user_id) {
                $ticket->update(['first_response_at' => now()]);
            }
            
            // Update ticket status
            if ($reply->user_id === $ticket->user_id) {
                $ticket->update(['status' => 'pending']);
            } else {
                $ticket->update(['status' => 'in_progress']);
            }
        });
    }
}
