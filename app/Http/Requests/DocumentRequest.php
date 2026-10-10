<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_id' => ['required', 'exists:folders'],
            'title' => ['required'],
            'original_filename' => ['required'],
            'mime_type' => ['required'],
            'size_bytes' => ['required', 'integer'],
            'sha256' => ['required'],
            'storage_key' => ['required'],
            'doc_type' => ['required'],
            'document_date' => ['required', 'date'],
            'notes' => ['nullable'],
            'content_text' => ['nullable'],
            'search_vector' => ['required', 'integer'],
            'uploaded_by' => ['required', 'exists:users'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
