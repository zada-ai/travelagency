<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'hotel_room_type_id' => ['required', 'exists:hotel_room_types,id'],
            'meal_plan_id' => ['nullable', 'exists:hotel_meal_plans,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'infants' => ['required', 'integer', 'min:0'],
            'include_meal' => ['nullable', Rule::in(['on', 'off', 1, 0])],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:40'],
            'passengers' => ['required_if:include_meal,1', 'array'],
            'passengers.*.passenger_type' => ['required_with:passengers.*.first_name', Rule::in(['Adult', 'Child', 'Infant'])],
            'passengers.*.first_name' => ['required_with:passengers.*.passenger_type', 'string', 'max:255'],
            'passengers.*.last_name' => ['nullable', 'string', 'max:255'],
            'passengers.*.age' => ['required_with:passengers.*.passenger_type', 'integer', 'min:0', 'max:120'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'check_in' => $this->input('check_in') ? now()->parse($this->input('check_in'))->format('Y-m-d') : null,
            'check_out' => $this->input('check_out') ? now()->parse($this->input('check_out'))->format('Y-m-d') : null,
            'include_meal' => $this->boolean('include_meal'),
        ]);
    }
}
