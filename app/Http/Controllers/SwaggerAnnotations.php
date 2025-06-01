<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Streaming API",
 *     version="1.0.0",
 *     description="API documentation for the Streaming application",
 *     @OA\Contact(
 *         email="your-email@example.com",
 *         name="Your Name"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\PathItem(path="/register")
 * @OA\PathItem(path="/login")
 * @OA\PathItem(path="/logout")
 * @OA\PathItem(path="/user")
 * @OA\PathItem(path="/movies")
 * @OA\PathItem(path="/movies/trending")
 * @OA\PathItem(path="/movies/upcoming")
 * @OA\PathItem(path="/movies/{slug}")
 * @OA\PathItem(path="/tv-shows")
 * @OA\PathItem(path="/tv-shows/trending")
 * @OA\PathItem(path="/tv-shows/{slug}")
 * @OA\PathItem(path="/tv-shows/{slug}/season/{seasonNumber}")
 * @OA\PathItem(path="/tv-shows/{slug}/season/{seasonNumber}/episode/{episodeNumber}")
 * @OA\PathItem(path="/genres")
 * @OA\PathItem(path="/genres/{type}")
 * @OA\PathItem(path="/genre/{id}")
 * @OA\PathItem(path="/search")
 * @OA\PathItem(path="/watchlist")
 * @OA\PathItem(path="/watchlist/add")
 * @OA\PathItem(path="/watchlist/remove")
 * @OA\PathItem(path="/watchlist/check")
 * @OA\PathItem(path="/favorites")
 * @OA\PathItem(path="/favorites/add")
 * @OA\PathItem(path="/favorites/remove")
 * @OA\PathItem(path="/favorites/check")
 * @OA\PathItem(path="/watch-history")
 * @OA\PathItem(path="/watch-history/update")
 * @OA\PathItem(path="/watch-history/get")
 * @OA\PathItem(path="/watch-history/delete")
 * @OA\PathItem(path="/admin/movies/refresh-trending")
 * @OA\PathItem(path="/admin/movies/refresh-upcoming")
 * @OA\PathItem(path="/admin/tv-shows/refresh-trending")
 * @OA\PathItem(path="/admin/genres/refresh")
 */
class SwaggerAnnotations extends Controller
{
    // This class doesn't need any methods, it's just for the annotations
}
