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
    public function collection()
    {
        return \App\Models\File::where('files.created_by', auth()->user()->id)
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

    public function headings(): array
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
