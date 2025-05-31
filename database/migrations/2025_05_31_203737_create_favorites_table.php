<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('favorable'); // Can be a movie or tv show
            $table->timestamps();

            $table->unique(['user_id', 'favorable_id', 'favorable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};
