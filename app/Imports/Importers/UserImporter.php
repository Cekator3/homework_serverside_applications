<?php

namespace App\Imports\Importers;

use App\Imports\Parsers\DTOs\ParsedUser;

class UserImporter extends BaseImporter
{
    /**
     * @param ParsedUser[]
     */
    protected function insertValues(array $users): array
    {
        $values = [];
        foreach ($users as $user)
            $values = array_merge($values, [$user->name, $user->email, $user->birthday, $user->password]);
        return $values;
    }

    /**
     * @return string[]
     */
    protected function insertColumns(): array
    {
        return ['name', 'email', 'birthday', 'password'];
    }

    /**
     * @return string[]
     */
    protected function updateColumns(): array
    {
        return ['name', 'email', 'birthday', 'password'];
    }

    /**
     * Название столбца БД, ограниченного по уникальности
     */
    protected function uniqueOn(): string
    {
        return 'email';
    }

    /**
     * Название таблицы БД
     */
    protected function table(): string
    {
        return 'public.users';
    }
}
