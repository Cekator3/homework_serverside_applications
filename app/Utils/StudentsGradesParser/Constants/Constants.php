<?php

namespace App\Utils\StudentsGradesParser\Constants;

class Constants
{
    /**
     * Количество сданных лабораторных работ, необходимых для зачёта
     */
    public const HOMEWORKS_FOR_PASS_1111b = 7;
    /**
     * Количество сданных лабораторных работ, необходимых для зачёта
     */
    public const HOMEWORKS_FOR_PASS_1511b = 10;
    /**
     * Процент посещений для автоматического зачёта
     */
    public const AUTO_PASS_ATTENDANCE = 80;
    public const LESSONS_COLUMNS_START_AT = 26;
    /**
     * Столбец "Зачёт"
     */
    public const ZACHET_COLUMN = 25;
    /**
     * Столбец "Подгруппа"
     */
    public const SUBGROUPS_COLUMN = 2;
    /**
     * Подгруппа студентов, используемая по умолчанию (при отсутствии значения)
     */
    public const STANDARD_SUBGROUP = 1;
}
