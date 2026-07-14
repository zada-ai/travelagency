<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRoomInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'hotel_room_type_id' => ['required', 'exists:hotel_room_types,id'],
            'inventory_date' => ['nullable', 'required_without:inventory_date_from', 'date'],
            'inventory_date_from' => ['required_without:inventory_date', 'date'],
            'inventory_date_to' => ['nullable', 'date', 'after_or_equal:inventory_date_from'],
            'total_rooms' => ['required', 'integer', 'min:0'],
            'available_rooms' => ['required', 'integer', 'min:0'],
            'booked_rooms' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
