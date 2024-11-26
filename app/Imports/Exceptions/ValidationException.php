<?php

namespace App\Imports\Exceptions;

/**
 * Ошибка целостности данных, возникающая при импорте файла
 */
class ValidationException extends \Exception
{
    /**
     * @param int $row номер записи в файле
     * @param string $reason Причина возникновения ошибки
     */
    public function __construct(int $row, string $reason)
    {
        parent::__construct("Запись №$row не удалось добавить/обновить. $reason.");
    }
}
