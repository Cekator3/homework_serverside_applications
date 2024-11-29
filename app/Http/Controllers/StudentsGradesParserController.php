<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\ParseStudentsGradesRequest;
use App\Utils\StudentsGradesParser\DTOs\StudentsGrades;


class StudentsGradesParserController
{
    function parse(ParseStudentsGradesRequest $request): JsonResponse
    {
        $parser = new StudentsGrades();
        return new JsonResponse($parser->parse($request->file));
    }
}
