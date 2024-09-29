<?php

namespace Database\Seeders;

use PDO;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    function get_role_mappings() : array
    {
        $query = DB::connection()->getPdo()->query('select name, id from roles');
        return $query->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    function get_permission_mappings() : array
    {
        $query = DB::connection()->getPdo()->query('select name, id from permissions');
        return $query->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = $this->get_role_mappings();
        $permissions = $this->get_permission_mappings();
        $entities_prefixes = ['get-list-', 'read-', 'create-', 'update-', 'delete-', 'restore-'];

        $roles_permissions = [
            'Guest' => [],
            'User' => [],
            'Admin' => []
        ];

        $roles_permissions['Guest'] []= 'get-list-user';

        foreach ($entities_prefixes as $prefix)
            $roles_permissions['User'] []= "{$prefix}user";

        foreach ($permissions as $name => $id)
            $roles_permissions['Admin'] []= $name;

        $values_to_insert = [];
        foreach ($roles_permissions as $role_name => $permissions_names)
        {
            foreach ($permissions_names as $permission_name)
            {
                $values_to_insert []= [
                    'role_id' => $roles[$role_name],
                    'permission_id' => $permissions[$permission_name],
                    'created_by' => -1
                ];
            }
        }
        DB::table('role_permissions')->insertOrIgnore($values_to_insert);
    }
}
