<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvShow extends Model
{
    use HasFactory;

    protected $fillable = [
        'tmdb_id',
        'name',
        'slug',
        'overview',
        'poster_path',
        'backdrop_path',
        'first_air_date',
        'vote_average',
        'vote_count',
        'is_trending',
    ];

    protected $casts = [
        'first_air_date' => 'date',
        'is_trending' => 'boolean',
    ];

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

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
}
