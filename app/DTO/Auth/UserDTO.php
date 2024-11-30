<?php

namespace App\DTO\Auth;

use App\Models\User;

class UserDTO
{
    public int $id;
    public string $name;
    public string $email;
    public string $birthday;
    public ?int $photo_id;

    public function __construct(int $id, string $name, string $email, string $birthday, ?int $photo_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->birthday = $birthday;
        $this->photo_id = $photo_id;
    }

    public static function fromOrm(User $user) : UserDTO
    {
        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            birthday: $user->birthday,
            photo_id: $user->photo_id,
        );
    }
}
