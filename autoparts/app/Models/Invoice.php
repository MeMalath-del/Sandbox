<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'order_id',
        'store_id',
        'user_id',
        'type',
        'seller_name',
        'seller_tax_number',
        'seller_address',
        'buyer_name',
        'buyer_tax_number',
        'buyer_address',
        'subtotal',
        'discount',
        'shipping',
        'tax_amount',
        'tax_rate',
        'total',
        'qr_code',
        'zatca_uuid',
        'zatca_hash',
        'issue_date',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'total' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
