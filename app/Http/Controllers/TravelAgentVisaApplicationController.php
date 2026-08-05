<?php

namespace App\Http\Controllers;

use App\Models\VisaApplication;
use App\Models\VisaType;
use App\Services\VisaNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TravelAgentVisaApplicationController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();

        $query = VisaApplication::query()
            ->where('travel_agent_id', $agent->id)
            ->with(['visaType', 'visaOfficer', 'applicants', 'customer']);

        // Filters
        if ($request->filled('search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('passport_number')) {
            $query->whereHas('applicants', function ($q) use ($request) {
                $q->where('passport_number', 'like', '%' . $request->passport_number . '%');
            });
        }

        if ($request->filled('visa_type_id')) {
            $query->where('visa_type_id', $request->visa_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $visaApplications = $query->orderByDesc('created_at')->paginate(15);
        $visaTypes = VisaType::where('is_active', true)->get();

        // Dashboard metrics
        $metrics = [
            'total' => VisaApplication::where('travel_agent_id', $agent->id)->count(),
            'pending' => VisaApplication::where('travel_agent_id', $agent->id)
                ->whereIn('status', ['Pending', 'Submitted', 'Under Review'])->count(),
            'approved' => VisaApplication::where('travel_agent_id', $agent->id)
                ->where('status', 'Approved')->count(),
            'rejected' => VisaApplication::where('travel_agent_id', $agent->id)
                ->where('status', 'Rejected')->count(),
            'issued' => VisaApplication::where('travel_agent_id', $agent->id)
                ->where('status', 'Issued')->count(),
        ];

        $statuses = [
            'Draft',
            'Submitted',
            'Pending',
            'Under Review',
            'Embassy Checking',
            'Approved',
            'Rejected',
            'Issued',
        ];

        return view('travel_agents.visa-applications.index', compact(
            'agent',
            'visaApplications',
            'visaTypes',
            'metrics',
            'statuses'
        ));
    }

    public function create()
    {
        $agent = Auth::guard('travel_agent')->user();
        $visaTypes = VisaType::where('is_active', true)->get();

        return view('travel_agents.visa-applications.create', compact('agent', 'visaTypes'));
    }

    public function store(Request $request)
    {
        set_time_limit(120);

        $agent = Auth::guard('travel_agent')->user();

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'passport_number' => 'required|string|max:100|unique:visa_applications,passport_number',
            'passport_expiry' => 'required|date|after:today',
            'nationality' => 'required|string|max:100',
            'visa_type_id' => 'required|exists:visa_types,id',
            'remarks' => 'nullable|string|max:1000',
            
            // Document validations
            'passport_copy' => 'required|file|mimes:jpeg,png,pdf,jpg|max:5120',
            'cnic_copy' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
            'photograph' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'vaccination_certificate' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
        ]);

        $visaType = VisaType::findOrFail($validated['visa_type_id']);

        // Create application record
        $appData = [
            'travel_agent_id' => $agent->id,
            'visa_officer_id' => $validated['visa_officer_id'] ?? null,
            'status' => 'Submitted',
            'remarks' => $validated['remarks'] ?? null,
            'visa_type' => $validated['visa_type_id'] ?? null,
        ];

        $application = VisaApplication::create($appData);
        $application->visa_fee = $visaType->base_fee;
        $application->service_charges = $visaType->service_charge;
        $application->total_amount = $visaType->base_fee + $visaType->service_charge;
        $application->save();

        // Create single applicant for this application
        $applicantData = [
            'visa_application_id' => $application->id,
            'applicant_number' => 1,
            'full_name' => $validated['customer_name'],
            'passport_number' => $validated['passport_number'],
            'passport_expiry_date' => $validated['passport_expiry'],
            'nationality' => $validated['nationality'],
        ];

        if ($request->hasFile('passport_copy')) {
            $applicantData['passport_scan'] = $request->file('passport_copy')->store('visa_docs/agent_' . $agent->id, 'public');
        }
        if ($request->hasFile('photograph')) {
            $applicantData['photo'] = $request->file('photograph')->store('visa_docs/agent_' . $agent->id, 'public');
        }
        if ($request->hasFile('cnic_copy')) {
            $applicantData['cnic'] = $request->file('cnic_copy')->store('visa_docs/agent_' . $agent->id, 'public');
        }

        \App\Models\VisaApplicant::create($applicantData);

        // Safely notify admin
        rescue(function () use ($application) {
            VisaNotificationService::sendStatusNotification($application);
        }, null, false);

        // FIX: Redirect using action or fallback URL to avoid missing route name issue
        return redirect()->action([static::class, 'index'])
            ->with('success', 'Visa application submitted successfully!');
    }

    public function show($id)
    {
        $agent = Auth::guard('travel_agent')->user();

        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->with(['visaType', 'visaOfficer', 'applicants', 'customer'])
            ->firstOrFail();

        return view('travel_agents.visa-applications.show', compact('agent', 'visaApplication'));
    }

    public function edit($id)
    {
        $agent = Auth::guard('travel_agent')->user();

        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        if (!in_array($visaApplication->status, ['Submitted', 'Documents Required'])) {
            return back()->withErrors([
                'error' => 'This application cannot be edited in its current status.'
            ]);
        }

        $visaTypes = VisaType::where('is_active', true)->get();

        return view('travel_agents.visa-applications.edit', compact('agent', 'visaApplication', 'visaTypes'));
    }

    public function update(Request $request, $id)
    {
        $agent = Auth::guard('travel_agent')->user();

        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        if (!in_array($visaApplication->status, ['Submitted', 'Documents Required'])) {
            return back()->withErrors([
                'error' => 'This application cannot be edited in its current status.'
            ]);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'passport_number' => 'required|string|max:100|unique:visa_applications,passport_number,' . $id,
            'passport_expiry' => 'required|date|after:today',
            'nationality' => 'required|string|max:100',
            'visa_type_id' => 'required|exists:visa_types,id',
            'remarks' => 'nullable|string|max:1000',
            
            'passport_copy' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
            'cnic_copy' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
            'photograph' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'vaccination_certificate' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
        ]);

        $visaType = VisaType::findOrFail($validated['visa_type_id']);

        $appData = [
            'visa_officer_id' => $validated['visa_officer_id'] ?? $visaApplication->visa_officer_id,
            'remarks' => $validated['remarks'] ?? $visaApplication->remarks,
            'status' => $visaApplication->status,
            'visa_type' => $validated['visa_type_id'] ?? $visaApplication->visa_type,
        ];

        $visaApplication->update($appData);

        $visaApplication->visa_fee = $visaType->base_fee;
        $visaApplication->service_charges = $visaType->service_charge;
        $visaApplication->total_amount = $visaType->base_fee + $visaType->service_charge;
        $visaApplication->save();

        $firstApplicant = $visaApplication->applicants()->orderBy('applicant_number')->first();
        if ($firstApplicant) {
            $firstApplicant->full_name = $validated['customer_name'] ?? $firstApplicant->full_name;
            $firstApplicant->passport_number = $validated['passport_number'] ?? $firstApplicant->passport_number;
            $firstApplicant->passport_expiry_date = $validated['passport_expiry'] ?? $firstApplicant->passport_expiry_date;
            $firstApplicant->nationality = $validated['nationality'] ?? $firstApplicant->nationality;
            if ($request->hasFile('passport_copy')) {
                $firstApplicant->passport_scan = $request->file('passport_copy')->store('visa_docs/agent_' . $agent->id, 'public');
            }
            if ($request->hasFile('photograph')) {
                $firstApplicant->photo = $request->file('photograph')->store('visa_docs/agent_' . $agent->id, 'public');
            }
            if ($request->hasFile('cnic_copy')) {
                $firstApplicant->cnic = $request->file('cnic_copy')->store('visa_docs/agent_' . $agent->id, 'public');
            }
            $firstApplicant->save();
        }

        return redirect()->action([static::class, 'show'], ['id' => $visaApplication->id])
            ->with('success', 'Visa application updated successfully!');
    }

    public function destroy($id)
    {
        $agent = Auth::guard('travel_agent')->user();

        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        if (!in_array($visaApplication->status, ['Submitted', 'Documents Required', 'Rejected'])) {
            return back()->withErrors([
                'error' => 'This application cannot be edited or deleted in its current status.'
            ]);
        }

        $documentFields = ['passport_copy', 'cnic_copy', 'photograph', 'vaccination_certificate', 'visa_copy'];
        foreach ($documentFields as $field) {
            if ($visaApplication->$field) {
                Storage::disk('public')->delete($visaApplication->$field);
            }
        }

        $visaApplication->delete();

        return redirect()->action([static::class, 'index'])
            ->with('success', 'Visa application deleted successfully!');
    }

    public function downloadDocument($id, $field)
    {
        $agent = Auth::guard('travel_agent')->user();

        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        $allowedFields = ['passport_copy', 'cnic_copy', 'photograph', 'vaccination_certificate', 'visa_copy'];

        if (!in_array($field, $allowedFields)) {
            abort(404);
        }

        if (!$visaApplication->$field) {
            return back()->withErrors(['error' => 'Document not available.']);
        }

        $path = $visaApplication->$field;
        if (!Storage::disk('public')->exists($path)) {
            return back()->withErrors(['error' => 'File not found on storage server.']);
        }

        return Storage::disk('public')->download($path);
    }

    public function print($id)
    {
        $agent = Auth::guard('travel_agent')->user();

        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->with(['visaType', 'visaOfficer'])
            ->firstOrFail();

        return view('travel_agents.visa-applications.print', compact('agent', 'visaApplication'));
    }
}