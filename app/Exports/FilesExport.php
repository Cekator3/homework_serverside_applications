<?php

namespace App\Exports;

use DB;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FilesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public ?int $userId;

    public function __construct(?int $userId = null)
    {
        $this->userId = $userId;
    }

    public function collection()
    {
        $query = \App\Models\File::query();

        if ($this->userId !== null)
            $query = $query->where('files.created_by', $this->userId);

        return $query
            ->join('users', 'files.created_by', 'users.id')
            ->select([
                'files.name AS f_name',
                'files.path',
                'files.created_at',
                'users.id',
                'users.name AS u_name',
            ])
            ->get()
            ->map(function ($file) {
                $file->path = Storage::path($file->path);
                return $file;
            });
    }

    public function headings() : array
    {
        return [
            "Наименование файла",
            "Путь до файла на сервере",
            "Дата загрузки файла",
            "Идентификатор пользователя",
            "Имя пользователя",
        ];
    }
}
