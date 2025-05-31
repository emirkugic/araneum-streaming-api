<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\TvShow;
use App\Services\TMDbService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    protected $tmdbService;

    public function __construct(TMDbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([
                'movies' => [],
                'tv_shows' => [],
            ]);
        }

        // Search locally first
        $movies = Movie::where('title', 'like', "%{$query}%")
            ->with('genres')
            ->limit(10)
            ->get();

        $tvShows = TvShow::where('name', 'like', "%{$query}%")
            ->with('genres')
            ->limit(10)
            ->get();

        // If not enough results, search TMDb
        if ($movies->count() < 5 || $tvShows->count() < 5) {
            $tmdbResults = $this->tmdbService->search($query);

            foreach ($tmdbResults['results'] as $result) {
                if ($result['media_type'] === 'movie' && $movies->count() < 10) {
                    $movie = Movie::updateOrCreate(
                        ['tmdb_id' => $result['id']],
                        $this->tmdbService->formatMovie($result)
                    );

                    if (!$movies->contains('id', $movie->id)) {
                        $movies->push($movie);
                    }
                } elseif ($result['media_type'] === 'tv' && $tvShows->count() < 10) {
                    $tvShow = TvShow::updateOrCreate(
                        ['tmdb_id' => $result['id']],
                        $this->tmdbService->formatTvShow($result)
                    );

                    if (!$tvShows->contains('id', $tvShow->id)) {
                        $tvShows->push($tvShow);
                    }
                }
            }
        }

        return response()->json([
            'movies' => $movies,
            'tv_shows' => $tvShows,
        ]);
    }
}
