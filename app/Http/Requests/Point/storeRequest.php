<?php

namespace App\Http\Requests\Point;

use Illuminate\Foundation\Http\FormRequest;

class storeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'category_id' => 'required|string|in:pubs,at_home,cinema,sabantui,sports',
            'name' => 'required|string',
            'description' => 'string',
            'preferable_gender' => 'boolean',
            'starts_at' => 'integer',
            'min_preferable_age' => 'integer|between:16,100',
            'max_preferable_age' => 'integer|between:16,100',
        ];
    }
}
