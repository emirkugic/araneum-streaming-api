<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\TvShowController;
use App\Http\Controllers\Api\WatchHistoryController;
use App\Http\Controllers\Api\WatchlistController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Movie routes
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/trending', [MovieController::class, 'trending']);
Route::get('/movies/upcoming', [MovieController::class, 'upcoming']);
Route::get('/movies/{slug}', [MovieController::class, 'show']);

// TV Show routes
Route::get('/tv-shows', [TvShowController::class, 'index']);
Route::get('/tv-shows/trending', [TvShowController::class, 'trending']);
Route::get('/tv-shows/{slug}', [TvShowController::class, 'show']);
Route::get('/tv-shows/{slug}/season/{seasonNumber}', [TvShowController::class, 'season']);
Route::get('/tv-shows/{slug}/season/{seasonNumber}/episode/{episodeNumber}', [TvShowController::class, 'episode']);

// Genre routes
Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{type}', [GenreController::class, 'index']);
Route::get('/genre/{id}', [GenreController::class, 'show']);

// Search routes
Route::get('/search', [SearchController::class, 'search']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Watchlist routes
    Route::get('/watchlist', [WatchlistController::class, 'index']);
    Route::post('/watchlist/add', [WatchlistController::class, 'add']);
    Route::post('/watchlist/remove', [WatchlistController::class, 'remove']);
    Route::post('/watchlist/check', [WatchlistController::class, 'check']);

    // Favorites routes
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/add', [FavoriteController::class, 'add']);
    Route::post('/favorites/remove', [FavoriteController::class, 'remove']);
    Route::post('/favorites/check', [FavoriteController::class, 'check']);

    // Watch history routes
    Route::get('/watch-history', [WatchHistoryController::class, 'index']);
    Route::post('/watch-history/update', [WatchHistoryController::class, 'update']);
    Route::post('/watch-history/get', [WatchHistoryController::class, 'get']);
    Route::post('/watch-history/delete', [WatchHistoryController::class, 'delete']);

    // Admin routes (you might want to add additional middleware for these)
    Route::post('/admin/movies/refresh-trending', [MovieController::class, 'refreshTrending']);
    Route::post('/admin/movies/refresh-upcoming', [MovieController::class, 'refreshUpcoming']);
    Route::post('/admin/tv-shows/refresh-trending', [TvShowController::class, 'refreshTrending']);
    Route::post('/admin/genres/refresh', [GenreController::class, 'refreshGenres']);
});
