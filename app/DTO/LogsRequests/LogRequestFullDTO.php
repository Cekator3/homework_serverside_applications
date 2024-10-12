<?php

namespace App\DTO\LogsRequests;

use App\Models\LogRequest;

class LogRequestFullDTO
{
    public int $id;
    public string $url;
    public string $http_method;
    public string $controller;
    public string $controller_method;
    public ?array $request_body;
    public ?array $request_headers;
    public ?int $user_id;
    public string $user_ip;
    public ?string $user_agent;
    public int $response_status;
    public ?array $response_body;
    public ?array $response_headers;
    public string $created_at;

    public function __construct(
        int $id,
        string $url,
        string $http_method,
        string $controller,
        string $controller_method,
        ?array $request_body,
        ?array $request_headers,
        ?int $user_id,
        string $user_ip,
        ?string $user_agent,
        int $response_status,
        ?array $response_body,
        ?array $response_headers,
        string $created_at,
    ) {
        $this->id = $id;
        $this->url = $url;
        $this->http_method = $http_method;
        $this->controller = $controller;
        $this->controller_method = $controller_method;
        $this->request_body = $request_body;
        $this->request_headers = $request_headers;
        $this->user_id = $user_id;
        $this->user_ip = $user_ip;
        $this->user_agent = $user_agent;
        $this->response_status = $response_status;
        $this->response_body = $response_body;
        $this->response_headers = $response_headers;
        $this->created_at = $created_at;
    }

    /**
     *
     */
    public static function fromOrm(LogRequest $logRequest): self
    {
        return new self(
            $logRequest->id,
            $logRequest->url,
            $logRequest->http_method,
            $logRequest->controller,
            $logRequest->controller_method,
            $logRequest->request_body ? json_decode($logRequest->request_body, true) : null,
            $logRequest->request_headers ? json_decode($logRequest->request_headers, true) : null,
            $logRequest->user_id,
            $logRequest->user_ip,
            $logRequest->user_agent,
            $logRequest->response_status,
            $logRequest->response_body ? json_decode($logRequest->response_body, true) : null,
            $logRequest->response_headers ? json_decode($logRequest->response_headers, true) : null,
            $logRequest->created_at->toDateTimeString(),
        );
    }
}
