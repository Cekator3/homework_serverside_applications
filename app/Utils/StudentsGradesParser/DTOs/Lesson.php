<?php

namespace App\Utils\StudentsGradesParser\DTOs;

use Illuminate\Support\Carbon;
use App\Utils\StudentsGradesParser\Constants\Constants;
use App\Utils\StudentsGradesParser\Constants\LessonType;

class Lesson
{
    public string $date;
    public string $time;
    public LessonType $type;
    public int $number;
    public int $subgroups;
    public bool $visit;
    public int $successLabs;
    private const SECONDS_IN_DAY = 86400;

    private static function parseDate(int $date): string
    {
        return Carbon::createFromDate(1900, 1, 1)->addDays($date - 2)->toDateString();
    }

    private static function parseTime(float $time): string
    {
        $SECONDS_IN_DAY = 86400;
        $seconds = $time * $SECONDS_IN_DAY;
        return Carbon::createFromTime(0, 0, 0)->addSeconds($seconds)->toTimeString();
    }

    private static function parseLessonType(string $lessonName): LessonType
    {
        if (str_contains($lessonName, "ЛК"))
            return LessonType::LECTURE;
        return LessonType::LAB;
    }

    private static function countSuccessfullLabs(string|null $lessonResults): int
    {
        if ($lessonResults === null)
            return 0;
        return substr_count($lessonResults, "✅");
    }

    /**
     * Подсчитывает кол-во посетивших пару подгрупп
     */
    private static function countSubgroups(array $sheet, int $lessonColumnIdx): int
    {
        $subgroupsColumn = array_column($sheet, Constants::SUBGROUPS_COLUMN);
        $lessonColumn = array_column($sheet, $lessonColumnIdx);

        $result = 0;
        $subgroupsAttended = [];

        for ($row = 6; $row < count($subgroupsColumn); $row++)
        {
            $isThisTheEnd = $sheet[$row][0] === null;
            $studentSubgroup = $subgroupsColumn[$row] ?? Constants::STANDARD_SUBGROUP;
            $isStudentAttendedLesson = $lessonColumn[$row] !== null;
            $isGroupEnded = $sheet[$row][1] === null;

            if ($isStudentAttendedLesson)
            {
                $subgroupsAttended[$studentSubgroup] = true;
                continue;
            }

            if ($isGroupEnded)
            {
                $result += count($subgroupsAttended);
                $subgroupsAttended = [];
                continue;
            }

            if ($isThisTheEnd)
                break;
        }

        $result += count($subgroupsAttended);

        return $result;
    }

    private static function isEverythingParsed(array $sheet, int $col): bool
    {
        # столбец с датой пуст
        return $sheet[2][$col] === null;
    }

    /**
     * $col - столбец (сверху вниз)
     * $row - строка (слева направо)
     * @return Lesson[]
     */
    public static function parse(array $sheet, array $row): array
    {
        $result = [];
        for ($col = Constants::LESSONS_COLUMNS_START_AT; $col < count($row); $col++)
        {
            if (static::isEverythingParsed($sheet, $col))
                break;
            $lesson = new Lesson();
            $lesson->date = static::parseDate($sheet[2][$col]);
            $lesson->time = static::parseTime($sheet[3][$col]);
            $lesson->type = static::parseLessonType($sheet[1][$col]);
            $lesson->number = $sheet[4][$col];
            $lesson->visit = ! empty($row[$col]);
            $lesson->successLabs = static::countSuccessfullLabs($row[$col]);
            $lesson->subgroups = static::countSubgroups($sheet, $col);
            $result []= $lesson;
        }
        return $result;
    }
}
