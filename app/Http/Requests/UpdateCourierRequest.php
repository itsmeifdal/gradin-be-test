<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('couriers', 'phone')->ignore($this->route('courier')),
            ],
            'level' => ['required', 'integer', 'between:1,5'],
            'registered_at' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
