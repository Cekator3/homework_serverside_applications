<?php

namespace App\Imports\Importers\DTOs;

use App\Imports\Parsers\DTOs\ParsedEntity;

class EntitiesToImport
{
    private array $mapping;
    /**
     * @param \App\Imports\Parsers\DTOs\ParsedEntity[] $entities
     */
    public function __construct(array $entities, string $uniqueColumn)
    {
        $mapping = [];
        foreach ($entities as $entity)
            $mapping[$entity->{$uniqueColumn}] = $entity;
        $this->mapping = $mapping;
    }

    public function get(mixed $uniqueColumnValue): ParsedEntity|array
    {
        if (is_array($uniqueColumnValue))
        {
            $result = [];
            foreach ($uniqueColumnValue as $value)
                $result[$value] = $this->mapping[$value];
            return $result;
        }
        return $this->mapping[$uniqueColumnValue];
    }

    public function getAll(): array
    {
        return array_values($this->mapping);
    }

    public function getUniqueColumnValues(): array
    {
        return array_keys($this->mapping);
    }

    /**
     * @return array EntitiesToImport[]
     */
    public function diff(mixed $uniqueColumnValues): array
    {
        $existingIds = $this->getUniqueColumnValues();
        $diff = array_diff($uniqueColumnValues, $existingIds);

        $result = [];
        foreach ($diff as $value)
            $result[$value] = $this->mapping[$value];
        return $result;
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
