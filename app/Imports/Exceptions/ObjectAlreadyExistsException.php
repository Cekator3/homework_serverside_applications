<?php

namespace App\Imports\Exceptions;

/**
 * Ошибка нарушения уникальности данных, возникающая при импорте файла
 */
class ObjectAlreadyExistsException extends \Exception
{
    /**
     * @param int $row номер записи в файле
     * @param int $db_obj_id Идентификатор объекта БД, с которым возник конфликт
     * @param int $property_name Человекочитабельное название конфликтного свойства сущности
     */
    public function __construct(int $row, int $db_obj_id, string $property_name)
    {
        parent::__construct("Запись №$row содержит дубликат записи №$db_obj_id по свойству $property_name");
    }
}
