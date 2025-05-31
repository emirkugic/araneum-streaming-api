<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'tmdb_id',
        'name',
        'type',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }

    public function tvShows()
    {
        return $this->belongsToMany(TvShow::class);
    }
}
