<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore([
                ['name' => 'Admin', 'description' => '...', 'code' => 'Admin', 'created_by' => -1],
                ['name' => 'User', 'description' => '...', 'code' => 'User', 'created_by' => -1],
                ['name' => 'Guest', 'description' => '...', 'code' => 'Guest', 'created_by' => -1],
        ]);
    }
}
