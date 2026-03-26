<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('church_name')->nullable()->after('name');
            $table->string('church_abbr')->nullable()->after('church_name');
            $table->string('type')->default('member')->after('church_abbr');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['church_name', 'church_abbr', 'type']);
        });
    }
};
