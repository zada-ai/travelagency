<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAirlineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', Rule::unique('airlines', 'code')->ignore($this->route('airline'))],
            'country' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:Active,Inactive'],
        ];
    }
}
