<?php

namespace App\DTO\LogsRequests;

use App\Models\LogRequest;
use Illuminate\Support\Collection;
use App\DTO\LogsRequests\LogRequestShortDTO;

class LogRequestListDTO
{
    /**
     * @var LogRequestShortDTO[]
     **/
    public array $logs;

    /**
     * @param LogRequestShortDTO[] $logs
     */
    public function __construct(array $logs)
    {
        $this->logs = $logs;
    }

    /**
     * @param Collection<LogRequest> $logs
     */
    public static function fromOrm(Collection $logs): self
    {
        $result = $logs->map(function (LogRequest $log) {
            return LogRequestShortDTO::fromOrm($log);
        })->toArray();

        return new self($result);
    }
}
