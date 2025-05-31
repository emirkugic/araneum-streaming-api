<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the watchlist items for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function watchlist(): HasMany
    {
        return $this->hasMany(WatchlistItem::class);
    }

    /**
     * Get the favorite items for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the watch history items for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function watchHistory(): HasMany
    {
        return $this->hasMany(WatchHistory::class);
    }
}
