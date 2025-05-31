<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'tv_show_id',
        'tmdb_id',
        'name',
        'overview',
        'still_path',
        'season_number',
        'episode_number',
        'air_date',
    ];

    protected $casts = [
        'air_date' => 'date',
    ];

    public function tvShow()
    {
        return $this->belongsTo(TvShow::class);
    }

    public function watchHistory()
    {
        return $this->morphMany(WatchHistory::class, 'watchable');
    }
}
