<?php

namespace App\Http\Requests\LogsRequests;

use Illuminate\Foundation\Http\FormRequest;

class GetLogsRequestsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'filters' => 'array',
            'filters.*.key' => 'required|string|in:user_id,response_status,user_ip,user_agent,controller',
            'filters.*.value' => 'required|string',

            'sortBy' => 'array',
            'sortBy.*.key' => 'required|string|in:url,controller,controller_method,response_status,created_at',
            'sortBy.*.order' => 'required|string|in:asc,desc',

            'page' => 'integer|min:1',
            'count' => 'integer|min:1|max:100',
        ];
    }
}
