<?php

namespace App\Services\Statistics\ApplicationUsage\EntityChanges;

use DateTime;
use App\Models\ChangeLog;
use Illuminate\Support\Facades\DB;

/**
 * Модуль для получения статистики об изменениях сущностей приложения
 */
class EntityChanges
{
    /**
     * Возвращает статистику об изменениях сущностей приложения
     *
     * @param $max_data_age - Временной интервал (в часах), за который собираются данные
     * @return DTOs\Get[]
     */
    public function get(int $max_data_age): array
    {
        $entityChanges = ChangeLog::select([
                'entity_name AS name',
                DB::raw('count(*) AS changes_count'),
                DB::raw('max(created_at) AS last_changed_at')
            ])
            ->where('created_at', '>=', now()->subHours($max_data_age))
            ->groupBy('entity_name')
            ->orderByDesc('changes_count')
            ->get();

        $result = [];
        foreach ($entityChanges as $entity)
        {
            $result []= new DTOs\Get(
                entityName: $entity->name,
                changesCount: $entity->changes_count,
                lastChangedAt: new DateTime($entity->last_changed_at)
            );
        }
        return $result;
    }
}
