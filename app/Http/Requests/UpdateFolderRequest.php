<?php

namespace App\Http\Requests;

use App\Models\Folder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateFolderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'uuid', Rule::exists('folders', 'id')],
            'name' => [Rule::requiredIf(! $this->has('parent_id')), 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->has('parent_id')) {
                    return;
                }

                $movingId = $this->route('folder')->id;
                $current = Folder::find($this->input('parent_id'));

                while ($current !== null) {
                    if ($current->id === $movingId) {
                        $validator->errors()->add('parent_id', 'Folder cannot be moved to itself or its subfolder');

                        return;
                    }

                    $current = $current->parent;
                }
            },
        ];
    }
}
