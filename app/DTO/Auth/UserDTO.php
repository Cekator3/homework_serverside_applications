<?php

namespace App\DTO\Auth;

use App\Models\User;

class UserDTO
{
    public int $id;
    public string $name;
    public string $email;
    public string $birthday;

    public function __construct(int $id, string $name, string $email, string $birthday)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->birthday = $birthday;
    }

    public static function fromOrm(User $user) : UserDTO
    {
        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            birthday: $user->birthday
        );
    }
}
