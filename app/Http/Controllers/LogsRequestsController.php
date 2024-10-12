<?php

namespace App\Http\Controllers;

use App\Models\LogRequest;
use Illuminate\Http\JsonResponse;
use App\DTO\LogsRequests\LogRequestFullDTO;
use App\DTO\LogsRequests\LogRequestListDTO;
use App\Http\Requests\LogsRequests\GetLogsRequestsRequest;

class LogsRequestsController
{
    public function getLogsRequests(GetLogsRequestsRequest $request)
    {
        $query = LogRequest::query();

        if ($request->has('filters'))
            foreach ($request->filters as $filter)
                $query->where($filter['key'], '=', $filter['value']);

        if ($request->has('sortBy'))
            foreach ($request->sortBy as $sort)
                $query->orderBy($sort['key'], $sort['order']);

        $query = $query->paginate(
            page: $request->input('page'),
            perPage: $request->input('count', 10)
        );

        $logs = collect($query->items());

        return new JsonResponse(LogRequestListDTO::fromOrm($logs));
    }

    public function getLogRequest(int $id)
    {
        $log = LogRequest::findOrFail($id);
        return new JsonResponse(LogRequestFullDTO::fromOrm($log));
    }

    public function hardDeleteLogRequest(int $id)
    {
        $log = LogRequest::findOrFail($id);
        $log->delete();
    }
}
