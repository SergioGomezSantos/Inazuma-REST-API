<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlayerRequest extends FormRequest
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
                'stats.*.player_id' => ['required', 'exists:players,id'],

                // Techniques with N:M
                'techniques' => ['required', 'array'],
                'techniques.*.id' => ['required', 'exists:techniques,id'],
                'techniques.*.source' => ['string', Rule::in(['anime1', 'anime2', 'anime3', 'ie1', 'ie2', 'ie3'])],
                'techniques.*.with' => ['nullable', 'json'],
            ];
        } else {

            return [
                'name' => ['nullable', 'string', 'max:255'],
                'fullName' => ['nullable', 'string', 'max:255'],
                'position' => ['nullable', Rule::in(['Portero', 'Defensa', 'Centrocampista', 'Delantero'])],
                'element' => ['nullable', Rule::in(['Montaña', 'Aire', 'Bosque', 'Fuego'])],
                'originalTeam' => ['nullable', 'string', 'max:255'],
                'image' => [
                    'nullable',
                    'url',
                    'regex:/^https:\/\/static\.wikia\.nocookie\.net\/.*inazuma.*\/images/'
                ],

                // Stats with 1:N
                'stats' => ['nullable', 'array'],
                'stats.*.GP' => ['nullable', 'integer', 'min:0'],
                'stats.*.TP' => ['nullable', 'integer', 'min:0'],
                'stats.*.kick' => ['nullable', 'integer', 'min:0'],
                'stats.*.body' => ['nullable', 'integer', 'min:0'],
                'stats.*.control' => ['nullable', 'integer', 'min:0'],
                'stats.*.guard' => ['nullable', 'integer', 'min:0'],
                'stats.*.speed' => ['nullable', 'integer', 'min:0'],
                'stats.*.stamina' => ['nullable', 'integer', 'min:0'],
                'stats.*.guts' => ['nullable', 'integer', 'min:0'],
                'stats.*.freedom' => ['nullable', 'integer', 'min:0'],
                'stats.*.version' => ['nullable', 'string', Rule::in(['ie1', 'ie2', 'ie3'])],
                'stats.*.player_id' => ['nullable', 'exists:players,id'],

                // Techniques with N:M
                'techniques' => ['nullable', 'array'],
                'techniques.*.id' => ['required_with:techniques', 'exists:techniques,id'],
                'techniques.*.source' => ['nullable', 'string', Rule::in(['anime1', 'anime2', 'anime3', 'ie1', 'ie2', 'ie3'])],
                'techniques.*.with' => ['nullable', 'json'],
            ];
        }
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
