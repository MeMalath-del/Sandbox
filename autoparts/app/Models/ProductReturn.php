<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'order_id',
        'user_id',
        'store_id',
        'type',
        'status',
        'reason',
        'reason_details',
        'images',
        'refund_method',
        'refund_amount',
        'refund_status',
        'tracking_number',
        'shipping_carrier',
        'admin_notes',
        'rejection_reason',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'images' => 'array',
        'refund_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($return) {
            $return->return_number = 'RET-' . strtoupper(uniqid());
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approve($userId)
    {
        $this->update([
            'status' => 'approved',
            'processed_by' => $userId,
            'processed_at' => now(),
        ]);
    }

    public function reject($reason, $userId)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'processed_by' => $userId,
            'processed_at' => now(),
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
