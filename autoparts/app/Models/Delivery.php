<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'driver_id',
        'vehicle_id',
        'status',
        'pickup_address',
        'pickup_latitude',
        'pickup_longitude',
        'pickup_time',
        'pickup_code',
        'picked_up_at',
        'delivery_address',
        'delivery_latitude',
        'delivery_longitude',
        'expected_delivery_time',
        'delivery_code',
        'delivered_at',
        'current_latitude',
        'current_longitude',
        'location_updated_at',
        'distance_km',
        'estimated_minutes',
        'delivery_photo',
        'signature_image',
        'recipient_name',
        'delivery_fee',
        'driver_earning',
        'platform_fee',
        'tip_amount',
        'driver_notes',
        'customer_notes',
        'failure_reason',
        'delivery_attempts',
        'last_attempt_at',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
        'picked_up_at' => 'datetime',
        'expected_delivery_time' => 'datetime',
        'delivered_at' => 'datetime',
        'location_updated_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'pickup_latitude' => 'decimal:8',
        'pickup_longitude' => 'decimal:8',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'distance_km' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'driver_earning' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'tip_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function logs()
    {
        return $this->hasMany(DeliveryLog::class)->orderBy('created_at', 'desc');
    }

    public function updateStatus($status, $notes = null, $photo = null)
    {
        $previousStatus = $this->status;
        $this->status = $status;
        
        switch ($status) {
            case 'picked_up':
                $this->picked_up_at = now();
                break;
            case 'delivered':
                $this->delivered_at = now();
                break;
        }
        
        $this->save();

        $this->logs()->create([
            'status' => $status,
            'previous_status' => $previousStatus,
            'latitude' => $this->current_latitude,
            'longitude' => $this->current_longitude,
            'notes' => $notes,
            'photo' => $photo,
        ]);

        return $this;
    }

    public function updateLocation($latitude, $longitude)
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'location_updated_at' => now(),
        ]);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['delivered', 'cancelled', 'returned']);
    }

    public function scopeForDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }
}
