<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateClick extends Model
{
    use HasFactory;

    protected $fillable = ['affiliate_id', 'ip_address', 'user_agent', 'referer'];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}
