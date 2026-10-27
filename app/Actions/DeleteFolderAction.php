<?php

namespace App\Actions;

use App\Models\Folder;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class DeleteFolderAction
{
    public function __invoke(Folder $folder): void
    {
        if ($folder->isSystem()) {
            throw new ConflictHttpException('System folder cannot be deleted.');
        }

        if ($folder->hasSubfolders() || $folder->containsDocuments()) {
            throw new ConflictHttpException('Folder is not empty.');
        }

        $folder->delete();
    }
}
