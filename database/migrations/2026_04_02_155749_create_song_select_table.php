<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('song_select', function (Blueprint $table) {
            $table->id();

            // Foreign key
            $table->unsignedBigInteger('church_id');

            $table->string('song_title');
            $table->string('song_by')->nullable();
            $table->string('song_reference')->nullable();

            $table->timestamps();

            // Foreign constraint (no cascade delete as you prefer)
            $table->foreign('church_id')
                ->references('id')
                ->on('churches');
        });
    }

    public function down(): void
    {
        Schema::table('song_select', function (Blueprint $table) {
            $table->dropForeign(['church_id']);
        });

        Schema::dropIfExists('song_select');
    }
};
