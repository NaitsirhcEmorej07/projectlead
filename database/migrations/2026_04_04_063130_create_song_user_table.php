<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('song_user', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('church_id');

            $table->string('song_title');
            $table->string('song_by')->nullable();
            $table->string('song_reference')->nullable();
            $table->string('user_key')->nullable();

            $table->timestamps();

            // 🔐 Foreign Keys (no cascade as you prefer)
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('church_id')->references('id')->on('churches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('song_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['church_id']);
        });

        Schema::dropIfExists('song_user');
    }
};
