<?php

namespace App\Services\Statistics\ApplicationUsage\ControllerMethodCalls;

use DateTime;
use \App\Models\LogRequest;
use Illuminate\Support\Facades\DB;

/**
 * Модуль для получения статистики о вызываемых методов контроллера приложения
 */
class ControllerMethodCalls
{
    /**
     * Возвращает статистику о вызываемых методах контроллера приложения
     *
     * @param $max_data_age - Временной интервал (в часах), за который собираются данные
     * @return DTOs\Get[]
     */
    public function get(int $max_data_age): array
    {
        $methodsCalls = LogRequest::select([
                'controller_method as name',
                DB::raw('COUNT(*) as call_count'),
                DB::raw('max(created_at) as last_called_at'),
            ])
            ->where('created_at', '>=', now()->subHours($max_data_age))
            ->groupBy('controller_method')
            ->orderByDesc('call_count')
            ->get();

        $result = [];
        foreach ($methodsCalls as $method)
        {
            $result []= new DTOs\Get(
                controllerMethod: $method->name,
                callCount: $method->call_count,
                lastCalledAt: new DateTime($method->last_called_at)
            );
        }
        return $result;
    }
}
