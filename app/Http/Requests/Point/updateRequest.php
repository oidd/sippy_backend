<?php

namespace App\Http\Requests\Point;

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
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'category_id' => 'nullable|string|in:pubs,at_home,cinema,sabantui,sports',
        ];
    }
}
