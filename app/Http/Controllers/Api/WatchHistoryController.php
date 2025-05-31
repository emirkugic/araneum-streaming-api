<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Movie;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $historyItems = $user->watchHistory()
            ->with('watchable')
            ->orderBy('updated_at', 'desc')
            ->get();

        $movies = [];
        $episodes = [];

        foreach ($historyItems as $item) {
            if ($item->watchable_type === 'App\\Models\\Movie') {
                $movies[] = array_merge(
                    $item->watchable->toArray(),
                    [
                        'progress' => $item->progress,
                        'completed' => $item->completed,
                        'last_watched' => $item->updated_at,
                    ]
                );
            } elseif ($item->watchable_type === 'App\\Models\\Episode') {
                $episode = $item->watchable;
                $tvShow = $episode->tvShow;

                $episodes[] = array_merge(
                    $episode->toArray(),
                    [
                        'tv_show' => $tvShow,
                        'progress' => $item->progress,
                        'completed' => $item->completed,
                        'last_watched' => $item->updated_at,
                    ]
                );
            }
        }

        return response()->json([
            'movies' => $movies,
            'episodes' => $episodes,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'type' => 'required|in:movie,episode',
            'id' => 'required|integer',
            'progress' => 'required|integer|min:0',
            'completed' => 'required|boolean',
        ]);

        $user = Auth::user();
        $type = $request->input('type');
        $id = $request->input('id');
        $progress = $request->input('progress');
        $completed = $request->input('completed');

        if ($type === 'movie') {
            $movie = Movie::findOrFail($id);
            $historyItem = WatchHistory::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'watchable_id' => $movie->id,
                    'watchable_type' => 'App\\Models\\Movie',
                ],
                [
                    'progress' => $progress,
                    'completed' => $completed,
                ]
            );

            return response()->json([
                'message' => 'Movie watch history updated',
                'history_item' => $historyItem,
            ]);
        } elseif ($type === 'episode') {
            $episode = Episode::findOrFail($id);
            $historyItem = WatchHistory::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'watchable_id' => $episode->id,
                    'watchable_type' => 'App\\Models\\Episode',
                ],
                [
                    'progress' => $progress,
                    'completed' => $completed,
                ]
            );

            return response()->json([
                'message' => 'Episode watch history updated',
                'history_item' => $historyItem,
            ]);
        }
    }

    public function get(Request $request)
    {
        $request->validate([
            'type' => 'required|in:movie,episode',
            'id' => 'required|integer',
        ]);

        $user = Auth::user();
        $type = $request->input('type');
        $id = $request->input('id');

        $watchableType = $type === 'movie' ? 'App\\Models\\Movie' : 'App\\Models\\Episode';

        $historyItem = WatchHistory::where([
            'user_id' => $user->id,
            'watchable_id' => $id,
            'watchable_type' => $watchableType,
        ])->first();

        if ($historyItem) {
            return response()->json([
                'progress' => $historyItem->progress,
                'completed' => $historyItem->completed,
                'last_watched' => $historyItem->updated_at,
            ]);
        } else {
            return response()->json([
                'progress' => 0,
                'completed' => false,
                'last_watched' => null,
            ]);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'type' => 'required|in:movie,episode',
            'id' => 'required|integer',
        ]);

        $user = Auth::user();
        $type = $request->input('type');
        $id = $request->input('id');

        $watchableType = $type === 'movie' ? 'App\\Models\\Movie' : 'App\\Models\\Episode';

        $deleted = WatchHistory::where([
            'user_id' => $user->id,
            'watchable_id' => $id,
            'watchable_type' => $watchableType,
        ])->delete();

        if ($deleted) {
            return response()->json(['message' => 'History item deleted']);
        } else {
            return response()->json(['message' => 'History item not found'], 404);
        }
    }
}
