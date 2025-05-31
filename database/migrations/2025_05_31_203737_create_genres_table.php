<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('tmdb_id')->unique();
            $table->string('name');
            $table->string('type'); // 'movie' or 'tv'
            $table->timestamps();
        });

        Schema::create('genre_movie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->unique(['genre_id', 'movie_id']);
        });

        Schema::create('genre_tv_show', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            $table->foreignId('tv_show_id')->constrained('tv_shows')->onDelete('cascade');
            $table->unique(['genre_id', 'tv_show_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('genre_tv_show');
        Schema::dropIfExists('genre_movie');
        Schema::dropIfExists('genres');
    }
};
