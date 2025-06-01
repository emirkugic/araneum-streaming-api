<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Swagger documentation routes
Route::get('/api/documentation', function () {
    return view('swagger.index');
});

// Include API routes
require __DIR__ . '/api.php';
