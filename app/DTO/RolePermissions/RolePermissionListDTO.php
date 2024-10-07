<?php

namespace App\DTO\RolePermissions;

use App\Models\RolePermission;
use Illuminate\Support\Collection;

class RolePermissionListDTO
{
    /**
     * @var RolePermissionDTO[]
     **/
    public array $rolePermissions;

    /**
     * @param RolePermissionDTO[] $rolePermissions
     */
    public function __construct(array $rolePermissions)
    {
        $this->rolePermissions = $rolePermissions;
    }

    /**
     * @param Collection<RolePermission> $rolePermissions
     */
    public static function fromOrm(Collection $rolePermissions): self
    {
        $rolePermissionDTOs = $rolePermissions->map(function (RolePermission $rolePermission) {
            return RolePermissionDTO::fromOrm($rolePermission);
        })->toArray();

        return new self($rolePermissionDTOs);
    }
}
