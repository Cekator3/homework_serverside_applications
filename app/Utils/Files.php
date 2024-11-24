<?php

namespace App\Utils;

/**
 * Модуль для взаимодействия с локальными файлами
 */
class Files
{
    /**
     * Сохраняет указанные данные в temp-файл
     *
     * @param string $content
     * @return string Путь к temp-файлу
     */
    static function create_temp(string $content): string
    {
        $path = tempnam(sys_get_temp_dir(), "");
        if ($path == false)
            throw new \RuntimeException("tempnam failed");

        $isSuccess = file_put_contents($path, $content);
        if ($isSuccess === false)
            throw new \RuntimeException("file_put_contents failed");

        return $path;
    }
}
