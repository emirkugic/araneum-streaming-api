<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TMDbService
{
    protected $baseUrl = 'https://api.themoviedb.org/3';
    protected $apiKey;
    protected $imageBaseUrl = 'https://image.tmdb.org/t/p/';

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
    }

    public function getTrending($type = 'all', $timeWindow = 'week')
    {
        $response = Http::get("{$this->baseUrl}/trending/{$type}/{$timeWindow}", [
            'api_key' => $this->apiKey,
        ]);

        return $response->json();
    }

    public function getUpcoming()
    {
        $response = Http::get("{$this->baseUrl}/movie/upcoming", [
            'api_key' => $this->apiKey,
        ]);

        return $response->json();
    }

    public function getMovie($id)
    {
        $response = Http::get("{$this->baseUrl}/movie/{$id}", [
            'api_key' => $this->apiKey,
            'append_to_response' => 'credits,videos,similar,recommendations',
        ]);

        return $response->json();
    }

    public function getTvShow($id)
    {
        $response = Http::get("{$this->baseUrl}/tv/{$id}", [
            'api_key' => $this->apiKey,
            'append_to_response' => 'credits,videos,similar,recommendations',
        ]);

        return $response->json();
    }

    public function getSeason($tvId, $seasonNumber)
    {
        $response = Http::get("{$this->baseUrl}/tv/{$tvId}/season/{$seasonNumber}", [
            'api_key' => $this->apiKey,
        ]);

        return $response->json();
    }

    public function getEpisode($tvId, $seasonNumber, $episodeNumber)
    {
        $response = Http::get("{$this->baseUrl}/tv/{$tvId}/season/{$seasonNumber}/episode/{$episodeNumber}", [
            'api_key' => $this->apiKey,
        ]);

        return $response->json();
    }

    public function search($query, $type = 'multi')
    {
        $response = Http::get("{$this->baseUrl}/search/{$type}", [
            'api_key' => $this->apiKey,
            'query' => $query,
        ]);

        return $response->json();
    }

    public function getGenres($type = 'movie')
    {
        $response = Http::get("{$this->baseUrl}/genre/{$type}/list", [
            'api_key' => $this->apiKey,
        ]);

        return $response->json();
    }

    public function getPosterUrl($path, $size = 'w500')
    {
        if (empty($path)) {
            return null;
        }
        return $this->imageBaseUrl . $size . $path;
    }

    public function getBackdropUrl($path, $size = 'original')
    {
        if (empty($path)) {
            return null;
        }
        return $this->imageBaseUrl . $size . $path;
    }

    public function formatMovie($movie)
    {
        return [
            'tmdb_id' => $movie['id'],
            'title' => $movie['title'],
            'slug' => Str::slug($movie['title']) . '-' . $movie['id'],
            'overview' => $movie['overview'] ?? null,
            'poster_path' => $movie['poster_path'] ?? null,
            'backdrop_path' => $movie['backdrop_path'] ?? null,
            'release_date' => $movie['release_date'] ?? null,
            'vote_average' => $movie['vote_average'] ?? null,
            'vote_count' => $movie['vote_count'] ?? null,
        ];
    }

    public function formatTvShow($tvShow)
    {
        return [
            'tmdb_id' => $tvShow['id'],
            'name' => $tvShow['name'],
            'slug' => Str::slug($tvShow['name']) . '-' . $tvShow['id'],
            'overview' => $tvShow['overview'] ?? null,
            'poster_path' => $tvShow['poster_path'] ?? null,
            'backdrop_path' => $tvShow['backdrop_path'] ?? null,
            'first_air_date' => $tvShow['first_air_date'] ?? null,
            'vote_average' => $tvShow['vote_average'] ?? null,
            'vote_count' => $tvShow['vote_count'] ?? null,
        ];
    }

    public function formatEpisode($episode, $tvShowId)
    {
        return [
            'tv_show_id' => $tvShowId,
            'tmdb_id' => $episode['id'],
            'name' => $episode['name'],
            'overview' => $episode['overview'] ?? null,
            'still_path' => $episode['still_path'] ?? null,
            'season_number' => $episode['season_number'],
            'episode_number' => $episode['episode_number'],
            'air_date' => $episode['air_date'] ?? null,
        ];
    }
}
