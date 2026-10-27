<?php

namespace App\Models\DataObjects;

use App\Enums\DocumentType;
use App\Http\Requests\DocumentRequest;
use App\Traits\Arrayable;

final class DocumentDataObject
{
    use Arrayable;

    private const string DEFAULT_FOLDER_ID = '01a13e2c-4342-704f-b55b-e7c90248e3cd';

    public function __construct(
        public string $folder_id,
        public string $title,
        public string $original_filename,
        public string $mime_type,
        public int $size_bytes,
        public string $sha256,
        public string $doc_type,
        public ?string $document_date,
        public ?string $notes,
        public string $uploaded_by,
    ) {}

    public static function fromRequest(DocumentRequest $request): DocumentDataObject
    {
        return new self(
            folder_id: $request->folder_id ?? self::DEFAULT_FOLDER_ID,
            title: $request->title ?? pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME),
            original_filename: $request->file('file')->getClientOriginalName(),
            mime_type: $request->file('file')->getMimeType(),
            size_bytes: $request->file('file')->getSize(),
            sha256: hash_file('sha256', $request->file('file')->getRealPath()),
            doc_type: $request->doc_type ?? DocumentType::GENERAL->value,
            document_date: $request->document_date,
            notes: $request->notes,
            uploaded_by: $request->user()->id,
        );
    }
}
