<?php

namespace App\Http\Controllers;

use App\Models\VisaApplication;
use App\Models\VisaType;
use App\Models\VoucherCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TravelAgentCustomerVisaController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();

        $query = VoucherCustomer::query()
            ->where('travel_agent_id', $agent->id);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('passport_no', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $allCustomers = VoucherCustomer::where('travel_agent_id', $agent->id)->get();
        $stats = [
            'total' => $allCustomers->count(),
            'adults' => $allCustomers->filter(fn ($c) => $c->passenger_type === 'Adult')->count(),
            'children' => $allCustomers->filter(fn ($c) => str_starts_with($c->passenger_type, 'Child'))->count(),
            'infants' => $allCustomers->filter(fn ($c) => str_starts_with($c->passenger_type, 'Infant'))->count(),
        ];

        return view('travel_agents.customer-visa.index', compact('agent', 'customers', 'stats'));
    }

    public function store(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'passport_no' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date'],
        ]);

        $passport = strtoupper(trim($validated['passport_no']));

        $exists = VoucherCustomer::where('travel_agent_id', $agent->id)
            ->where('passport_no', $passport)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'A customer with this passport number is already registered.');
        }

        VoucherCustomer::create([
            'travel_agent_id' => $agent->id,
            'name' => trim($validated['name']),
            'passport_no' => $passport,
            'date_of_birth' => $validated['date_of_birth'],
        ]);

        return back()->with('success', 'Customer added successfully.');
    }

    public function destroy($id)
    {
        $agent = Auth::guard('travel_agent')->user();

        $customer = VoucherCustomer::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        $customer->delete();

        return back()->with('success', 'Customer deleted successfully.');
    }

    public function show($id)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->with(['visaType', 'visaOfficer', 'customer', 'applicants'])
            ->firstOrFail();

        return view('travel_agents.customer-visa.show', compact('agent', 'visaApplication'));
    }

    public function downloadVisaCopy($id)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        if ($visaApplication->status !== 'Issued') {
            return back()->withErrors(['error' => 'Visa copy can only be downloaded when status is Issued.']);
        }

        if (!$visaApplication->visa_copy) {
            return back()->withErrors(['error' => 'Visa copy not available.']);
        }

        $path = $visaApplication->visa_copy;
        if (!Storage::disk('public')->exists($path)) {
            return back()->withErrors(['error' => 'Visa copy file was not found on disk.']);
        }

        return response()->download(Storage::disk('public')->path($path));
    }

    public function downloadDocument($id, $field)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        $allowedFields = ['passport_copy', 'cnic_copy', 'photograph', 'vaccination_certificate'];
        
        if (!in_array($field, $allowedFields)) {
            return back()->withErrors(['error' => 'Invalid document field.']);
        }

        if (!$visaApplication->$field) {
            return back()->withErrors(['error' => 'Document not available.']);
        }

        $path = $visaApplication->$field;
        if (!Storage::disk('public')->exists($path)) {
            return back()->withErrors(['error' => 'Document file was not found on disk.']);
        }

        return response()->download(Storage::disk('public')->path($path));
    }
}
