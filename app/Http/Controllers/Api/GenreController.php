<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\TvShow;
use App\Services\TMDbService;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    protected $tmdbService;

    public function __construct(TMDbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index($type = null)
    {
        $query = Genre::query();

        if ($type) {
            $query->where('type', $type);
        }

        $genres = $query->orderBy('name')->get();

        return response()->json($genres);
    }

    public function show($id)
    {
        $genre = Genre::findOrFail($id);

        $movies = [];
        $tvShows = [];

        if ($genre->type === 'movie' || $genre->type === 'all') {
            $movies = $genre->movies()->with('genres')->paginate(10);
        }

        if ($genre->type === 'tv' || $genre->type === 'all') {
            $tvShows = $genre->tvShows()->with('genres')->paginate(10);
        }

        return response()->json([
            'genre' => $genre,
            'movies' => $movies,
            'tv_shows' => $tvShows,
        ]);
    }

    public function refreshGenres()
    {
        // Get movie genres
        $movieGenres = $this->tmdbService->getGenres('movie');
        foreach ($movieGenres['genres'] as $genreData) {
            Genre::updateOrCreate(
                [
                    'tmdb_id' => $genreData['id'],
                    'type' => 'movie',
                ],
                [
                    'name' => $genreData['name'],
                ]
            );
        }

        // Get TV genres
        $tvGenres = $this->tmdbService->getGenres('tv');
        foreach ($tvGenres['genres'] as $genreData) {
            Genre::updateOrCreate(
                [
                    'tmdb_id' => $genreData['id'],
                    'type' => 'tv',
                ],
                [
                    'name' => $genreData['name'],
                ]
            );
        }

        return response()->json(['message' => 'Genres updated successfully']);
    }
}
