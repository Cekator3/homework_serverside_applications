<?php

namespace App\DTO\Files;

class FileDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public string $format,
        public int $size,
    ) {}

    public static function fromOrm(\App\Models\File $file): self
    {
        return new self(
            id: $file->id,
            name: $file->name,
            description: $file->description,
            format: $file->format,
            size: $file->size,
        );
    }
}
