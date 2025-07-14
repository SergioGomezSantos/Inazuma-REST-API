<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->method == 'PUT') {

            return [
                'name' => ['required', 'string', 'unique:formations,name'],
                'layout' => ['required', 'regex:/^\d{1,2}(-\d{1,2}){2,3}$/'],
            ];
        } else {

            return [
                'name' => ['nullable', 'string', 'unique:formations,name'],
                'layout' => ['nullable', 'regex:/^\d{1,2}(-\d{1,2}){2,3}$/']
            ];
        }
    }
}
