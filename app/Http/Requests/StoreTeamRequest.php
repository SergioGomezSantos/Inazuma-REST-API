<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'formationId' => ['required', 'exists:formation,id'],
            'emblemId' => ['required', 'exists:emblem,id'],
            'coachId' => ['required', 'exists:coach,id'],
            'userId' => ['required', 'exists:user,id'],

            'players' => ['required', 'array'],
            'players.*.player_id' => ['required', 'exists:players,id'],
            'players.*.position' => ['required', 'string', 'regex:/^(pos-(0|1|2|3|4|5|6|7|8|9|10)|bench-(0|1|2|3|4))$/'],
        ];
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
