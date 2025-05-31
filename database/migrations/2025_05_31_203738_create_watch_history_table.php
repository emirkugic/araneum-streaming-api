<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('watch_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('watchable'); // Can be a movie or episode
            $table->integer('progress')->default(0); // Store progress in seconds
            $table->boolean('completed')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'watchable_id', 'watchable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('watch_history');
    }
};
