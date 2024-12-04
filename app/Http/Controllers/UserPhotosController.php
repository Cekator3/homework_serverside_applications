<?php

namespace App\Http\Controllers;

use App\Exports\FilesExport;
use App\DTO\Files\FileListDTO;
use Illuminate\Http\JsonResponse;
use Intervention\Image\ImageManager;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Users\UploadUserPhotoRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class UserPhotosController
{
    /**
     * Возвращает список всех фотографий
     */
    function list(int $userId)
    {
        if (auth()->user()->id !== $userId)
            abort(Response::HTTP_FORBIDDEN, "Нельзя просматривать чужие фото");

        $files = \App\Models\File::where('created_by', $userId)->get();

        return new JsonResponse(FileListDTO::fromOrm($files));
    }

    /**
     * Возвращает указанную фотографию
     */
    function download(int $userId, int $id)
    {
        $user = auth()->user();
        if ($user->id !== $userId)
            abort(Response::HTTP_NOT_FOUND);

        $file = \App\Models\File::where('created_by', $userId)->findOrFail($id);

        return Storage::download($file->path, $file->name);
    }

    /**
     * Удаляет указанную фотографию
     */
    function remove(int $userId, int $id)
    {
        $user = auth()->user();
        if ($user->id !== $userId)
            abort(Response::HTTP_NOT_FOUND);

        $file = \App\Models\File::where('created_by', $userId)->findOrFail($id);

        $file->delete();
        # Мы не удаляем файл с файловой системы, чтобы у нас всегда был компромат на пользака
    }

    /**
     * Загружает новую фотографию
     */
    function upload(UploadUserPhotoRequest $request, int $userId)
    {
        $user = auth()->user();
        if ($user->id !== $userId)
            abort(Response::HTTP_FORBIDDEN, "Нельзя загружать фотки другим пользователям");

        $file = $request->file("file");

        $path = Storage::putFile($file);

        $file = \App\Models\File::create([
            'name' => $file->getClientOriginalName(),
            'description' => $request->description,
            'format' => $file->guessExtension() ?? $file->getClientOriginalExtension() ?? '',
            'size' => $file->getSize(),
            'path' => $path,
            'created_by' => $userId
        ]);
        return response(status: Response::HTTP_CREATED);
    }

    /**
     * Устанавливает фотографию в качестве аватарки пользователя
     */
    function setAsAvatar(int $userId, int $id)
    {
        $user = auth()->user();
        if ($user->id !== $userId)
            abort(Response::HTTP_NOT_FOUND);

        $file = \App\Models\File::where('created_by', $userId)->findOrFail($id);

        # Договор: Аватарки будут иметь такое же имя файла, но будут находиться в папке avatars
        $avatarPath = "avatars/{$file->path}";

        $isThisAvatarAlreadySet = (
            $user->avatar !== null
            && (
                $user->avatar->path === $avatarPath
                || $user->avatar_id === $id
            )
        );
        if ($isThisAvatarAlreadySet)
            return response(status: Response::HTTP_OK);

        $avatar = ImageManager::imagick()
                              ->read(Storage::get($file->path))
                              ->resizeDown(width: 128, height: 128)
                              ->encode();
        Storage::put($avatarPath, $avatar);

        $dbAvatar = \App\Models\File::withTrashed()->where('path', $avatarPath)->first();
        if ($dbAvatar === null)
        {
            $dbAvatar = \App\Models\File::create([
                'name' => $file->name,
                'description' => $file->description,
                'format' => $file->format,
                'size' => $avatar->size(),
                'path' => $avatarPath,
                'created_by' => $userId
            ]);
        }
        else
        {
            $dbAvatar->restore();
            $dbAvatar->size = $avatar->size();
            $dbAvatar->updated_at = now();
            $dbAvatar->save();
        }
        $user->avatar_id = $dbAvatar->id;
        $user->save();

        return response(status: Response::HTTP_OK);
    }

    /**
     * Возвращает фотографии всех пользователей в архиве
     */
    function downloadAllArchive(): BinaryFileResponse
    {
        $user = auth()->user();

        $files = \App\Models\File::get();
        if ($files->isEmpty())
            abort(RESPONSE::HTTP_BAD_REQUEST, 'No files available to archive');

        $archive = new \ZipArchive();
        $archiveFullpath = Storage::path("$user->name-photo-archive.zip");
        Storage::delete("$user->name-photo-archive.zip");
        $archive->open($archiveFullpath, \ZipArchive::CREATE);

        foreach ($files as $file)
        {
            if (! str_starts_with($file->path, 'avatars/'))
                $archive->addFile(Storage::path($file->path), "originals/$file->path");
            else
                $archive->addFile(Storage::path($file->path), $file->path);
        }

        $excelName = 'overview.xlsx';
        Excel::store(new FilesExport, $excelName);
        $archive->addFile(Storage::path($excelName), $excelName);
        $archive->close();
        Storage::delete($excelName);

        return response()->download($archiveFullpath)->deleteFileAfterSend();
    }

    /**
     * Возвращает все фотографии в архиве
     */
    function downloadArchive(int $userId): BinaryFileResponse
    {
        $user = auth()->user();
        if ($user->id !== $userId)
            abort(Response::HTTP_NOT_FOUND);

        $files = \App\Models\File::where('created_by', $userId)->get();
        if ($files->isEmpty())
            abort(RESPONSE::HTTP_BAD_REQUEST, 'No files available to archive');

        $archive = new \ZipArchive();
        $archiveFullpath = Storage::path("$user->name-photo-archive.zip");
        Storage::delete("$user->name-photo-archive.zip");
        $archive->open($archiveFullpath, \ZipArchive::CREATE);

        foreach ($files as $file)
        {
            if (! str_starts_with($file->path, 'avatars/'))
                $archive->addFile(Storage::path($file->path), "originals/$file->path");
            else
                $archive->addFile(Storage::path($file->path), $file->path);
        }

        $excelName = 'overview.xlsx';
        Excel::store(new FilesExport($userId), $excelName);
        $archive->addFile(Storage::path($excelName), $excelName);
        $archive->close();
        Storage::delete($excelName);

        return response()->download($archiveFullpath)->deleteFileAfterSend();
    }
}
