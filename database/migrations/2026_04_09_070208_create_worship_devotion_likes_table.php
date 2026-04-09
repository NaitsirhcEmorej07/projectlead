<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('worship_devotion_likes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('worship_devotion_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('church_id');

            $table->timestamps();

            // prevent duplicate likes
            $table->unique(['worship_devotion_id', 'user_id']);

            $table->foreign('worship_devotion_id')->references('id')->on('worship_devotions');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('church_id')->references('id')->on('churches');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worship_devotion_likes');
    }
};
