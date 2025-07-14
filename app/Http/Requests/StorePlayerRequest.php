<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlayerRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'fullName' => ['required', 'string', 'max:255'],
            'position' => ['required', Rule::in(['Portero', 'Defensa', 'Centrocampista', 'Delantero'])],
            'element' => ['required', Rule::in(['Montaña', 'Aire', 'Bosque', 'Fuego'])],
            'originalTeam' => ['required', 'string', 'max:255'],
            'image' => [
                'required',
                'url',
                'regex:/^https:\/\/static\.wikia\.nocookie\.net\/.*inazuma.*\/images/'
            ],

            // Stats with 1:N
            'stats' => ['required', 'array'],
            'stats.*.GP' => ['required', 'integer', 'min:0'],
            'stats.*.TP' => ['required', 'integer', 'min:0'],
            'stats.*.kick' => ['required', 'integer', 'min:0'],
            'stats.*.body' => ['required', 'integer', 'min:0'],
            'stats.*.control' => ['required', 'integer', 'min:0'],
            'stats.*.guard' => ['required', 'integer', 'min:0'],
            'stats.*.speed' => ['required', 'integer', 'min:0'],
            'stats.*.stamina' => ['required', 'integer', 'min:0'],
            'stats.*.guts' => ['required', 'integer', 'min:0'],
            'stats.*.freedom' => ['required', 'integer', 'min:0'],
            'stats.*.version' => ['required', 'string', Rule::in(['ie1', 'ie2', 'ie3'])],

            // Techniques with N:M
            'techniques' => ['required', 'array'],
            'techniques.*' => ['exists:techniques,id'],
            'techniques.*.source' => ['string', Rule::in(['anime1', 'anime2', 'anime3', 'ie1', 'ie2', 'ie3'])],
            'techniques.*.with' => ['nullable', 'json'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->fullName) {
            $this->merge([
                'full_name' => $this->fullName
            ]);
        }

        if ($this->originalTeam) {
            $this->merge([
                'original_team' => $this->originalTeam
            ]);
        }
    }
}
