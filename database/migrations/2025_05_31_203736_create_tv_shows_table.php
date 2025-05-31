<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tv_shows', function (Blueprint $table) {
            $table->id();
            $table->string('tmdb_id')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->date('first_air_date')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->integer('vote_count')->nullable();
            $table->boolean('is_trending')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tv_shows');
    }
};
