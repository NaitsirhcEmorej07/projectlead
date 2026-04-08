<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetData extends Command
{
    protected $signature = 'reset:data';
    protected $description = 'Truncate selected tables';

    public function handle()
    {
        $this->info('Resetting data...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'users',

            'churches',
            'church_user',

            'role_select',
            'role_user',

            'song_select',
            'song_user',

            'social_user',
            
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
            $this->info("Truncated: $table");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Done! All selected tables are reset.');
    }
}