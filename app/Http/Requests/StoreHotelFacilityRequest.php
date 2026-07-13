<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'facility_name' => ['required', 'string', 'max:255'],
            'facility_code' => ['required', 'string', 'max:100', 'unique:hotel_facilities,facility_code'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
