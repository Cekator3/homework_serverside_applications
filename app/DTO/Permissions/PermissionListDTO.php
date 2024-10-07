<?php

namespace App\DTO\Permissions;

use App\Models\Permission;
use Illuminate\Support\Collection;

class PermissionListDTO
{
    /**
     * @var PermissionDTO[]
     **/
    public array $permissions;

    /**
     * @param PermissionDTO[] $permissions
     */
    public function __construct(array $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @param Collection<Permission> $permissions
     */
    public static function fromOrm(Collection $permissions): self
    {
        $permissionDTOs = $permissions->map(function (Permission $permission) {
            return PermissionDTO::fromOrm($permission);
        })->toArray();

        return new self($permissionDTOs);
    }
}
