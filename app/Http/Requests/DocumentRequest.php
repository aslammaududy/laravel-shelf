<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folder_id' => ['nullable', 'uuid', Rule::exists('folders', 'id')],
            'title' => ['nullable', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:'.config('document.allowed_types'), 'max:'.config('document.max_upload_size_kb')],
            'doc_type' => ['nullable', Rule::enum(DocumentType::class)],
            'notes' => ['nullable', 'string', 'max:10000'],
            'document_date' => ['nullable', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws \Throwable
     */
    protected function failedValidation(Validator $validator): void
    {
        $failedFileRules = $validator->failed()['file'] ?? [];

        abort_if(isset($failedFileRules['Mimes']), 415);
        abort_if(isset($failedFileRules['Max']), 413);

        parent::failedValidation($validator);
    }
}
