<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('church_user', function (Blueprint $table) {
            $table->boolean('is_approved')->default(0)->after('church_id');
            $table->string('type')->default('member')->after('is_approved');
        });
    }

    public function down(): void
    {
        Schema::table('church_user', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'type']);
        });
    }
};
