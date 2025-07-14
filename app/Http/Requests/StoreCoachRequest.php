<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCoachRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:coaches,name'],
            'image' => ['required', 'url', 'regex:/^https:\/\/static\.wikia\.nocookie\.net\/.*inazuma.*\/images/'],
            'version' => ['required', Rule::in(['ie1', 'ie2', 'ie3'])]
        ];
    }
}
