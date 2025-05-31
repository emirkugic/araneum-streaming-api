<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('tmdb_id')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->date('release_date')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->integer('vote_count')->nullable();
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_upcoming')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
};
