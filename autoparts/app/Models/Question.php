<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id', 'question', 'status', 'helpful_count',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function storeAnswer()
    {
        return $this->hasOne(Answer::class)->where('is_store_answer', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
