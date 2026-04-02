<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSelectSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role_select')->upsert(
            [
                [
                    'role_name' => 'Worship Leader',
                    'role_slug' => 'worship_leader',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_name' => 'Vocalist',
                    'role_slug' => 'vocalist',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_name' => 'Electric Guitarist',
                    'role_slug' => 'electric_guitarist',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_name' => 'Accoustic Guitarist',
                    'role_slug' => 'accoustic_guitarist',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_name' => 'Bassist',
                    'role_slug' => 'bassist',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_name' => 'Drummer',
                    'role_slug' => 'drummer',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_name' => 'Keyboardist',
                    'role_slug' => 'keyboardist',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_name' => 'Sound Engineer',
                    'role_slug' => 'sound_engineer',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_name' => 'Multimedia Operator',
                    'role_slug' => 'multimedia_operator',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ],
            ['role_slug'], // 🔑 unique key (conflict checker)
            ['role_name', 'updated_at'] // 🔄 columns to update
        );
    }
}

// https://chatgpt.com/g/g-p-69c4a14703c88191b5ff915440a74d2c-project-lead/c/69ce824c-d4c0-8322-96f7-b37802897835