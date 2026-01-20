<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnImage extends Model
{
    use HasFactory;

    protected $fillable = ['return_id', 'image_path'];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class, 'return_id');
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
