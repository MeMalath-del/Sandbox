<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'query',
        'results_count',
        'filters',
    ];

    protected $casts = [
        'filters' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record($query, $resultsCount = 0, $filters = [])
    {
        return static::create([
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'query' => $query,
            'results_count' => $resultsCount,
            'filters' => $filters,
        ]);
    }

    public static function getPopularSearches($limit = 10)
    {
        return static::select('query')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit($limit)
            ->pluck('query');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
