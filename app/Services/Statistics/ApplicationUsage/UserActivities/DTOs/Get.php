<?php

namespace App\Services\Statistics\ApplicationUsage\UserActivities\DTOs;

use DateTime;
use stdClass;

/**
 * Модуль для хранения статистики об использовании приложения пользователем
 */
class Get
{
    public int $id;
    public string $email;
    public ?DateTime $lastSeenAt;
    public int $requestCount;
    public ?DateTime $lastUpdatedEntityAt;
    public int $entityUpdateCount;
    public ?DateTime $lastGotAuthTokenAt;
    public int $gotAuthTokenCount;
    public int $permissionsCount;

    public static function fromObject(stdClass $obj): Get
    {
        $result = new Get();
        $result->id = $obj->id;
        $result->email = $obj->email;
        $result->lastSeenAt = $obj->last_seen_at ? new DateTime($obj->last_seen_at) : null;
        $result->requestCount = $obj->request_count ?? 0;
        $result->lastUpdatedEntityAt = $obj->last_updated_entity_at ? new DateTime($obj->last_updated_entity_at) : null;
        $result->entityUpdateCount = $obj->entity_update_count ?? 0;
        $result->lastGotAuthTokenAt = $obj->last_got_auth_token_at ? new DateTime($obj->last_got_auth_token_at) : null;
        $result->gotAuthTokenCount = $obj->got_auth_token_count ?? 0;
        $result->permissionsCount = $obj->permissions_count ?? 0;
        return $result;
    }
}
