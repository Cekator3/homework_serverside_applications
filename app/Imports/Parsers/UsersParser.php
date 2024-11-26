<?php

namespace App\Imports\Parsers;

use App\Imports\Parsers\BaseParser;
use Illuminate\Support\Facades\Hash;
use App\Imports\Parsers\DTOs\ParsedUser;
use App\Http\Requests\Auth\UserValidationRules;

/**
 * Модуль для парсинга файла с данными о пользователях
 */
class UsersParser extends BaseParser
{
    protected function expectedColumns(): array
    {
        return ['name', 'email', 'birthday', 'password'];
    }

    protected function rules() : array
    {
        return [
            'name' => UserValidationRules::NAME,
            'email' => UserValidationRules::EMAIL(isUnique: false),
            'birthday' => UserValidationRules::BIRTHDAY,
            'password' => UserValidationRules::PASSWORD(with_confirmation: false),
        ];
    }

    protected function parseBodyRow(array $columns, int $rowNumber): ParsedUser
    {
        return new ParsedUser(
            name: $columns['name'],
            email: $columns['email'],
            password: Hash::make($columns['password']),
            birthday: $columns['birthday'],
            rowNumber: $rowNumber
        );
    }
}
