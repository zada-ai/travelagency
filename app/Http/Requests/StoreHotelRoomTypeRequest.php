<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'room_name' => ['required', 'string', 'max:255'],
            'room_code' => ['required', 'string', 'max:100', 'unique:hotel_room_types,room_code'],
            'max_occupancy' => ['required', 'integer', 'between:1,10'],
            'total_rooms' => ['required', 'integer', 'min:0'],
            'available_rooms' => ['required', 'integer', 'min:0', 'lte:total_rooms'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'extra_bed_price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
