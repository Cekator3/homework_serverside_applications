<?php

namespace App\Exports;

use App\Models\User;
use App\Models\ChangeLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ChangeLogsExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    public function query()
    {
        return ChangeLog::query();
    }

    public function headings(): array
    {
        return ['id', 'entity_name', 'entity_id', 'old_values', 'new_values'];
    }
}
