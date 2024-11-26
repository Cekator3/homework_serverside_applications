<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    public function query()
    {
        return User::query();
    }

    public function headings(): array
    {
        return [
            "name",
            "email",
            "birthday",
        ];
    }
}
