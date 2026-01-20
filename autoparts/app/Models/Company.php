<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'trade_name',
        'commercial_register_number',
        'commercial_register_image',
        'tax_number',
        'tax_certificate_image',
        'activity_type',
        'company_size',
        'establishment_year',
        'headquarters_address',
        'website',
        'logo',
        'credit_limit',
        'payment_terms',
        'bank_name',
        'bank_account_number',
        'bank_iban',
        'trade_reference_1',
        'trade_reference_2',
        'status',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'establishment_year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
