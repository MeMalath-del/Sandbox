<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'evidence',
        'status',
        'resolution_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'evidence' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportable()
    {
        return $this->morphTo();
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function resolve($notes, $userId)
    {
        $this->update([
            'status' => 'resolved',
            'resolution_notes' => $notes,
            'resolved_by' => $userId,
            'resolved_at' => now(),
        ]);
    }

    public function dismiss($notes, $userId)
    {
        $this->update([
            'status' => 'dismissed',
            'resolution_notes' => $notes,
            'resolved_by' => $userId,
            'resolved_at' => now(),
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
