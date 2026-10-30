<?php

namespace App\Actions;

use App\Models\Document;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateDocumentAction
{
    /**
     * @throws \Throwable
     */
    public function __invoke(array $validatedInput, Document $document): Document
    {
        DB::transaction(function () use ($validatedInput, $document) {
            $document->update(Arr::except($validatedInput, ['tags']));

            if (isset($validatedInput['tags'])) {
                $document->tags()->sync($validatedInput['tags']);
            }
        });

        return $document;
    }
}
