<?php

namespace App\DTO\Auth;

use App\Models\User;
use Illuminate\Support\Collection;

class UserListDTO
{
    /**
     * @var UserDTO[]
     **/
    public array $users;

    /**
     * @param UserDTO[] $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * @param Collection<User> $users
     */
    public static function fromOrm(Collection $users): self
    {
        $userDTOs = $users->map(function (User $user) {
            return UserDTO::fromOrm($user);
        })->toArray();

        return new self($userDTOs);
    }
}
