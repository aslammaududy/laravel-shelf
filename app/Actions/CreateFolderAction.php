<?php

namespace App\Actions;

use App\Models\DataObjects\FolderDataObject;
use App\Models\Folder;
use Illuminate\Database\UniqueConstraintViolationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CreateFolderAction
{
    public function __invoke(FolderDataObject $object): Folder
    {
        try {
            return Folder::create($object->toArray());
        } catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException('Folder already exists.');
        }
    }
}
