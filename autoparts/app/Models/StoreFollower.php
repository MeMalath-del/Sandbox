<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreFollower extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'notifications_enabled',
    ];

    protected $casts = [
        'notifications_enabled' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
