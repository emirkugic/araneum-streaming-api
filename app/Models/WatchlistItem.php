<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'watchable_id',
        'watchable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function watchable()
    {
        return $this->morphTo();
    }
}
