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
        return true;
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
                        $type = $this->input('type');

                        if ($type === 'Talento' && $value !== null) {
                            $fail('Talents cannot have an element');
                        }

                        if ($type !== 'Talento' && $value === null) {
                            $fail('Non-talent techniques require an element');
                        }
                    }
                ],
                'type' => ['sometimes', Rule::in(['Tiro', 'Regate', 'Bloqueo', 'Atajo', 'Talento'])]
            ];
        } else {

            return [
                'name' => ['nullable', 'string', 'unique:techniques,name'],
                'element' => [
                    'nullable',
                    Rule::in(['Aire', 'Bosque', 'Fuego', 'Montaña', 'Neutro']),
                    function ($attribute, $value, $fail) {
                        $type = $this->input('type');

                        if ($type === 'Talento' && $value !== null) {
                            $fail('Talents cannot have an element');
                        }

                        if ($type !== 'Talento' && $value === null) {
                            $fail('Non-talent techniques require an element');
                        }
                    }
                ],
                'type' => ['sometimes', Rule::in(['Tiro', 'Regate', 'Bloqueo', 'Atajo', 'Talento'])]
            ];
        }
    }
}
