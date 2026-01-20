<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'store_id',
        'address_id',
        'driver_id',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'shipping_method',
        'tracking_number',
        'expected_delivery_date',
        'delivered_at',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_region',
        'shipping_postal_code',
        'shipping_latitude',
        'shipping_longitude',
        'billing_name',
        'billing_company',
        'billing_tax_number',
        'billing_address',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'shipping_cost',
        'tax_amount',
        'tax_rate',
        'total',
        'commission_amount',
        'driver_fee',
        'points_earned',
        'points_used',
        'wallet_amount_used',
        'customer_notes',
        'store_notes',
        'driver_notes',
        'admin_notes',
        'delivery_instructions',
        'allow_leave_at_door',
        'call_before_delivery',
        'confirmed_at',
        'processing_at',
        'shipped_at',
        'cancelled_at',
        'cancellation_reason',
        'cancelled_by',
    ];

    protected $casts = [
        'expected_delivery_date' => 'date',
        'delivered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'processing_at' => 'datetime',
        'shipped_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'allow_leave_at_door' => 'boolean',
        'call_before_delivery' => 'boolean',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'total' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'driver_fee' => 'decimal:2',
        'wallet_amount_used' => 'decimal:2',
        'shipping_latitude' => 'decimal:8',
        'shipping_longitude' => 'decimal:8',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function returns()
    {
        return $this->hasMany(ProductReturn::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    // Status Checks
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return in_array($this->status, ['confirmed', 'processing', 'ready_for_shipping', 'shipped', 'out_for_delivery', 'delivered', 'completed']);
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed', 'processing']);
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    // Methods
    public function updateStatus($status, $userId = null, $comment = null)
    {
        $previousStatus = $this->status;
        $this->status = $status;
        
        // Update timestamps
        switch ($status) {
            case 'confirmed':
                $this->confirmed_at = now();
                break;
            case 'processing':
                $this->processing_at = now();
                break;
            case 'shipped':
                $this->shipped_at = now();
                break;
            case 'delivered':
            case 'completed':
                $this->delivered_at = now();
                break;
            case 'cancelled':
                $this->cancelled_at = now();
                $this->cancelled_by = $userId;
                break;
        }
        
        $this->save();

        // Log status change
        $this->statusHistory()->create([
            'status' => $status,
            'previous_status' => $previousStatus,
            'comment' => $comment,
            'changed_by' => $userId,
        ]);

        return $this;
    }

    public function calculateTotal()
    {
        $this->subtotal = $this->items->sum('total');
        $this->tax_amount = ($this->subtotal - $this->discount_amount) * ($this->tax_rate / 100);
        $this->total = $this->subtotal - $this->discount_amount + $this->shipping_cost + $this->tax_amount;
        $this->save();
    }

    // Scopes
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeForStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeForDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }
}
