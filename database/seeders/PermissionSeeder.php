<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = ['user', 'role', 'permission', 'log-request', 'application-usage-report'];
        $entities_prefixes = ['get-list-', 'read-', 'create-', 'update-', 'delete-', 'restore-', 'get-story-'];
        $values_to_insert = [];
        foreach ($entities as $entity)
        {
            foreach ($entities_prefixes as $prefix)
            {
                $values_to_insert []= [
                    'name' => "$prefix$entity",
                    'description' => '...',
                    'code' => "$prefix$entity",
                    'created_by' => -1
                ];
            }
        }
        DB::table('permissions')->insertOrIgnore($values_to_insert);
    }
}
