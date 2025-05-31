<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\TvShow;
use App\Models\WatchlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $watchlistItems = $user->watchlist()->with('watchable')->get();

        $movies = [];
        $tvShows = [];

        foreach ($watchlistItems as $item) {
            if ($item->watchable_type === 'App\\Models\\Movie') {
                $movies[] = $item->watchable;
            } elseif ($item->watchable_type === 'App\\Models\\TvShow') {
                $tvShows[] = $item->watchable;
            }
        }

        return response()->json([
            'movies' => $movies,
            'tv_shows' => $tvShows,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'type' => 'required|in:movie,tv',
            'id' => 'required|integer',
        ]);

        $user = Auth::user();
        $type = $request->input('type');
        $id = $request->input('id');

        if ($type === 'movie') {
            $movie = Movie::findOrFail($id);
            $watchlistItem = WatchlistItem::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'watchable_id' => $movie->id,
                    'watchable_type' => 'App\\Models\\Movie',
                ]
            );

            return response()->json([
                'message' => 'Movie added to watchlist',
                'watchlist_item' => $watchlistItem,
            ]);
        } elseif ($type === 'tv') {
            $tvShow = TvShow::findOrFail($id);
            $watchlistItem = WatchlistItem::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'watchable_id' => $tvShow->id,
                    'watchable_type' => 'App\\Models\\TvShow',
                ]
            );

            return response()->json([
                'message' => 'TV show added to watchlist',
                'watchlist_item' => $watchlistItem,
            ]);
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'type' => 'required|in:movie,tv',
            'id' => 'required|integer',
        ]);

        $user = Auth::user();
        $type = $request->input('type');
        $id = $request->input('id');

        $watchableType = $type === 'movie' ? 'App\\Models\\Movie' : 'App\\Models\\TvShow';

        $deleted = WatchlistItem::where([
            'user_id' => $user->id,
            'watchable_id' => $id,
            'watchable_type' => $watchableType,
        ])->delete();

        if ($deleted) {
            return response()->json(['message' => 'Item removed from watchlist']);
        } else {
            return response()->json(['message' => 'Item not found in watchlist'], 404);
        }
    }

    public function check(Request $request)
    {
        $request->validate([
            'type' => 'required|in:movie,tv',
            'id' => 'required|integer',
        ]);

        $user = Auth::user();
        $type = $request->input('type');
        $id = $request->input('id');

        $watchableType = $type === 'movie' ? 'App\\Models\\Movie' : 'App\\Models\\TvShow';

        $exists = WatchlistItem::where([
            'user_id' => $user->id,
            'watchable_id' => $id,
            'watchable_type' => $watchableType,
        ])->exists();

        return response()->json(['in_watchlist' => $exists]);
    }
}
