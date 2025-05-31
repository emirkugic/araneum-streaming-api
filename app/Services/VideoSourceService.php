<?php

namespace App\Services;

class VideoSourceService
{
    public function getMovieSource($tmdbId)
    {
        // In a real app, you'd want to add error handling and fallbacks
        return [
            'vidsrc' => "https://vidsrc.to/embed/movie/{$tmdbId}",
            '2embed' => "https://2embed.to/embed/movie/{$tmdbId}",
        ];
    }

    public function getTvShowSource($tmdbId, $season, $episode)
    {
        return [
            'vidsrc' => "https://vidsrc.to/embed/tv/{$tmdbId}/{$season}/{$episode}",
            '2embed' => "https://2embed.to/embed/series/{$tmdbId}/{$season}/{$episode}",
        ];
    }
}
