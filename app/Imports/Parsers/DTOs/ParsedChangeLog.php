<?php

namespace App\Imports\Parsers\DTOs;

use App\Imports\Parsers\DTOs\ParsedEntity;

class ParsedChangeLog extends ParsedEntity
{
    public int $id;
    public string $entityName;
    public int $entityId;
    public string $oldValues;
    public string $newValues;
    public int $rowNumber;

    public function __construct(
        int $id,
        string $entityName,
        int $entityId,
        string $oldValues,
        string $newValues,
        int $rowNumber
    ) {
        $this->id = $id;
        $this->entityName = $entityName;
        $this->entityId = $entityId;
        $this->oldValues = $oldValues;
        $this->newValues = $newValues;
        $this->rowNumber = $rowNumber;
    }
}
