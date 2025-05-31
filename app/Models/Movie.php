<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'tmdb_id',
        'title',
        'slug',
        'overview',
        'poster_path',
        'backdrop_path',
        'release_date',
        'vote_average',
        'vote_count',
        'is_trending',
        'is_upcoming',
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_trending' => 'boolean',
        'is_upcoming' => 'boolean',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function watchlistItems()
    {
        return $this->morphMany(WatchlistItem::class, 'watchable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    public function watchHistory()
    {
        return $this->morphMany(WatchHistory::class, 'watchable');
    }
}
