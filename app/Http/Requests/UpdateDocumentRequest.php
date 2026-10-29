<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folder_id' => ['sometimes', 'uuid', Rule::exists('folders', 'id')],
            'title' => ['sometimes', 'string', 'max:255'],
            'doc_type' => ['sometimes', Rule::enum(DocumentType::class)],
            'notes' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'document_date' => ['sometimes', 'nullable', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
