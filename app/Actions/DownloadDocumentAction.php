<?php

namespace App\Actions;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadDocumentAction
{
    public function __invoke(Document $document): StreamedResponse
    {
        abort_if(Storage::fileMissing($document->storage_key), 404);

        return Storage::download(
            $document->storage_key,
            $document->original_filename,
            ['Content-Type' => $document->mime_type]
        );
    }
}
