<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Services\TMDbService;
use App\Services\VideoSourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    protected $tmdbService;
    protected $videoSourceService;

    public function __construct(TMDbService $tmdbService, VideoSourceService $videoSourceService)
    {
        $this->tmdbService = $tmdbService;
        $this->videoSourceService = $videoSourceService;
    }

    public function index()
    {
        $movies = Movie::with('genres')->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($movies);
    }

    public function trending()
    {
        $movies = Movie::with('genres')
            ->where('is_trending', true)
            ->orderBy('vote_average', 'desc')
            ->paginate(20);

        return response()->json($movies);
    }

    public function upcoming()
    {
        $movies = Movie::with('genres')
            ->where('is_upcoming', true)
            ->orderBy('release_date', 'asc')
            ->paginate(20);

        return response()->json($movies);
    }

    public function show($slug)
    {
        $movie = Movie::with('genres')->where('slug', $slug)->firstOrFail();

        // Get additional details from TMDb
        $tmdbDetails = $this->tmdbService->getMovie($movie->tmdb_id);

        // Get video sources
        $sources = $this->videoSourceService->getMovieSource($movie->tmdb_id);

        $response = array_merge(
            $movie->toArray(),
            [
                'sources' => $sources,
                'credits' => $tmdbDetails['credits'] ?? null,
                'videos' => $tmdbDetails['videos'] ?? null,
                'similar' => $tmdbDetails['similar'] ?? null,
                'recommendations' => $tmdbDetails['recommendations'] ?? null,
            ]
        );

        return response()->json($response);
    }

    public function refreshTrending()
    {
        // Update trending movies from TMDb
        $trendingMovies = $this->tmdbService->getTrending('movie', 'week');

        // Reset trending flag
        Movie::query()->update(['is_trending' => false]);

        foreach ($trendingMovies['results'] as $tmdbMovie) {
            $movie = Movie::updateOrCreate(
                ['tmdb_id' => $tmdbMovie['id']],
                array_merge(
                    $this->tmdbService->formatMovie($tmdbMovie),
                    ['is_trending' => true]
                )
            );
        }

        return response()->json(['message' => 'Trending movies updated']);
    }

    public function refreshUpcoming()
    {
        // Update upcoming movies from TMDb
        $upcomingMovies = $this->tmdbService->getUpcoming();

        // Reset upcoming flag
        Movie::query()->update(['is_upcoming' => false]);

        foreach ($upcomingMovies['results'] as $tmdbMovie) {
            $movie = Movie::updateOrCreate(
                ['tmdb_id' => $tmdbMovie['id']],
                array_merge(
                    $this->tmdbService->formatMovie($tmdbMovie),
                    ['is_upcoming' => true]
                )
            );
        }

        return response()->json(['message' => 'Upcoming movies updated']);
    }
}
