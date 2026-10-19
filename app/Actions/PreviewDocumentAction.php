<?php

namespace App\Actions;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreviewDocumentAction
{
    public function __invoke(Document $document): StreamedResponse
    {
        abort_if(! $document->isPreviewable(), 415);
        abort_if(Storage::fileMissing($document->storage_key), 404);

        return Storage::response(
            $document->storage_key,
            $document->original_filename,
            ['Content-Type' => $document->mime_type]
        );
    }
}
