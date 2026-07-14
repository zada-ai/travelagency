<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
            'meal_plan_id' => ['nullable', 'exists:hotel_meal_plans,id', 'required_if:include_meal,1'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after_or_equal:check_in'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'infants' => ['required', 'integer', 'min:0'],
            'include_meal' => ['nullable', 'boolean'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:40'],
            'passengers' => ['required', 'array'],
            'passengers.*.passenger_type' => ['required', Rule::in(['Adult', 'Child', 'Infant'])],
            'passengers.*.first_name' => ['required', 'string', 'max:255'],
            'passengers.*.last_name' => ['required', 'string', 'max:255'],
            'passengers.*.date_of_birth' => ['required', 'date', 'before:today'],
            'passengers.*.passport_number' => ['required', 'string', 'max:100', 'distinct'],
            'passengers.*.passport_expiry' => ['required', 'date', 'after_or_equal:today'],
            'passengers.*.nationality' => ['required', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $passengers = $this->input('passengers', []);
            $expectedCounts = [
                'Adult' => (int) $this->input('adults', 0),
                'Child' => (int) $this->input('children', 0),
                'Infant' => (int) $this->input('infants', 0),
            ];
            $actualCounts = ['Adult' => 0, 'Child' => 0, 'Infant' => 0];
            $today = Carbon::today();

            foreach ($passengers as $index => $passenger) {
                $type = $passenger['passenger_type'] ?? null;
                if (! in_array($type, ['Adult', 'Child', 'Infant'], true)) {
                    continue;
                }

                $actualCounts[$type]++;

                if (empty($passenger['date_of_birth'])) {
                    continue;
                }

                try {
                    $dob = Carbon::parse($passenger['date_of_birth'])->startOfDay();
                } catch (\Exception $exception) {
                    $validator->errors()->add("passengers.{$index}.date_of_birth", 'The date of birth must be a valid date.');
                    continue;
                }

                if ($dob->isFuture()) {
                    $validator->errors()->add("passengers.{$index}.date_of_birth", 'Date of birth cannot be in the future.');
                    continue;
                }

                $years = $dob->diffInYears($today);

                if ($type === 'Adult' && $years < 12) {
                    $validator->errors()->add("passengers.{$index}.date_of_birth", 'Adult passengers must be 12 years or older.');
                }

                if ($type === 'Child' && ($years < 2 || $years > 11)) {
                    $validator->errors()->add("passengers.{$index}.date_of_birth", 'Child passengers must be between 2 and 11 years old.');
                }

                if ($type === 'Infant' && $years >= 2) {
                    $validator->errors()->add("passengers.{$index}.date_of_birth", 'Infant passengers must be under 2 years old.');
                }
            }

            foreach ($expectedCounts as $passengerType => $expected) {
                if ($actualCounts[$passengerType] !== $expected) {
                    $validator->errors()->add('passengers', "Passenger count mismatch: expected {$expected} {$passengerType} passenger(s). Please update passenger details.");
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'passengers.required' => 'Passenger details are required for the booking.',
            'passengers.array' => 'Passenger details must be provided in the correct format.',
            'passengers.*.passenger_type.required' => 'Each passenger must select a passenger type.',
            'passengers.*.passenger_type.in' => 'Each passenger type must be Adult, Child, or Infant.',
            'passengers.*.first_name.required' => 'Each passenger must have a first name.',
            'passengers.*.last_name.required' => 'Each passenger must have a last name.',
            'passengers.*.date_of_birth.required' => 'Each passenger must have a date of birth.',
            'passengers.*.date_of_birth.before' => 'Date of birth must be before today.',
            'passengers.*.passport_number.required' => 'Each passenger must have a passport number.',
            'passengers.*.passport_number.distinct' => 'Each passenger passport number must be unique within this booking.',
            'passengers.*.passport_expiry.required' => 'Each passenger must have a passport expiry date.',
            'passengers.*.passport_expiry.after_or_equal' => 'Passport expiry date cannot be in the past.',
            'passengers.*.nationality.required' => 'Each passenger must have a nationality.',
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
