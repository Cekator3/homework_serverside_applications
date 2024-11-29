<?php

namespace App\Utils;

class Regex
{
    /**
     * Проверяет, содержатся ли в строке только русские и пробельные символы
     */
    public static function hasOnlyCyrillicAndSpaces(string $s): bool
    {
        $pattern = '/^[\p{Cyrillic}\s]+$/u';
        return preg_match($pattern, $s) === 1;
    }
}
