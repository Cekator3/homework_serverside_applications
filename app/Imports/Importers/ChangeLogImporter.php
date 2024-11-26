<?php

namespace App\Imports\Importers;

use App\Imports\Importers\BaseImporter;
use App\Imports\Parsers\DTOs\ParsedChangeLog;

class ChangeLogImporter extends BaseImporter
{
    private int $createdBy;

    public function __construct(int $createdBy)
    {
        $this->createdBy = $createdBy;
        parent::__construct();
    }

    /**
     * @param ParsedChangeLog[] $logs
     */
    protected function insertValues(array $logs): array
    {
        $values = [];
        foreach ($logs as $log)
            $values = array_merge($values, [$log->id, $log->entityName, $log->entityId, $log->oldValues, $log->newValues, $this->createdBy]);
        return $values;
    }

    /**
     * @return string[]
     */
    protected function insertColumns(): array
    {
        return ['id', 'entity_name', 'entity_id', 'old_values', 'new_values', 'created_by'];
    }

    /**
     * @return string[]
     */
    protected function updateColumns(): array
    {
        return ['id', 'entity_name', 'entity_id', 'old_values', 'new_values'];
    }

    /**
     * Название столбца БД, ограниченного по уникальности
     */
    protected function uniqueOn(): string
    {
        return 'id';
    }

    /**
     * Название таблицы БД
     */
    protected function table(): string
    {
        return 'public.change_logs';
    }
}
