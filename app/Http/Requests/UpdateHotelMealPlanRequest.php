<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHotelMealPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $mealPlanId = $this->route('hotel_meal_plan')->id;

        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'meal_plan_name' => ['required', 'string', 'max:255'],
            'meal_plan_code' => ['required', 'string', 'max:100', Rule::unique('hotel_meal_plans', 'meal_plan_code')->ignore($mealPlanId)],
            'description' => ['nullable', 'string'],
            'price_per_person' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
