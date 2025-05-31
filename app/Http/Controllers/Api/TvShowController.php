<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\TvShow;
use App\Services\TMDbService;
use App\Services\VideoSourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TvShowController extends Controller
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
        $tvShows = TvShow::with('genres')->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($tvShows);
    }

    public function trending()
    {
        $tvShows = TvShow::with('genres')
            ->where('is_trending', true)
            ->orderBy('vote_average', 'desc')
            ->paginate(20);

        return response()->json($tvShows);
    }

    public function show($slug)
    {
        $tvShow = TvShow::with('genres')->where('slug', $slug)->firstOrFail();

        // Get additional details from TMDb
        $tmdbDetails = $this->tmdbService->getTvShow($tvShow->tmdb_id);

        $response = array_merge(
            $tvShow->toArray(),
            [
                'seasons' => $tmdbDetails['seasons'] ?? [],
                'credits' => $tmdbDetails['credits'] ?? null,
                'videos' => $tmdbDetails['videos'] ?? null,
                'similar' => $tmdbDetails['similar'] ?? null,
                'recommendations' => $tmdbDetails['recommendations'] ?? null,
            ]
        );

        return response()->json($response);
    }

    public function season($slug, $seasonNumber)
    {
        $tvShow = TvShow::where('slug', $slug)->firstOrFail();

        // Get season details from TMDb
        $seasonDetails = $this->tmdbService->getSeason($tvShow->tmdb_id, $seasonNumber);

        // Store episodes in database
        $episodes = [];
        foreach ($seasonDetails['episodes'] as $episodeData) {
            $episode = Episode::updateOrCreate(
                [
                    'tv_show_id' => $tvShow->id,
                    'season_number' => $seasonNumber,
                    'episode_number' => $episodeData['episode_number'],
                ],
                $this->tmdbService->formatEpisode($episodeData, $tvShow->id)
            );

            $episodes[] = $episode;
        }

        $response = [
            'tv_show' => $tvShow,
            'season' => $seasonDetails,
            'episodes' => $episodes,
        ];

        return response()->json($response);
    }

    public function episode($slug, $seasonNumber, $episodeNumber)
    {
        $tvShow = TvShow::where('slug', $slug)->firstOrFail();

        $episode = Episode::where([
            'tv_show_id' => $tvShow->id,
            'season_number' => $seasonNumber,
            'episode_number' => $episodeNumber,
        ])->firstOrFail();

        // Get episode details from TMDb
        $episodeDetails = $this->tmdbService->getEpisode($tvShow->tmdb_id, $seasonNumber, $episodeNumber);

        // Get video sources
        $sources = $this->videoSourceService->getTvShowSource($tvShow->tmdb_id, $seasonNumber, $episodeNumber);

        $response = [
            'tv_show' => $tvShow,
            'episode' => array_merge($episode->toArray(), [
                'sources' => $sources,
                'credits' => $episodeDetails['credits'] ?? null,
                'images' => $episodeDetails['images'] ?? null,
            ]),
        ];

        return response()->json($response);
    }

    public function refreshTrending()
    {
        // Update trending tv shows from TMDb
        $trendingTvShows = $this->tmdbService->getTrending('tv', 'week');

        // Reset trending flag
        TvShow::query()->update(['is_trending' => false]);

        foreach ($trendingTvShows['results'] as $tmdbTvShow) {
            $tvShow = TvShow::updateOrCreate(
                ['tmdb_id' => $tmdbTvShow['id']],
                array_merge(
                    $this->tmdbService->formatTvShow($tmdbTvShow),
                    ['is_trending' => true]
                )
            );
        }

        return response()->json(['message' => 'Trending TV shows updated']);
    }
}
