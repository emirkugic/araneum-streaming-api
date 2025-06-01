<?php

namespace App\Services;

class VideoSourceService
{
    /**
     * Get video sources for a movie
     * 
     * @param string $tmdbId The TMDB ID of the movie
     * @return array An array of video sources
     */
    public function getMovieSource($tmdbId)
    {
        // Return sources according to the documented format
        return [
            'vidsrc' => "https://vidsrc.xyz/embed/movie/{$tmdbId}",
            '2embed' => "https://www.2embed.cc/embed/{$tmdbId}"
        ];
    }

    /**
     * Get video sources for a TV show episode
     * 
     * @param string $tmdbId The TMDB ID of the TV show
     * @param int $season The season number
     * @param int $episode The episode number
     * @return array An array of video sources
     */
    public function getTvShowSource($tmdbId, $season, $episode)
    {
        // Return sources according to the documented format
        return [
            'vidsrc' => "https://vidsrc.xyz/embed/tv/{$tmdbId}/{$season}-{$episode}",
            '2embed' => "https://www.2embed.cc/embedtv/{$tmdbId}&s={$season}&e={$episode}"
        ];
    }
}
