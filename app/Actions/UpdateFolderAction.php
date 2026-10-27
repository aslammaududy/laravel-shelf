<?php

namespace App\Actions;

use App\Models\DataObjects\FolderDataObject;
use App\Models\Folder;
use Illuminate\Database\UniqueConstraintViolationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UpdateFolderAction
{
    public function __invoke(FolderDataObject $object, Folder $folder): Folder
    {
        if ($folder->isSystem()) {
            throw new ConflictHttpException('System folder cannot be modified.');
        }

        try {
            $folder->when(is_string($object->name), fn () => $folder->name = $object->name);
            $folder->when($object->is_movable, fn () => $folder->parent_id = $object->parent_id);
            $folder->save();

            return $folder;
        } catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException('Folder already exists.');
        }
    }
}
