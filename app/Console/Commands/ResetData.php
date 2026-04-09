<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetData extends Command
{
    protected $signature = 'reset:data';
    protected $description = 'Truncate selected tables';

    public function handle()
    {
        $this->info('Resetting data...');

        // ✅ Disable FK safely (works for PostgreSQL & MySQL)
        Schema::disableForeignKeyConstraints();

        $tables = [
            'users',

            'churches',
            'church_user',

            'role_select',
            'role_user',

            'song_select',
            'song_user',

            'social_user',

            'worship_devotions',
            'worship_devotion_comments',
            'worship_devotion_likes',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
            $this->info("Truncated: $table");
        }

        // ✅ Enable back
        Schema::enableForeignKeyConstraints();

        $this->info('Done! All selected tables are reset.');
    }
}