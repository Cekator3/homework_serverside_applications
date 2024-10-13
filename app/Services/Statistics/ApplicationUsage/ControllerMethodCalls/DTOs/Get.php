<?php

namespace App\Services\Statistics\ApplicationUsage\ControllerMethodCalls\DTOs;
use DateTime;

/**
 * Модуль для хранения статистики о вызываемых методах контроллера приложения
 */
class Get
{
    public string $controllerMethod;
    public string $callCount;
    public DateTime $lastCalledAt;

    public function __construct(string $controllerMethod, string $callCount, DateTime $lastCalledAt)
    {
        $this->controllerMethod = $controllerMethod;
        $this->callCount = $callCount;
        $this->lastCalledAt = $lastCalledAt;
    }
}
