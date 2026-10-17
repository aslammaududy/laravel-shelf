<?php

namespace App\Actions;

use App\Models\DataObjects\DocumentDataObject;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class UploadDocumentAction
{
    /**
     * @throws \Throwable
     */
    public function __invoke(DocumentDataObject $data, UploadedFile $file): Document
    {
        $uploadedFileName = Str::uuid7()->toString();

        $storageKey = $file->storeAs('documents/'.$data->uploaded_by, $uploadedFileName.'.'.$file->extension());

        throw_if($storageKey === false, RuntimeException::class, 'File write failed.');

        try {
            return Document::create([...$data->toArray(), 'storage_key' => $storageKey]);
        } catch (\Throwable $throwable) {
            Storage::delete($storageKey);

            throw $throwable;
        }
    }
}
