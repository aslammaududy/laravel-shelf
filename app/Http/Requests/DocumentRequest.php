<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    private const int MAX_UPLOAD_SIZE_KB = 102400;

    private const string ALLOWED_TYPES = 'pdf,jpeg,png,webp,tiff,docx,xlsx';

    public function rules(): array
    {
        return [
            'folder_id' => ['nullable', 'uuid', Rule::exists('folders', 'id')],
            'title' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:'.self::ALLOWED_TYPES, 'max:'.self::MAX_UPLOAD_SIZE_KB],
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
