<?php

namespace App\Utils\StudentsGradesParser\DTOs;

class GroupResult
{
    public int $success = 0;
    public int $unsuccessfully = 0;

    /**
     * @param Student[] $students
     */
    public function __construct(array $students)
    {
        foreach ($students as $student)
        {
            if ($student->result)
                $this->success++;
            else
                $this->unsuccessfully++;
        }
    }
}
