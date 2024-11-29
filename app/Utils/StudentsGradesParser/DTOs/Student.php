<?php

namespace App\Utils\StudentsGradesParser\DTOs;

use App\Utils\StudentsGradesParser\Constants\Constants;
use App\Utils\StudentsGradesParser\Constants\LessonType;

class Student
{
    public string $name;
    public string $subgroup;
    /**
     * @var Lesson[]
     */
    public array $lessons;
    public int $visitPercent;
    public int $successLabs = 0;
    public int $successLabsPercent;
    public bool $result;

    public static function isGotZachet(Student $student, array $row, string $groupName): bool
    {
        return (
            ($row[Constants::ZACHET_COLUMN] === 1)
            || ($student->visitPercent >= Constants::AUTO_PASS_ATTENDANCE)
            || (
                ($groupName === "1111б" && $student->successLabs >= Constants::HOMEWORKS_FOR_PASS_1111b)
                || ($groupName === "1511б" && $student->successLabs >= Constants::HOMEWORKS_FOR_PASS_1511b)
            )
        );
    }

    /**
     * $col - столбец (сверху вниз)
     * $row - строка (слева направо)
     */
    public static function parse(array $sheet, array $row, string $groupName): Student
    {
        $student = new Student();
        $student->name = $row[0];
        $student->subgroup = $row[2] ?? Constants::STANDARD_SUBGROUP;
        $student->lessons = Lesson::parse($sheet, $row);

        $visitedLessons = 0;
        $labsCount = 0;
        foreach ($student->lessons as $lesson)
        {
            $student->successLabs += $lesson->successLabs;
            if ($lesson->visit)
                $visitedLessons++;
            if ($lesson->type === LessonType::LAB)
                $labsCount++;
        }
        $student->visitPercent = (int)(($visitedLessons / count($student->lessons) * 100));
        $student->successLabsPercent = (int)($student->successLabs / $labsCount);
        $student->result = static::isGotZachet($student, $row, $groupName);
        return $student;
    }
}
