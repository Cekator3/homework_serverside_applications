<?php

namespace App\Services\ReportGenerators\ApplicationUsage;

use App\Services\Statistics;

/**
 * Модуль для генерации отчёта об использовании приложения в формате JSON
 */
class Json
{
    private Statistics\ApplicationUsage\UserActivities\UserActivities $userActivities;
    private Statistics\ApplicationUsage\EntityChanges\EntityChanges $entityChanges;
    private Statistics\ApplicationUsage\ControllerMethodCalls\ControllerMethodCalls $controllerMethodCalls;

    public function __construct(
        ?Statistics\ApplicationUsage\UserActivities\UserActivities $userActivities = null,
        ?Statistics\ApplicationUsage\EntityChanges\EntityChanges $entityChanges = null,
        ?Statistics\ApplicationUsage\ControllerMethodCalls\ControllerMethodCalls $controllerMethodCalls = null
    )
    {
        $this->userActivities = $userActivities ?: new Statistics\ApplicationUsage\UserActivities\UserActivities();
        $this->entityChanges = $entityChanges ?: new Statistics\ApplicationUsage\EntityChanges\EntityChanges();
        $this->controllerMethodCalls = $controllerMethodCalls ?: new Statistics\ApplicationUsage\ControllerMethodCalls\ControllerMethodCalls();
    }

    /**
     * Генерирует отчёт об использовании приложения в формате JSON
     *
     * @param $maxDataAge Временной интервал (в часах), за который собираются данные для отчёта
     */
    public function generate(int $maxDataAge): array
    {
        $usersActivities = $this->userActivities->get($maxDataAge);
        $entitiesChanges = $this->entityChanges->get($maxDataAge);
        $controllerMethodCalls = $this->controllerMethodCalls->get($maxDataAge);

        return [
            'report_generated_at' => now()->toDateTimeString(),
            'max_data_age' => $maxDataAge,
            'users_activities' => $usersActivities,
            'entities_changes' => $entitiesChanges,
            'controllers_method_calls' => $controllerMethodCalls,
        ];
    }
}
