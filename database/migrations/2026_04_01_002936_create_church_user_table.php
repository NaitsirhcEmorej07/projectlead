<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('church_user', function (Blueprint $table) {
            $table->id();

            // Foreign keys (NO cascade)
            $table->foreignId('user_id')->constrained();
            $table->foreignId('church_id')->constrained();

            // Prevent duplicates (recommended)
            $table->unique(['user_id', 'church_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('church_user');
    }
};
