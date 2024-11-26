<?php

namespace App\Imports\Parsers;

use App\Imports\Parsers\BaseParser;
use App\Imports\Parsers\DTOs\ParsedChangeLog;

class ChangeLogsParser extends BaseParser
{
    protected function expectedColumns(): array
    {
        return ['id', 'entity_name', 'entity_id', 'old_values', 'new_values'];
    }

    protected function rules() : array
    {
        return [
            'id' => 'required|integer',
            'entity_name' => 'required|string|max:255',
            'entity_id' => 'required|integer',
            'old_values' => 'required|json',
            'new_values' => 'required|json',
        ];
    }

    protected function parseBodyRow(array $columns, int $rowNumber): ParsedChangeLog
    {
        return new ParsedChangeLog(
            id: $columns['id'],
            entityName: $columns['entity_name'],
            entityId: $columns['entity_id'],
            oldValues: $columns['old_values'],
            newValues: $columns['new_values'],
            rowNumber: $rowNumber
        );
    }
}
