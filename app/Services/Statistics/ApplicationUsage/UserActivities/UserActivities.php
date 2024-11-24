<?php

namespace App\Services\Statistics\ApplicationUsage\UserActivities;

use Illuminate\Support\Facades\DB;

/**
 * Модуль для получения статистики об использовании приложения пользователями
 */
class UserActivities
{
    private const QUERY = "
-- Таблица авторизаций пользователей
WITH auth AS (
  SELECT
    user_id,
    MAX(created_at) AS last_got_auth_token_at, -- Время последнего получения токена
    COUNT(id) AS got_auth_token_count          -- Количество токенов
  FROM
    public.logs_requests

  WHERE
    created_at >= NOW() - :max_data_age * INTERVAL '1 hour'
    AND controller = 'App\Http\Controllers\AuthController'
    AND controller_method IN ('register', 'login')
  	AND response_status BETWEEN 200 AND 299

  GROUP BY
    user_id
),

-- Таблица разрешений пользователей
permissions AS (
  SELECT
  	r.user_id,
    COUNT(p.id) AS permissions_count  -- Количество разрешений пользователя,
  FROM
    public.user_roles r

  JOIN public.role_permissions p
    ON p.role_id = r.id

  GROUP BY
    r.user_id
),

-- Таблица запросов пользователей
requests AS (
  SELECT
    user_id,
    MAX(created_at) AS last_seen_at,  -- время последнего запроса
    COUNT(id) AS request_count       -- количество запросов
  FROM
    public.logs_requests

  WHERE
    created_at >= NOW() - :max_data_age * INTERVAL '1 hour'

  GROUP BY
  	user_id
),

-- Таблица изменений сущностей пользователями
entity_updates AS (
  SELECT
    created_by AS user_id,
    MAX(created_at) AS last_updated_entity_at,  -- время последнего изменения сущности
    COUNT(id) AS entity_update_count    -- количество изменений сущности
  FROM
    public.change_logs

  WHERE
    created_at >= NOW() - :max_data_age * INTERVAL '1 hour'

  GROUP BY
  	user_id
)

SELECT
  u.id,
  u.email,

  -- Данные о запросах
  r.last_seen_at,  -- время последнего запроса
  r.request_count,       -- количество запросов

  -- Данные об изменениях сущностей
  eu.last_updated_entity_at,  -- время последнего изменения сущности
  eu.entity_update_count,    -- количество изменений сущности

  -- Данные об авторизациях
  auth.last_got_auth_token_at, -- Время последнего получения аутентификационного токена
  auth.got_auth_token_count, -- Время последнего получения аутентификационного токена

  -- Данные о разрешениях
  p.permissions_count -- Количество разрешений пользователя,
FROM
  public.users u

-- Таблица изменений сущностей пользователями
LEFT JOIN entity_updates eu
	ON u.id = eu.user_id

LEFT JOIN requests r
	ON u.id = r.user_id

LEFT JOIN auth
	ON u.id = auth.user_id

LEFT JOIN permissions p
	ON u.id = p.user_id

    ";

    /**
     * Возвращает статистику об использовании приложения пользователями
     *
     * @param $max_data_age - Временной интервал (в часах), за который собираются данные
     * @return DTOs\Get[]
     */
    public function get(int $max_data_age): array
    {
        $result = [];

        $rows = DB::select(static::QUERY, [":max_data_age" => $max_data_age]);
        foreach ($rows as $row)
            $result []= DTOs\Get::fromObject($row);

        return $result;
    }
}
