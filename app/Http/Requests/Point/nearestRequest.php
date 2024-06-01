<?php

namespace App\Http\Requests\Point;

use Illuminate\Foundation\Http\FormRequest;

class nearestRequest extends FormRequest
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
        ];
    }
}
