<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
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
                'name' => ['required', 'string'],
                'formationId' => ['required', 'exists:formations,id'],
                'emblemId' => ['required', 'exists:emblems,id'],
                'coachId' => ['required', 'exists:coaches,id'],

                'players' => ['required', 'array'],
                'players.*.player_id' => ['required', 'exists:players,id'],
                'players.*.position' => ['required', 'string', 'regex:/^(pos-(0|1|2|3|4|5|6|7|8|9|10)|bench-(0|1|2|3|4))$/'],
            ];
        } else {

            return [
                'name' => ['nullable', 'string'],
                'formationId' => ['nullable', 'exists:formations,id'],
                'emblemId' => ['nullable', 'exists:emblems,id'],
                'coachId' => ['nullable', 'exists:coaches,id'],

                'players' => ['nullable', 'array'],
                'players.*.player_id' => ['nullable', 'exists:players,id'],
                'players.*.position' => ['nullable', 'string', 'regex:/^(pos-(0|1|2|3|4|5|6|7|8|9|10)|bench-(0|1|2|3|4))$/'],
            ];
        }
    }

    protected function prepareForValidation()
    {
        if ($this->formationId) {
            $this->merge([
                'formation_id' => $this->formationId
            ]);
        }

        if ($this->emblemId) {
            $this->merge([
                'emblem_id' => $this->emblemId
            ]);
        }

        if ($this->coachId) {
            $this->merge([
                'coach_id' => $this->coachId
            ]);
        }

        if ($this->userId) {
            $this->merge([
                'user_id' => $this->userId
            ]);
        }
    }
}
