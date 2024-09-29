<?php

namespace App\Models\traits;

use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * SoftDeletes trait that also updates the deletedBy column
 */
trait SoftDeletesWithAuthor
{
    use SoftDeletes {runSoftDelete as private parentRunSoftDelete; restore as private parentRestore; }


    /**
     * Perform the actual delete query on this model instance.
     */
    protected function runSoftDelete(): void
    {
        // Наглая копипаста оригинального метода,
        // т.к. по другому мы не можем задать переменную $columns для обновления
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $time = $this->freshTimestamp();

        $columns = [
            'deleted_at' => $this->fromDateTime($time),
            'deleted_by' => Auth::user()->id
        ];

        $this->deleted_at = $time;

        if ($this->usesTimestamps() && ! is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);

        $this->syncOriginalAttributes(array_keys($columns));

        $this->fireModelEvent('trashed', false);
    }

    /**
     * Restore a soft-deleted model instance.
     */
    public function restore(): bool
    {
        $deleted_by = $this->deleted_by;
        $this->deleted_by = null;
        if ($this->parentRestore())
            return true;
        $this->deleted_by = $deleted_by;
        return false;
    }
}
