<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('worship_devotion_images', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('worship_devotion_id');
            $table->unsignedBigInteger('user_id');     // 🔥 ADD
            $table->unsignedBigInteger('church_id');   // 🔥 ADD

            $table->string('image_path');

            $table->timestamps();

            // FOREIGN KEYS
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

    public function down()
    {
        Schema::dropIfExists('worship_devotion_images');
    }
};
