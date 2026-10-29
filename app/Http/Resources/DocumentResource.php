<?php

namespace App\Http\Resources;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Document */
class DocumentResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'original_filename' => $this->original_filename,
            'mime_type' => $this->mime_type,
            'size_bytes' => $this->size_bytes,
            'sha256' => $this->sha256,
            'doc_type' => $this->doc_type,
            'document_date' => $this->document_date?->toDateString(),
            'notes' => $this->notes,
            'content_text' => $this->content_text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'folder_id' => $this->folder_id,
            'uploaded_by' => $this->uploadedBy,

            'folder' => new FolderResource($this->whenLoaded('folder')),
        ];
    }
}
