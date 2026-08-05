<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'hotel_name' => ['required', 'string', 'max:255'],
            'hotel_code' => ['required', 'string', 'max:100', 'unique:hotels,hotel_code'],
            'city' => ['required', 'string', 'max:100', 'in:Makkah,Madina'],
            'category' => ['required', 'in:3 Star,4 Star,5 Star'],
            'distance_from_haram' => ['nullable', 'numeric', 'between:0,999.99'],
            'address' => ['required', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string', 'max:2000'],
            'about' => ['nullable', 'string', 'max:2000'],
            'stay_policy_free_cancellation' => ['nullable', 'string', 'max:2000'],
            'stay_policy_haram_shuttle' => ['nullable', 'string', 'max:2000'],
            'stay_policy_flexible_checkin' => ['nullable', 'string', 'max:2000'],
            'stay_policy_inclusive_breakfast' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:Active,Inactive'],
            'visibility' => ['required', 'in:Both,Agent Only,Customer Only'],
            'featured' => ['boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'cover_image_id' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'array'],
            'is_active.*' => ['integer', 'exists:hotel_images,id'],
            'existing_image_order' => ['nullable', 'array'],
            'existing_image_order.*' => ['integer'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer', 'exists:hotel_images,id'],
        ];
    }
}
