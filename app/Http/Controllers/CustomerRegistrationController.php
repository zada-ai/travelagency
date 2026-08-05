<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TravelAgent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomerRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.registration');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'mobile_number' => ['required', 'string', 'max:255', Rule::unique('customers', 'phone')],
            'password' => ['required', 'confirmed', 'min:8'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'whatsapp_number' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'cnic' => ['nullable', 'string', 'max:255', Rule::unique('customers', 'cnic')],
            'passport_number' => ['nullable', 'string', 'max:255', Rule::unique('customers', 'passport_no')],
            'passport_expiry' => ['nullable', 'date'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'relationship' => ['nullable', 'string', 'max:255'],
            'emergency_contact_number' => ['nullable', 'string', 'max:255'],
            'agent_reference' => ['nullable', 'email', 'exists:travel_agents,email'],
            'terms' => ['accepted'],
            'privacy_policy' => ['accepted'],
        ]);

        $agent = null;
        if (! empty($validated['agent_reference'])) {
            $agent = TravelAgent::where('email', $validated['agent_reference'])->first();
        }

        $nameParts = preg_split('/\s+/', trim((string) $validated['name']), 2) ?: [(string) $validated['name']];
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? null;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->assignRole('Customer');

        $customerCode = $this->generateCustomerCode();

        Customer::create([
            'user_id' => $user->id,
            'travel_agent_id' => $agent?->id,
            'customer_code' => $customerCode,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $validated['mobile_number'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'cnic' => $validated['cnic'] ?? null,
            'passport_no' => $validated['passport_number'] ?? null,
            'passport_expiry' => $validated['passport_expiry'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'relationship' => $validated['relationship'] ?? null,
            'emergency_contact_number' => $validated['emergency_contact_number'] ?? null,
            'status' => 'active',
        ]);

        Auth::guard('web')->login($user);

        return redirect()->route('customer.dashboard');
    }

    protected function generateCustomerCode(): string
    {
        for ($i = 0; $i < 10; $i++) {
            $code = 'CUST-' . strtoupper(Str::random(6));

            if (! Customer::query()->where('customer_code', $code)->exists()) {
                return $code;
            }
        }

        return 'CUST-' . strtoupper(Str::random(8));
    }
}
