<?php

namespace App\Utils\StudentsGradesParser\DTOs;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Utils\StudentsGradesParser\DTOs\Group;

/**
 * Модуль для парсинга файла с успеваемостью студентов
 */
class StudentsGrades
{
    /**
     * @var Group[]
     */
    public array $groups;

    public static function parse(UploadedFile $file): StudentsGrades
    {
        $sheet = Excel::toArray([], $file)[0];
        $result = new StudentsGrades();
        $result->groups = Group::parse($sheet);
        return $result;
    }
}
