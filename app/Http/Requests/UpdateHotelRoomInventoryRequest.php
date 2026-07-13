<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRoomInventoryRequest extends FormRequest
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
            'inventory_date' => ['required', 'date'],
            'total_rooms' => ['required', 'integer', 'min:0'],
            'available_rooms' => ['required', 'integer', 'min:0'],
            'booked_rooms' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
