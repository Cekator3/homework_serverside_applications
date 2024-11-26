<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Exports\ChangeLogsExport;
use App\Imports\Parsers\UsersParser;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ExcelFileRequest;
use App\Imports\Importers\UserImporter;
use App\Imports\Parsers\ChangeLogsParser;
use App\Imports\Importers\ChangeLogImporter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ChangeLogsController
{
    function import(ExcelFileRequest $request)
    {
        $parser = new ChangeLogsParser();
        $parsedUsers = $parser->parse($request->file, ignoreErrors: $request->ignoreErrors);
        if (! $request->ignoreErrors && $parser->hasErrors())
        {
            return [
                'validation_errors' => $parser->getErrors(),
                'import_messages' => [],
                'import_errors' => [],
            ];
        }

        $importer = new ChangeLogImporter($request->user()->id);
        $importer->import($parsedUsers, isUpdateAllowed: $request->isUpdatesAllowed);
        return [
            'validation_errors' => $parser->getErrors(),
            'import_messages' => $importer->getMessages(),
            'import_errors' => $importer->getErrors(),
        ];
    }

    function export(): BinaryFileResponse
    {
        return Excel::download(new ChangeLogsExport, 'change_logs.xlsx');
    }
}
