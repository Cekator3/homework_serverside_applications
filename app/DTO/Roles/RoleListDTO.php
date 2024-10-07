<?php

namespace App\DTO\Roles;

use App\Models\Role;
use Illuminate\Support\Collection;

class RoleListDTO
{
    /** @var RoleDTO[] */
    public array $roles;

    /**
     * @param RoleDTO[]
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param Collection<Role> $roles
     */
    public static function fromOrm(Collection $roles): self
    {
        $roleDTOs = $roles->map(function (Role $role) {
            return RoleDTO::fromOrm($role);
        })->toArray();

        return new self($roleDTOs);
    }
}
