<?php

namespace App\Http\Requests;

use App\Models\HotelRoomInventory;
use App\Models\HotelRoomType;
use Illuminate\Support\Carbon;
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
            'hotel_room_type_id' => [
                'required',
                Rule::exists('hotel_room_types', 'id')->where(fn ($query) => $query->where('hotel_id', $this->input('hotel_id'))),
            ],
            'meal_plan_id' => ['nullable', 'exists:hotel_meal_plans,id', 'required_if:include_meal,1'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'infants' => ['required', 'integer', 'min:0'],
            'include_meal' => ['nullable', 'boolean'],
            'include_visa' => ['nullable', 'boolean'],
            'include_transport' => ['nullable', 'boolean'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:40'],
            'passengers' => ['required', 'array'],
            'passengers.*.passenger_type' => ['required', Rule::in(['Adult', 'Child', 'Infant'])],
            'passengers.*.full_name' => ['required', 'string', 'max:255'],
            'passengers.*.date_of_birth' => ['required', 'date', 'before:today'],
           'passengers.*.passport_document' => [
    $this->isReviewRequest() ? 'required' : 'nullable',
    'file',
    'max:5120',
    'mimes:pdf,png,jpg,jpeg,gif',
],

'passengers.*.cnic_document' => [
    $this->isReviewRequest() ? 'required' : 'nullable',
    'file',
    'max:5120',
    'mimes:pdf,png,jpg,jpeg,gif',
],
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

            $hotelRoomType = HotelRoomType::find($this->input('hotel_room_type_id'));
            if (! $hotelRoomType || $hotelRoomType->hotel_id !== (int) $this->input('hotel_id')) {
                $validator->errors()->add('hotel_room_type_id', 'The selected room type is invalid for the chosen hotel.');
                return;
            }

            try {
                $checkIn = Carbon::parse($this->input('check_in'))->startOfDay();
                $checkOut = Carbon::parse($this->input('check_out'))->startOfDay();
            } catch (\Exception $exception) {
                return;
            }

            if ($checkIn->gte($checkOut)) {
                return;
            }

            $bounds = $this->getRoomTypeInventoryBounds($hotelRoomType);

            if ($bounds) {
                $maxBookingCheckOut = $bounds['max']->copy()->addDay();

                if ($checkIn->lt($bounds['min'])) {
                    $validator->errors()->add('check_in', "Check-in must be on or after {$bounds['min']->format('Y-m-d')} for the selected room type.");
                }

                if ($checkOut->gt($maxBookingCheckOut)) {
                    $validator->errors()->add('check_out', "Check-out must be on or before {$maxBookingCheckOut->format('Y-m-d')} for the selected room type.");
                }
            }

            if ($hotelRoomType->availableRoomsForDates($checkIn, $checkOut) < 1) {
                $validator->errors()->add('hotel_room_type_id', 'No available room was found for the selected dates.');
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
            'passengers.*.full_name.required' => 'Each passenger must have a full name.',
            'passengers.*.full_name.string' => 'Full name must be a valid text string.',
            'passengers.*.date_of_birth.required' => 'Each passenger must have a date of birth.',
            'passengers.*.date_of_birth.before' => 'Date of birth must be before today.',
            'passengers.*.passport_document.required' => 'Each passenger must upload a passport document.',
            'passengers.*.passport_document.file' => 'Passport document must be a valid file.',
            'passengers.*.passport_document.max' => 'Passport document must not exceed 5MB.',
            'passengers.*.passport_document.mimes' => 'Passport document must be a PDF, PNG, JPG, JPEG, or GIF file.',
            'passengers.*.cnic_document.required' => 'Each passenger must upload a CNIC/ID card document.',
            'passengers.*.cnic_document.file' => 'CNIC/ID card document must be a valid file.',
            'passengers.*.cnic_document.max' => 'CNIC/ID card document must not exceed 5MB.',
            'passengers.*.cnic_document.mimes' => 'CNIC/ID card document must be a PDF, PNG, JPG, JPEG, or GIF file.',
            'check_out.after' => 'Check-out must be after check-in.',
        ];
    }

    private function getRoomTypeInventoryBounds(HotelRoomType $roomType): ?array
    {
        $inventory = HotelRoomInventory::where('hotel_id', $roomType->hotel_id)
            ->where('hotel_room_type_id', $roomType->id)
            ->where('status', 'Active')
            ->get();

        if ($inventory->isEmpty()) {
            return null;
        }

        $minDate = $inventory->min('inventory_date');
        $maxDate = $inventory
            ->map(fn ($item) => $item->inventory_date_to ? $item->inventory_date_to->format('Y-m-d') : $item->inventory_date->format('Y-m-d'))
            ->max();

        if (! $minDate || ! $maxDate) {
            return null;
        }

        return [
            'min' => Carbon::parse($minDate)->startOfDay(),
            'max' => Carbon::parse($maxDate)->startOfDay(),
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'check_in' => $this->input('check_in') ? now()->parse($this->input('check_in'))->format('Y-m-d') : null,
            'check_out' => $this->input('check_out') ? now()->parse($this->input('check_out'))->format('Y-m-d') : null,
            'include_meal' => $this->boolean('include_meal'),
            'include_visa' => $this->boolean('include_visa'),
            'include_transport' => $this->boolean('include_transport'),
        ]);
    }
    private function isReviewRequest(): bool
{
    return $this->isMethod('POST')
        && $this->routeIs('hotels.book.review');
}
}

