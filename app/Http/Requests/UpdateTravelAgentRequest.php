<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $agentId = $this->route('agent')->id;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:travel_agents,email,'.$agentId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'mobile' => ['required', 'string', 'max:30'],
            'company_address' => ['required', 'string', 'max:1000'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'dts_license' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'cnic_front' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'cnic_back' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
