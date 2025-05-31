<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Movie;
use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $favoriteItems = $user->favorites()->with('favorable')->get();

        $movies = [];
        $tvShows = [];

        foreach ($favoriteItems as $item) {
            if ($item->favorable_type === 'App\\Models\\Movie') {
                $movies[] = $item->favorable;
            } elseif ($item->favorable_type === 'App\\Models\\TvShow') {
                $tvShows[] = $item->favorable;
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
            $favoriteItem = Favorite::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'favorable_id' => $movie->id,
                    'favorable_type' => 'App\\Models\\Movie',
                ]
            );

            return response()->json([
                'message' => 'Movie added to favorites',
                'favorite_item' => $favoriteItem,
            ]);
        } elseif ($type === 'tv') {
            $tvShow = TvShow::findOrFail($id);
            $favoriteItem = Favorite::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'favorable_id' => $tvShow->id,
                    'favorable_type' => 'App\\Models\\TvShow',
                ]
            );

            return response()->json([
                'message' => 'TV show added to favorites',
                'favorite_item' => $favoriteItem,
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

        $favorableType = $type === 'movie' ? 'App\\Models\\Movie' : 'App\\Models\\TvShow';

        $deleted = Favorite::where([
            'user_id' => $user->id,
            'favorable_id' => $id,
            'favorable_type' => $favorableType,
        ])->delete();

        if ($deleted) {
            return response()->json(['message' => 'Item removed from favorites']);
        } else {
            return response()->json(['message' => 'Item not found in favorites'], 404);
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

        $favorableType = $type === 'movie' ? 'App\\Models\\Movie' : 'App\\Models\\TvShow';

        $exists = Favorite::where([
            'user_id' => $user->id,
            'favorable_id' => $id,
            'favorable_type' => $favorableType,
        ])->exists();

        return response()->json(['in_favorites' => $exists]);
    }
}
