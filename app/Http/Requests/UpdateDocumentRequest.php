<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Validation\Validator;
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
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function after()
    {
        return [
            function (Validator $validator) {
                $allowed = array_keys($this->rules());
                $unexpected = array_diff(array_keys($this->all()), $allowed);

                foreach ($unexpected as $field) {
                    $validator->errors()->add($field, "The field '{$field}' is not recognized.");
                }
            }
        ];
    }
}
