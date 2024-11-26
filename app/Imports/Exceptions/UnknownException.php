<?php

namespace App\Imports\Exceptions;

/**
 * Неизвестная ошибка, возникающая при импорте файла
 */
class UnknownException extends \Exception
{
    /**
     * @param int $row номер записи в файле
     */
    public function __construct(int $row)
    {
        parent::__construct("Запись №$row не удалось добавить/обновить. Неизвестная ошибка.");
    }
}
