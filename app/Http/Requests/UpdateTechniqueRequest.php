<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTechniqueRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:techniques,name'],
            'element' => [
                'nullable', 
                Rule::in(['Aire', 'Bosque', 'Fuego', 'Montaña', 'Neutro']),
                function ($attribute, $value, $fail) {
                    if ($this->input('type') !== 'Talento' && $value !== null) {
                        $fail('The "element" field can only be null if "type" is Talento.');
                    }
                }
            ],
            'type' => ['required', Rule::in(['Aire', 'Bosque', 'Fuego', 'Montaña', 'Neutro'])]
            ];

        } else {

            return [
            'name' => ['nullable', 'string', 'unique:techniques,name'],
            'element' => [
                'nullable', 
                Rule::in(['Aire', 'Bosque', 'Fuego', 'Montaña', 'Neutro']),
                function ($attribute, $value, $fail) {
                    if ($this->input('type') !== 'Talento' && $value !== null) {
                        $fail('The "element" field can only be null if "type" is Talento.');
                    }
                }
            ],
            'type' => ['nullable', Rule::in(['Aire', 'Bosque', 'Fuego', 'Montaña', 'Neutro'])]
            ];
        }
    }
}
