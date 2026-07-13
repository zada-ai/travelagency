<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHotelFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $facilityId = $this->route('hotel_facility')->id;

        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'facility_name' => ['required', 'string', 'max:255'],
            'facility_code' => ['required', 'string', 'max:100', Rule::unique('hotel_facilities', 'facility_code')->ignore($facilityId)],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
