<?php

namespace App\Imports\Parsers\DTOs;

class ParsedUser extends ParsedEntity
{
    public string $name;
    public string $email;
    public string $birthday;
    public string $password;
    public function __construct(string $name, string $email, string $birthday, string $password, int $rowNumber)
    {
        $this->name = $name;
        $this->email = $email;
        $this->birthday = $birthday;
        $this->password = $password;
        $this->rowNumber = $rowNumber;
    }
}
