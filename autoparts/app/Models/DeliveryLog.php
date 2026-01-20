<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'status',
        'previous_status',
        'latitude',
        'longitude',
        'notes',
        'photo',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
