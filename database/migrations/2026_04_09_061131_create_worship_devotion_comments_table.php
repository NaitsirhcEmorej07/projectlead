<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worship_devotion_comments', function (Blueprint $table) {
            $table->id();

            // RELATIONS
            $table->unsignedBigInteger('worship_devotion_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('church_id');

            // 👇 THIS IS THE IMPORTANT PART
            $table->unsignedBigInteger('parent_id')->nullable();


            // CONTENT
            $table->text('comment');

            $table->timestamps();

            // FOREIGN KEYS (NO CASCADE)
            $table->foreign('worship_devotion_id')
                ->references('id')
                ->on('worship_devotions');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('church_id')
                ->references('id')
                ->on('churches');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worship_devotion_comments');
    }
};
