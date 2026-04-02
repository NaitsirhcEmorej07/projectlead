<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {
        Schema::table('song_select', function (Blueprint $table) {
            $table->string('original_key')->nullable()->after('song_reference');
        });
    }

    public function down(): void
    {
        Schema::table('song_select', function (Blueprint $table) {
            $table->dropColumn('original_key');
        });
    }
};