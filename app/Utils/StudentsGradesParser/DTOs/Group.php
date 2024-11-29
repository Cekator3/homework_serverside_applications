<?php

namespace App\Utils\StudentsGradesParser\DTOs;

use App\Utils\Regex;
use App\Utils\StudentsGradesParser\DTOs\GroupResult;

/**
 * Модуль для парсинга успеваемости групп студентов
 */
class Group
{
    public string $groupName;
    /**
     * @var Student[]
     */
    public array $students = [];
    public GroupResult $result;

    /**
     * Проверяет, является ли ячейка названием группы
     */
    private static function isGroupName(array $sheet, int $row, int $col): bool
    {
        $rightCell = $sheet[$row][$col + 1];
        return empty($rightCell);
    }

    /**
     * Проверяет, является ли строка ФИО студента
     */
    private static function isFullName(string $s, int $row): bool
    {
        return Regex::hasOnlyCyrillicAndSpaces($s) && $row !== 0;
    }

    /**
     * $col - столбец (сверху вниз)
     * $row - строка (слева направо)
     * @return Group[]
     */
    public static function parse(array $sheet): array
    {
        $col = 0;
        $groups_column = array_column($sheet, $col);

        $result = [];
        $currentGroup = new Group();
        for ($row = 0; $row < count($groups_column); $row++)
        {
            $cell = $groups_column[$row];
            if (empty($cell))
                continue;

            if (static::isFullName($cell, $row))
            {
                $currentGroup->students []= Student::parse($sheet, $sheet[$row], $currentGroup->groupName);
                continue;
            }

            if (static::isGroupName($sheet, $row, col: 0))
            {
                if (! empty($currentGroup->students))
                {
                    $currentGroup->result = new GroupResult($currentGroup->students);
                    $result []= $currentGroup;
                    $currentGroup = new Group();
                }
                $currentGroup->groupName = $cell;
            }
        }
        return $result;
    }
}
