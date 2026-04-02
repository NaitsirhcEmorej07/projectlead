<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSelectSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSelectSeeder::class);
    }
}
