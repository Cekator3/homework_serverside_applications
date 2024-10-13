<?php

namespace App\Services\Statistics\ApplicationUsage\UserActivities\DTOs;

use DateTime;

/**
 * Модуль для хранения статистики об использовании приложения пользователем
 */
class Get
{
    public int $id;
    public string $email;
    public DateTime $lastSeenAt;

    public function __construct(int $id, string $email, DateTime $lastSeenAt)
    {
        $this->id = $id;
        $this->email = $email;
        $this->lastSeenAt = $lastSeenAt;
    }
}
