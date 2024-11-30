<?php

namespace App\DTO\Files;

use App\Models\File;
use App\DTO\Files\FileDTO;
use Illuminate\Support\Collection;

class FileListDTO
{
    /** @var FileDTO[] */
    public array $files;

    /**
     * @param FileDTO[]
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @param Collection<File> $files
     */
    public static function fromOrm(Collection $files): self
    {
        $fileDTOs = $files->map(function (File $file) {
            return FileDTO::fromOrm($file);
        })->toArray();

        return new self($fileDTOs);
    }
}
