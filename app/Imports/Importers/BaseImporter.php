<?php

namespace App\Imports\Importers;

use DB;
use App\Utils\ErrorMessages;
use App\Imports\Parsers\DTOs\ParsedEntity;
use App\Imports\Exceptions\UnknownException;
use App\Imports\Importers\DTOs\EntitiesToImport;
use App\Imports\Importers\DTOs\ImportedEntities;
use App\Imports\Exceptions\ObjectAlreadyExistsException;

/**
 * Модуль для парсинга файла с данными о сущности
 */
abstract class BaseImporter
{
    private ErrorMessages $messages;

    public function __construct()
    {
        $this->messages = new ErrorMessages();
    }

    /**
     * @param ParsedEntity[]
     */
    abstract protected function insertValues(array $entities): array;

    /**
     * @return string[]
     */
    abstract protected function insertColumns(): array;

    /**
     * @return string[]
     */
    abstract protected function updateColumns(): array;

    /**
     * Название столбца БД, ограниченного по уникальности
     */
    abstract protected function uniqueOn(): string;

    /**
     * Название таблицы БД
     */
    abstract protected function table(): string;

    public function getErrors() : array
    {
        return $this->messages->getErrors();
    }

    public function getMessages() : array
    {
        return $this->messages->getAll();
    }

    private function generateInsertQuery(bool $withUpdates, int $valuesAmount): string
    {
        $table = $this->table();
        $uniqueColumn = $this->uniqueOn();
        $columns = $this->insertColumns();
        $columnsStr = implode(', ', $columns);

        // Dynamically generate the placeholders for the VALUES clause
        $placeholdersPerRow = '(' . implode(', ', array_fill(0, count($columns), '?')) . ')';
        $placeholders = implode(', ', array_fill(0, $valuesAmount / count($columns), $placeholdersPerRow));

        $result = "
            INSERT INTO
                $table ($columnsStr)
            VALUES $placeholders
            ON CONFLICT ($uniqueColumn)
        ";
        if ($withUpdates)
        {
            $updateColumns = [];
            foreach ($this->updateColumns() as $column)
                $updateColumns []= "$column = EXCLUDED.$column";
            $updateColumnsStr = implode(', ', $updateColumns);
            $result .= "
            DO UPDATE
            SET $updateColumnsStr
            ";
        }
        else
        {
            $result .= "
            DO NOTHING
            ";
        }
        $result .= "RETURNING id, $uniqueColumn";
        return $result;
    }

    /**
     * @param ParsedEntity[] $entities
     */
    private function insert(array $entities, bool $withUpdates): ImportedEntities
    {
        $values = $this->insertValues($entities);
        $query = $this->generateInsertQuery($withUpdates, valuesAmount: count($values));
        $insertedEntities = DB::select($query, $values);

        return new ImportedEntities($insertedEntities, uniqueColumn: $this->uniqueOn());
    }

    private function _import(array $entities, bool $isUpdateAllowed): void
    {
        $toImport = new EntitiesToImport($entities, uniqueColumn: $this->uniqueOn());
        $inserted = $this->insert($entities, withUpdates: false);

        if ($toImport->count() == $inserted->count())
        {
            $this->logUpsertedEntities($inserted, $toImport, isInserted: true);
            return;
        }

        if (! $isUpdateAllowed)
        {
            $notInserted = $toImport->get(array_diff($toImport->getUniqueColumnValues(), $inserted->getUniqueColumnValues()));
            $this->logDublicatedEntities($notInserted);
            return;
        }

        $this->logUpsertedEntities($inserted, $toImport, isInserted: true);

        $toImport->remove($inserted->getUniqueColumnValues());
        $updated = $this->insert($toImport->getAll(), withUpdates: true);
        $this->logUpsertedEntities($updated, $toImport, isInserted: false);
        // foreach ($updated->getUniqueColumnValues() as $i)
        // {
        //     $importedFromRow = $toImport->get($i)->rowNumber;
        //     $idFromDb = $updated->getId($i);

        //     $message = "Запись №$importedFromRow успешно обновила запись с идентификатором №$idFromDb";
        //     $this->messages->add($message, isError: false);
        // }
    }

    /**
     * Считывает из файла импортируемые данные
     *
     * @param ParsedEntity[] $entities
     */
    public function import(array $entities, bool $isUpdateAllowed): void
    {
        if (empty($entities))
            return;

        DB::beginTransaction();
        try
        {
            $this->_import($entities, $isUpdateAllowed);
            if ($this->messages->hasErrors())
                DB::rollBack();
            else
                DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            throw $e;
        }
    }

    public function logUpsertedEntities(ImportedEntities $inserted, EntitiesToImport $toImport, bool $isInserted) : void
    {
        foreach ($inserted->getUniqueColumnValues() as $uniqueColumnValue)
        {
            $importedFromRow = $toImport->get($uniqueColumnValue)->rowNumber;
            $idFromDb = $inserted->getId($uniqueColumnValue);

            $message = $isInserted
                ? "Запись №{$importedFromRow} успешно добавлена с идентификатором №{$idFromDb}"
                : "Запись №{$importedFromRow} успешно обновила запись с идентификатором №{$idFromDb}";

            $this->messages->add($message, isError: false);
        }
    }

    /**
     * Summary of logDublicatedEntities
     * @param \App\Imports\Parsers\DTOs\ParsedEntity[] $entities
     * @return void
     */
    public function logDublicatedEntities(array $entities): void
    {
        $entities = new EntitiesToImport($entities, uniqueColumn: $this->uniqueOn());

        $table = $this->table();
        $uniqueColumn = $this->uniqueOn();
        $dublicatedUniqueColumnValues = $entities->getUniqueColumnValues();

        $placeholders = implode(', ', array_fill(0, count($dublicatedUniqueColumnValues), '?'));
        $query = "
            SELECT
                id,
                $uniqueColumn
            FROM
                $table
            WHERE
                $uniqueColumn IN ($placeholders)
        ";
        $existing = DB::select($query, $dublicatedUniqueColumnValues);

        foreach ($existing as $existingEntity)
        {
            $dublicatedEntity = $entities->get($existingEntity->{$uniqueColumn});
            $message = "Запись №{$dublicatedEntity->rowNumber} содержит дубликат записи №{$existingEntity->id} по свойству {property}";
            $this->messages->add($message, isError: true);
        }
    }

    private function onUniqueConstraintViolation(ParsedEntity $entity, int $db_obj_id): void
    {
        $err = new ObjectAlreadyExistsException($entity->rowNumber, $db_obj_id, property_name: $this->uniqueOn());
        $this->messages->add($err->getMessage(), isError: true);
    }

    private function onUnknownError(ParsedEntity $entity): void
    {
        $err = new UnknownException($entity->rowNumber);
        $this->messages->add($err->getMessage(), isError: true);
    }
}
