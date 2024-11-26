<?php

namespace App\Imports\Importers\DTOs;

/**
 * Успешно импортированный объект
 */
class ImportedEntities
{
    private array $mapping;
    /**
     * @param object[] $entities
     */
    public function __construct(array $entities, string $uniqueColumn)
    {
        $mapping = [];
        foreach ($entities as $entity)
            $mapping[$entity->{$uniqueColumn}] = $entity;
        $this->mapping = $mapping;
    }

    /**
     * Возвращает идентификатор объекта в БД
     */
    public function getId($uniqueColumnValue): int
    {
        return $this->mapping[$uniqueColumnValue]->id;
    }

    public function getUniqueColumnValues(): array
    {
        return array_keys($this->mapping);
    }

    public function diff(mixed $uniqueColumnValues): array
    {
        $existingIds = $this->getUniqueColumnValues();
        $diff = array_diff($uniqueColumnValues, $existingIds);
        return $diff;
    }

    public function remove(array $uniqueColumnValues): void
    {
        foreach ($uniqueColumnValues as $uniqueColumnValue)
            unset($this->mapping[$uniqueColumnValue]);
    }

    public function count(): int
    {
        return count($this->mapping);
    }
}
