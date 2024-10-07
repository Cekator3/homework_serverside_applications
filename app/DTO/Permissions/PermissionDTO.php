<?php

namespace App\DTO\Permissions;

use App\Models\Permission;

class PermissionDTO
{
    public int $id;
    public string $name;
    public ?string $description;
    public string $code;

    public function __construct(int $id, string $name, ?string $description, string $code)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->code = $code;
    }

    public static function fromOrm(Permission $permission): self
    {
        return new self(
            id: $permission->id,
            name: $permission->name,
            description: $permission->description,
            code: $permission->code,
        );
    }
}
