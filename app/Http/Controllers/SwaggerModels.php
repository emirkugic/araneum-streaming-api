<?php

namespace App\Http\Controllers;

/**
 * @OA\Schema(
 *     schema="User",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Movie",
 *     required={"id", "title", "slug"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="tmdb_id", type="string", example="299534"),
 *     @OA\Property(property="title", type="string", example="Avengers: Endgame"),
 *     @OA\Property(property="slug", type="string", example="avengers-endgame-299534"),
 *     @OA\Property(property="overview", type="string", example="After the devastating events of Avengers: Infinity War..."),
 *     @OA\Property(property="poster_path", type="string", example="/or06FN3Dka5tukK1e9sl16pB3iy.jpg"),
 *     @OA\Property(property="backdrop_path", type="string", example="/7RyHsO4yDXtBv1zUU3mTpHeQ0d5.jpg"),
 *     @OA\Property(property="release_date", type="string", format="date", example="2019-04-26"),
 *     @OA\Property(property="vote_average", type="number", format="float", example=8.3),
 *     @OA\Property(property="vote_count", type="integer", example=12345),
 *     @OA\Property(property="is_trending", type="boolean", example=true),
 *     @OA\Property(property="is_upcoming", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="genres",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Genre")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="TvShow",
 *     required={"id", "name", "slug"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="tmdb_id", type="string", example="1399"),
 *     @OA\Property(property="name", type="string", example="Game of Thrones"),
 *     @OA\Property(property="slug", type="string", example="game-of-thrones-1399"),
 *     @OA\Property(property="overview", type="string", example="Seven noble families fight for control of the mythical land of Westeros..."),
 *     @OA\Property(property="poster_path", type="string", example="/u3bZgnGQ9T01sWNhyveQz0wH0Hl.jpg"),
 *     @OA\Property(property="backdrop_path", type="string", example="/suopoADq0k8YZr4dQXcU6pToj6s.jpg"),
 *     @OA\Property(property="first_air_date", type="string", format="date", example="2011-04-17"),
 *     @OA\Property(property="vote_average", type="number", format="float", example=8.4),
 *     @OA\Property(property="vote_count", type="integer", example=11345),
 *     @OA\Property(property="is_trending", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="genres",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Genre")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Genre",
 *     required={"id", "name", "type"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="tmdb_id", type="string", example="28"),
 *     @OA\Property(property="name", type="string", example="Action"),
 *     @OA\Property(property="type", type="string", example="movie"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Episode",
 *     required={"id", "tv_show_id", "name", "season_number", "episode_number"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="tv_show_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="tmdb_id", type="string", example="123456"),
 *     @OA\Property(property="name", type="string", example="Winter Is Coming"),
 *     @OA\Property(property="overview", type="string", example="Jon Arryn, the Hand of the King, is dead..."),
 *     @OA\Property(property="still_path", type="string", example="/wrGWeW4WKxnaeA8sxJb2T9O6ryo.jpg"),
 *     @OA\Property(property="season_number", type="integer", example=1),
 *     @OA\Property(property="episode_number", type="integer", example=1),
 *     @OA\Property(property="air_date", type="string", format="date", example="2011-04-17"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class SwaggerModels extends Controller
{
    // This class doesn't need any methods, it's just for the annotations
}
