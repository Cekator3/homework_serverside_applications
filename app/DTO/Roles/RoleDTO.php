<?php

namespace App\DTO\Roles;

use App\Models\Role;

class RoleDTO
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

    public static function fromOrm(Role $role): self
    {
        return new self(
            id: $role->id,
            name: $role->name,
            description: $role->description,
            code: $role->code,
        );
    }
}
