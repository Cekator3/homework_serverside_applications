<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([
            'id' => -1,
            'name' => 'admin',
            'email' => 'notexists@mail.ru',
            'password' => Hash::make('1'),
            'birthday' => '2024-09-29'
        ]);
    }
}
