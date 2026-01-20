<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_id', 'store_id', 'type', 'status', 'notes',
        'store_note', 'total_amount', 'approved_at', 'approved_by',
        'rejected_at', 'rejection_reason', 'received_at', 'completed_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    public function images()
    {
        return $this->hasMany(ReturnImage::class, 'return_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(ReturnStatusHistory::class, 'return_id');
    }
}
