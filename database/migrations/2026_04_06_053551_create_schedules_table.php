<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            // relations
            $table->unsignedBigInteger('church_id');
            $table->unsignedBigInteger('user_id');

            // main fields
            $table->string('sched_title')->nullable();
            $table->text('sched_description')->nullable();
            $table->string('sched_type')->nullable();

            $table->date('sched_date');
            $table->time('sched_time')->nullable();

            $table->timestamps();

            // foreign keys (NO cascade - your preference)
            $table->foreign('church_id')
                ->references('id')
                ->on('churches');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
