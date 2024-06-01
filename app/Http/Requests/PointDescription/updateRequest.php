<?php

namespace App\Http\Requests\PointDescription;

use Illuminate\Foundation\Http\FormRequest;

class updateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'preferable_gender' => 'nullable|boolean',
            'starts_at' => 'nullable|integer',
            'max_preferable_age' => 'nullable|integer|between:16,100',
            'min_preferable_age' => 'nullable|integer|between:16,100',
            'description' => 'nullable|string',
            'name' => 'string',
        ];
    }
}
