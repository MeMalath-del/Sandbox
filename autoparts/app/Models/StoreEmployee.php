<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'role',
        'permissions',
        'is_active',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasPermission($permission)
    {
        if ($this->role === 'owner') {
            return true;
        }

        return in_array($permission, $this->permissions ?? []);
    }
}
