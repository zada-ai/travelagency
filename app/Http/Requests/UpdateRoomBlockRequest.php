<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'hotel_room_id' => ['required', 'exists:hotel_rooms,id'],
            'block_from' => ['required', 'date', 'after_or_equal:today'],
            'block_to' => ['required', 'date', 'after:block_from'],
            'reason' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Active', 'Inactive'])],
            'notes' => ['nullable', 'string'],
        ];
    }
}
