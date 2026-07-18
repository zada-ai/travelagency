<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlightBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'adults' => ['required', 'integer', 'min:0'],
            'children' => ['required', 'integer', 'min:0'],
            'infants' => ['required', 'integer', 'min:0'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:255'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:Pending,Confirmed,Cancelled'],
            'payment_status' => ['required', 'in:Paid,Unpaid'],
            'cabin_class' => ['nullable', 'string', 'max:255'],
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.first_name' => ['required', 'string', 'max:255'],
            'passengers.*.last_name' => ['required', 'string', 'max:255'],
            'passengers.*.gender' => ['required', 'string', 'in:Male,Female,Other'],
            'passengers.*.date_of_birth' => ['required', 'date', 'before:today'],
            'passengers.*.nationality' => ['required', 'string', 'max:255'],
            'passengers.*.passport_number' => ['required', 'string', 'max:255'],
            'passengers.*.passport_expiry' => ['required', 'date', 'after:today'],
            'passengers.*.passenger_type' => ['required', 'string', 'in:Adult,Child,Infant'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $expected = (int) $this->input('adults', 0)
                + (int) $this->input('children', 0)
                + (int) $this->input('infants', 0);

            $passengers = $this->input('passengers', []);
            if (count($passengers) !== $expected) {
                $validator->errors()->add('passengers', 'Passenger count does not match the selected adult, child and infant totals.');
            }

            foreach ($passengers as $index => $passenger) {
                if (isset($passenger['date_of_birth'], $passenger['passenger_type'])) {
                    $birthDate = strtotime($passenger['date_of_birth']);
                    if ($birthDate === false) {
                        continue;
                    }

                    $age = floor((time() - $birthDate) / 31556952);
                    if ($passenger['passenger_type'] === 'Infant' && $age >= 2) {
                        $validator->errors()->add(
                            "passengers.{$index}.passenger_type",
                            'Infant passengers must be younger than 2 years old.'
                        );
                    }

                    if ($passenger['passenger_type'] === 'Child' && $age >= 12) {
                        $validator->errors()->add(
                            "passengers.{$index}.passenger_type",
                            'Child passengers must be younger than 12 years old.'
                        );
                    }
                }
            }
        });
    }
}
