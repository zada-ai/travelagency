<?php

namespace App\Http\Controllers;

use App\Models\VisaApplication;
use App\Models\VisaApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CustomerVisaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $customer = $user?->customer()->first();

        if (! $customer) {
            return redirect()->route('customer.dashboard')
                ->withErrors(['error' => 'Your account is not linked to a customer profile. Please login with a customer account to access visa applications.']);
        }

        $applicants = VisaApplicant::with(['application.assignedSalesOfficer'])
            ->whereHas('application', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->latest()
            ->get();

        return view('travel_agents.dashboard', compact('applicants'))
            ->with('userRole', 'customer')
            ->with('innerView', 'customer.visa.index');
    }

    public function create()
    {
        $user = Auth::user();
        $customer = $user?->customer()->first();

        if (! $customer) {
            return redirect()->route('customer.dashboard')
                ->withErrors(['error' => 'Your account is not linked to a customer profile. Please login with a customer account to apply for visas.']);
        }

        return view('travel_agents.dashboard')
            ->with('userRole', 'customer')
            ->with('innerView', 'customer.visa.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $customer = $user?->customer()->first();

        if (! $customer) {
            return redirect()->route('customer.dashboard')
                ->withErrors(['error' => 'Your account is not linked to a customer profile. Please login with a customer account to submit a visa application.']);
        }

        $validated = $request->validate([
            'total_persons' => 'required|integer|min:1',
            'adults' => 'required|integer|min:0',
            'children' => 'required|integer|min:0',
            'infants' => 'required|integer|min:0',
            'visa_type' => 'nullable|string|max:255',
            'applicants' => 'required|array|min:1',
            'applicants.*.full_name' => 'required|string|max:255',
            'applicants.*.father_name' => 'nullable|string|max:255',
            'applicants.*.gender' => 'nullable|string|max:50',
            'applicants.*.date_of_birth' => 'nullable|date',
            'applicants.*.nationality' => 'nullable|string|max:255',
            'applicants.*.passport_number' => 'nullable|string|max:255',
            'applicants.*.passport_expiry_date' => 'nullable|date',
            'applicants.*.mobile_number' => 'nullable|string|max:255',
            'applicants.*.email' => 'nullable|email|max:255',
            'applicants.*.address' => 'nullable|string',
            'applicants.*.passport_scan' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'applicants.*.photo' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'applicants.*.cnic' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Validate sum
        if (($validated['adults'] + $validated['children'] + $validated['infants']) !== (int) $validated['total_persons']) {
            return back()->withInput()->withErrors(['total_persons' => 'Adults + Children + Infants must equal Total Persons']);
        }

        // Adjust children count when one or more applicants are older than 2 years old by DOB.
        $adjustedAdults = $validated['adults'];
        $adjustedChildren = $validated['children'];

        foreach ($validated['applicants'] as $applicant) {
            if (empty($applicant['date_of_birth'])) {
                continue;
            }

            try {
                $age = Carbon::parse($applicant['date_of_birth'])->age;
            } catch (\Exception $e) {
                continue;
            }

            if ($age > 2 && $adjustedChildren > 0) {
                $adjustedChildren--;
                $adjustedAdults++;
            }
        }

        $validated['adults'] = $adjustedAdults;
        $validated['children'] = $adjustedChildren;

        if (($validated['adults'] + $validated['children'] + $validated['infants']) !== (int) $validated['total_persons']) {
            return back()->withInput()->withErrors(['total_persons' => 'Applicant counts do not match total persons after age adjustment.']);
        }

        DB::beginTransaction();

        try {
            $application = VisaApplication::create([
                'customer_id' => $customer->id,
                'travel_agent_id' => $customer->travel_agent_id,
                'assigned_sales_officer_id' => null,
                'status' => 'pending',
                'remarks' => null,
                'total_persons' => $validated['total_persons'],
                'adults' => $validated['adults'],
                'children' => $validated['children'],
                'infants' => $validated['infants'],
                'visa_type' => $validated['visa_type'] ?? null,
            ]);

            foreach ($validated['applicants'] as $index => $app) {
                $num = $index + 1;
                $data = [
                    'visa_application_id' => $application->id,
                    'applicant_number' => $num,
                    'full_name' => $app['full_name'] ?? '',
                    'father_name' => $app['father_name'] ?? null,
                    'gender' => $app['gender'] ?? null,
                    'date_of_birth' => $app['date_of_birth'] ?? null,
                    'nationality' => $app['nationality'] ?? null,
                    'passport_number' => $app['passport_number'] ?? null,
                    'passport_expiry_date' => $app['passport_expiry_date'] ?? null,
                    'mobile_number' => $app['mobile_number'] ?? null,
                    'email' => $app['email'] ?? null,
                    'address' => $app['address'] ?? null,
                ];

                foreach (['passport_scan', 'photo', 'cnic'] as $field) {
                    if ($request->hasFile("applicants.$index.$field")) {
                        $file = $request->file("applicants.$index.$field");
                        $path = $file->store('visa_applicants/' . $application->id . '/' . $num, 'public');
                        $data[$field] = $path;
                    }
                }

                VisaApplicant::create($data);
            }

            DB::commit();

                return redirect()->route('customer.visa.index')->with('success', 'Visa application submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $customer = $user?->customer()->first();

        if (! $customer) {
            return redirect()->route('customer.dashboard')
                ->withErrors(['error' => 'Your account is not linked to a customer profile. Please login with a customer account to view visa applications.']);
        }

        $application = VisaApplication::with('applicants', 'assignedSalesOfficer')
            ->findOrFail($id);

        if ($application->customer_id !== $customer->id) {
            abort(403);
        }

        return view('travel_agents.dashboard', compact('application'))
            ->with('userRole', 'customer')
            ->with('innerView', 'customer.visa.show');
    }
}
