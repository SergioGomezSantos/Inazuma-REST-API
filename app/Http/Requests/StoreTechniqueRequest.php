<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTechniqueRequest extends FormRequest
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
        return [
            'name' => ["required", "string", "unique:techniques,name"],
            'element' => [
                'nullable', 
                Rule::in(['Aire', 'Bosque', 'Fuego', 'Montaña', 'Neutro']),
                function ($attribute, $value, $fail) {
                    if ($this->input('type') !== 'Talento' && $value !== null) {
                        $fail('The "element" field can only be null if "type" is Talento.');
                    }
                }
            ],
            'type' => ["required", Rule::in(['Tiro', 'Regate', 'Bloqueo', 'Atajo', 'Talento'])]
        ];
    }
}
