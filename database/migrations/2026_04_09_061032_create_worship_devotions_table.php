<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worship_devotions', function (Blueprint $table) {
            $table->id();

            // RELATION FIELDS
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('church_id');

            // CONTENT
            $table->text('content');

            // OPTIONAL (for future features)
            $table->integer('likes_count')->default(0);

            $table->timestamps();

            // FOREIGN KEYS (NO CASCADE)
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('church_id')->references('id')->on('churches');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worship_devotions');
    }
};
