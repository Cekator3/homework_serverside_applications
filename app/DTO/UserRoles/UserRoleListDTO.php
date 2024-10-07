<?php

namespace App\DTO\UserRoles;

use App\Models\UserRole;
use Illuminate\Support\Collection;

class UserRoleListDTO
{
    /** @var UserRoleDTO[] */
    public array $userRoles;

    /**
     * @param UserRoleDTO[] $userRoles
     */
    public function __construct(array $userRoles)
    {
        $this->userRoles = $userRoles;
    }

    /**
     * @param Collection<UserRole> $userRoles
     */
    public static function fromOrm(Collection $userRoles): self
    {
        $userRoleDTOs = $userRoles->map(function (UserRole $userRole) {
            return UserRoleDTO::fromOrm($userRole);
        })->toArray();

        return new self($userRoleDTOs);
    }
}
