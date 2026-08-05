<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'airline_id' => ['nullable', 'integer', 'exists:airlines,id'],
            'airline' => ['required_without:airline_id', 'nullable', 'string', 'max:255'],

            'route' => ['required', 'string', 'max:255'],
            'flight_number' => ['required', 'string', 'max:255'],

            'departure_time' => ['required', 'string', 'max:255'],
            'arrival_time' => ['required', 'string', 'max:255'],

            // Return timing
            'return_departure_time' => ['nullable', 'string', 'max:255'],
            'return_arrival_time' => ['nullable', 'string', 'max:255'],

            'departure_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:departure_date'],

            'departure_airport_id' => ['nullable', 'integer', 'exists:airports,id'],
            'arrival_airport_id' => ['nullable', 'integer', 'exists:airports,id'],
            'return_departure_airport_id' => ['nullable', 'integer', 'exists:airports,id'],
            'return_arrival_airport_id' => ['nullable', 'integer', 'exists:airports,id'],

            'ticket_type' => ['nullable', 'string', 'max:255'],
            'refundable' => ['nullable', 'boolean'],
            'baggage' => ['nullable', 'string', 'max:255'],
            'meal' => ['nullable', 'string', 'max:255'],

            // Main/general prices
            'price' => ['required', 'numeric', 'min:0'],
            'adult_price' => ['required', 'numeric', 'min:0'],
            'child_price' => ['required', 'numeric', 'min:0'],
            'infant_price' => ['required', 'numeric', 'min:0'],

            // Cabin prices
            'economy_price' => ['nullable', 'numeric', 'min:0'],
            'premium_economy_price' => ['nullable', 'numeric', 'min:0'],
            'business_price' => ['nullable', 'numeric', 'min:0'],
            'first_price' => ['nullable', 'numeric', 'min:0'],

            'tax_rate' => ['nullable', 'numeric', 'between:0,1'],
            'service_charge_rate' => ['nullable', 'numeric', 'between:0,1'],

            'status' => ['required', 'string', 'in:Pending,Approved,Processing,Cancelled'],
            'visibility' => ['required', 'string', 'in:Agent Only,Customer Only,Both'],

            'reference' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tickets', 'reference')->ignore($this->route('ticket')),
            ],

            'total_seats' => ['required', 'integer', 'min:1'],
            'economy_seats' => ['required', 'integer', 'min:0'],
            'premium_economy_seats' => ['required', 'integer', 'min:0'],
            'business_seats' => ['required', 'integer', 'min:0'],
            'first_seats' => ['required', 'integer', 'min:0'],
            'cabin_prices.Economy' => ['required', 'numeric', 'min:0'],
'cabin_prices.Premium Economy' => ['required', 'numeric', 'min:0'],
'cabin_prices.Business' => ['required', 'numeric', 'min:0'],
'cabin_prices.First' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function prepareForValidation(): void
    {
        $cleanMoney = function ($value) {
            if ($value === null || $value === '') {
                return null;
            }

            return str_replace([' ,', ',', 'SAR', ' '], '', $value);
        };

        $this->merge([
            'price' => $cleanMoney($this->input('price')),
            'adult_price' => $cleanMoney($this->input('adult_price')),
            'child_price' => $cleanMoney($this->input('child_price')),
            'infant_price' => $cleanMoney($this->input('infant_price')),

            'economy_price' => $cleanMoney($this->input('economy_price')),
            'premium_economy_price' => $cleanMoney($this->input('premium_economy_price')),
            'business_price' => $cleanMoney($this->input('business_price')),
            'first_price' => $cleanMoney($this->input('first_price')),

            'refundable' => filter_var(
                $this->input('refundable'),
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $economy = (int) $this->input('economy_seats', 0);
            $premiumEconomy = (int) $this->input('premium_economy_seats', 0);
            $business = (int) $this->input('business_seats', 0);
            $first = (int) $this->input('first_seats', 0);
            $totalSeats = (int) $this->input('total_seats', 0);

            if (
                $economy +
                $premiumEconomy +
                $business +
                $first !== $totalSeats
            ) {
                $validator->errors()->add(
                    'total_seats',
                    'The total seats must equal the sum of all class seat counts.'
                );
            }
        });
    }
}