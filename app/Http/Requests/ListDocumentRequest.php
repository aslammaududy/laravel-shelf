<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'string', Rule::in(['created_at', '-created_at', 'title', '-title', 'document_date', '-document_date'])],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
