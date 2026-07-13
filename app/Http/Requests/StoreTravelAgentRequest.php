<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTravelAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:travel_agents,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mobile' => ['required', 'string', 'max:30'],
            'company_address' => ['required', 'string', 'max:1000'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'company_logo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'dts_license' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'cnic_front' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'cnic_back' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'terms' => ['required', 'accepted'],
        ];
    }
}
