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
            'airline' => ['required', 'string', 'max:255'],
            'route' => ['required', 'string', 'max:255'],
            'flight_number' => ['required', 'string', 'max:255'],
            'departure_time' => ['required', 'string', 'max:255'],
            'arrival_time' => ['required', 'string', 'max:255'],
            'departure_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date'],
            'baggage' => ['nullable', 'string', 'max:255'],
            'meal' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'adult_price' => ['required', 'numeric', 'min:0'],
            'child_price' => ['required', 'numeric', 'min:0'],
            'infant_price' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'between:0,1'],
            'service_charge_rate' => ['nullable', 'numeric', 'between:0,1'],
            'status' => ['required', 'string', 'in:Pending,Approved,Processing,Cancelled'],
            'reference' => ['required', 'string', 'max:255', Rule::unique('tickets', 'reference')->ignore($this->route('ticket'))],
            'client' => ['nullable', 'string', 'max:255'],
            'total_seats' => ['required', 'integer', 'min:1'],
            'economy_seats' => ['required', 'integer', 'min:0'],
            'premium_economy_seats' => ['required', 'integer', 'min:0'],
            'business_seats' => ['required', 'integer', 'min:0'],
            'first_seats' => ['required', 'integer', 'min:0'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'price' => str_replace([',', 'SAR', ' '], '', $this->price),
            'adult_price' => str_replace([',', 'SAR', ' '], '', $this->adult_price),
            'child_price' => str_replace([',', 'SAR', ' '], '', $this->child_price),
            'infant_price' => str_replace([',', 'SAR', ' '], '', $this->infant_price),
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

            if ($economy + $premiumEconomy + $business + $first !== $totalSeats) {
                $validator->errors()->add('total_seats', 'The total seats must equal the sum of all class seat counts.');
            }
        });
    }
}
