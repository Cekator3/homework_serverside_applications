<?php

namespace App\DTO\UserRoles;

use App\Models\UserRole;

class UserRoleDTO
{
    public int $id;
    public int $user_id;
    public int $role_id;

    public function __construct(int $id, int $user_id, int $role_id)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->role_id = $role_id;
    }

    public static function fromOrm(UserRole $userRole): self
    {
        return new self(
            id: $userRole->id,
            user_id: $userRole->user_id,
            role_id: $userRole->role_id,
        );
    }
}
