<?php

namespace App\DTO\LogsRequests;

use App\Models\LogRequest;

class LogRequestShortDTO
{
    public string $url;
    public string $controller;
    public string $controller_method;
    public int $response_status;
    public string $created_at;

    public function __construct(
        string $controller,
        string $controller_method,
        string $response_status,
        string $created_at,
    ) {
        $this->controller = $controller;
        $this->controller_method = $controller_method;
        $this->response_status = (int)$response_status;
        $this->created_at = $created_at;
    }

    /**
     *
     */
    public static function fromOrm(LogRequest $logRequest): self
    {
        return new self(
            controller: $logRequest->controller,
            controller_method: $logRequest->controller_method,
            response_status: $logRequest->response_status,
            created_at: $logRequest->created_at,
        );
    }
}
