<?php

namespace App\Services\Statistics\ApplicationUsage\EntityChanges\DTOs;
use DateTime;

/**
 * Модуль для хранения статистики об изменениях сущностей приложения
 */
class Get
{
    public string $entityName;
    public int $changesCount;
    public DateTime $lastChangedAt;

    public function __construct(string $entityName, int $changesCount, DateTime $lastChangedAt)
    {
        $this->entityName = $entityName;
        $this->changesCount = $changesCount;
        $this->lastChangedAt = $lastChangedAt;
    }
}
