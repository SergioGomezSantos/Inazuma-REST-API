<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatRequest extends FormRequest
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
                'playerId' => ['required', 'exists:player,id'],
                'GP' => ['required', 'integer', 'min:0'],
                'TP' => ['required', 'integer', 'min:0'],
                'kick' => ['required', 'integer', 'min:0'],
                'body' => ['required', 'integer', 'min:0'],
                'control' => ['required', 'integer', 'min:0'],
                'guard' => ['required', 'integer', 'min:0'],
                'speed' => ['required', 'integer', 'min:0'],
                'stamina' => ['required', 'integer', 'min:0'],
                'guts' => ['required', 'integer', 'min:0'],
                'freedom' => ['required', 'integer', 'min:0'],
                'version' => ['required', 'string', Rule::in(['ie1', 'ie2', 'ie3'])],
            ];
        } else {

            return [
                'playerId' => ['nullable', 'exists:player,id'],
                'GP' => ['nullable', 'integer', 'min:0'],
                'TP' => ['nullable', 'integer', 'min:0'],
                'kick' => ['nullable', 'integer', 'min:0'],
                'body' => ['nullable', 'integer', 'min:0'],
                'control' => ['nullable', 'integer', 'min:0'],
                'guard' => ['nullable', 'integer', 'min:0'],
                'speed' => ['nullable', 'integer', 'min:0'],
                'stamina' => ['nullable', 'integer', 'min:0'],
                'guts' => ['nullable', 'integer', 'min:0'],
                'freedom' => ['nullable', 'integer', 'min:0'],
                'version' => ['nullable', 'string', Rule::in(['ie1', 'ie2', 'ie3'])],
            ];
        }
    }

    protected function prepareForValidation()
    {
        if ($this->playerId) {
            $this->merge([
                'player_id' => $this->playerId
            ]);
        }
    }
}
