<?php

namespace App\DTO\RolePermissions;

use App\Models\RolePermission;

class RolePermissionDTO
{
    public int $id;
    public int $permission_id;
    public int $role_id;

    public function __construct(int $id, int $permission_id, int $role_id)
    {
        $this->id = $id;
        $this->permission_id = $permission_id;
        $this->role_id = $role_id;
    }

    public static function fromOrm(RolePermission $rolePermission): self
    {
        return new self(
            id: $rolePermission->id,
            permission_id: $rolePermission->permission_id,
            role_id: $rolePermission->role_id,
        );
    }
}
