<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn(['church_name', 'church_abbr']);

            $table->foreignId('church_id')
                ->nullable()
                ->constrained('churches');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['church_id']);
            $table->dropColumn('church_id');

            $table->string('church_name')->nullable();
            $table->string('church_abbr')->nullable();
        });
    }
};
