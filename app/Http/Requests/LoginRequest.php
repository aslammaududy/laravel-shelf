<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        if (is_string($this->email)) {
            $this->merge(
                ['email' => Str::of($this->email)->lower()->toString()]
            );
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}
