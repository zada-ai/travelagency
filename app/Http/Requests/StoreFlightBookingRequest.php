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
            'cabin_class' => ['required', 'string', 'in:Economy,Premium Economy,Business,First'],
            
            // --- NEW ADDONS RULES ---
            'include_visa' => ['nullable', 'boolean'],
            'include_transport' => ['nullable', 'boolean'],
            // ------------------------

            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.full_name' => ['required', 'string', 'max:255'],
            'passengers.*.date_of_birth' => ['required', 'date'],
            'passengers.*.passport_upload' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'passengers.*.cnic_upload' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
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

    public function prepareForValidation(): void
    {
        $this->merge([
            'include_visa' => $this->boolean('include_visa'),
            'include_transport' => $this->boolean('include_transport'),
        ]);
    }
}
