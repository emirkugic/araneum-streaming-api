<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('watchlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('watchable'); // Can be a movie or tv show
            $table->timestamps();

            $table->unique(['user_id', 'watchable_id', 'watchable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('watchlist_items');
    }
};
