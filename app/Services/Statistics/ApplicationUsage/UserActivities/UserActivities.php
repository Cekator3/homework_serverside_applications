<?php

namespace App\Services\Statistics\ApplicationUsage\UserActivities;

use DateTime;
use Illuminate\Support\Facades\DB;

/**
 * Модуль для получения статистики об использовании приложения пользователями
 */
class UserActivities
{
    /**
     * Возвращает статистику об использовании приложения пользователями
     *
     * @param $max_data_age - Временной интервал (в часах), за который собираются данные
     * @return DTOs\Get[]
     */
    public function get(int $max_data_age): array
    {
        $users = DB::table('public.users', as: 'u')
                ->select([
                    'u.id AS id',
                    'u.email AS email',
                    DB::raw('max(lr.created_at) AS last_seen_at')
                ])
                ->join('public.logs_requests AS lr', 'lr.user_id', '=', 'u.id')
                ->where('lr.created_at', '>=', now()->subHours($max_data_age))
                ->groupBy(['u.id', 'u.email'])
                ->orderByDesc('last_seen_at')
                ->get();

        $result = [];
        foreach ($users as $user)
        {
            $result []= new DTOs\Get(
                id: $user->id,
                email: $user->email,
                lastSeenAt: new DateTime($user->last_seen_at),
            );
        }
        return $result;
    }
}
