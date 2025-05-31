<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tv_show_id')->constrained('tv_shows')->onDelete('cascade');
            $table->string('tmdb_id')->unique();
            $table->string('name');
            $table->text('overview')->nullable();
            $table->string('still_path')->nullable();
            $table->integer('season_number');
            $table->integer('episode_number');
            $table->date('air_date')->nullable();
            $table->timestamps();

            $table->unique(['tv_show_id', 'season_number', 'episode_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('episodes');
    }
};
