<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TravelAgent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['user', 'travelAgent']);

        if ($request->filled('q')) {
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('customer_code', 'like', '%'.$request->q.'%')
                    ->orWhere('first_name', 'like', '%'.$request->q.'%')
                    ->orWhere('last_name', 'like', '%'.$request->q.'%')
                    ->orWhere('phone', 'like', '%'.$request->q.'%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('email', 'like', '%'.$request->q.'%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('agent_id')) {
            $query->where('travel_agent_id', $request->agent_id);
        }

        $customers = $query->latest('created_at')->paginate(15)->withQueryString();
        $agents = TravelAgent::select('id', 'company_name', 'email')->orderBy('company_name')->get();

        $metrics = [
            'total' => Customer::count(),
            'active' => Customer::where('status', 'active')->count(),
            'inactive' => Customer::where('status', 'inactive')->count(),
            'with_agent' => Customer::whereNotNull('travel_agent_id')->count(),
        ];

        return view('admin.customer-management', compact('customers', 'agents', 'metrics'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['user', 'travelAgent']);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $agents = TravelAgent::select('id', 'company_name', 'email')->orderBy('company_name')->get();

        return view('admin.customers.edit', compact('customer', 'agents'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_code' => ['required', 'string', 'max:255', Rule::unique('customers', 'customer_code')->ignore($customer->id)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($customer->user_id)],
            'phone' => ['required', 'string', 'max:255', Rule::unique('customers', 'phone')->ignore($customer->id)],
            'whatsapp_number' => ['nullable', 'string', 'max:255'],
            'cnic' => ['nullable', 'string', 'max:255', Rule::unique('customers', 'cnic')->ignore($customer->id)],
            'passport_no' => ['nullable', 'string', 'max:255', Rule::unique('customers', 'passport_no')->ignore($customer->id)],
            'passport_expiry' => ['nullable', 'date'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'relationship' => ['nullable', 'string', 'max:255'],
            'emergency_contact_number' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'travel_agent_id' => ['nullable', 'exists:travel_agents,id'],
        ]);

        $customer->update([
            'customer_code' => $validated['customer_code'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'phone' => $validated['phone'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'cnic' => $validated['cnic'] ?? null,
            'passport_no' => $validated['passport_no'] ?? null,
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
            'status' => $validated['status'],
            'travel_agent_id' => $validated['travel_agent_id'] ?? null,
        ]);

        if ($customer->user) {
            $customer->user->update([
                'email' => $validated['email'],
                'name' => trim($validated['first_name'].' '.$validated['last_name']),
            ]);
        }

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer record updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customer-management')->with('success', 'Customer record deleted successfully.');
    }
}
