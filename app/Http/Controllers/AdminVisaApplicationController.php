<?php

namespace App\Http\Controllers;

use App\Models\VisaApplicant;
use App\Models\VisaApplication;
use App\Models\VisaType;
use App\Models\TravelAgent;
use App\Models\User;
use App\Services\VisaNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVisaApplicationController extends Controller
{
    /**
     * Display Visa Dashboard & Applications list with search filters.
     */
    public function index(Request $request)
    {
        // Search & Filters parameters
        $search = $request->input('search');
        $passport = $request->input('passport');
        $status = $request->input('status');
        $visaTypeId = $request->input('visa_type_id');
        $agentId = $request->input('travel_agent_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = VisaApplicant::with(['application.visaType', 'application.travelAgent', 'application.visaOfficer', 'application.customer']);

        // Applying filters
        if ($search) {
            $query->whereHas('application.customer', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($passport) {
            $query->where('passport_number', 'like', '%' . $passport . '%');
        }
        if ($status) {
            $query->whereHas('application', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }
        if ($visaTypeId) {
            $query->whereHas('application', function ($q) use ($visaTypeId) {
                $q->where('visa_type', $visaTypeId)->orWhere('visa_type_id', $visaTypeId);
            });
        }
        if ($agentId) {
            $query->whereHas('application', function ($q) use ($agentId) {
                $q->where('travel_agent_id', $agentId);
            });
        }
        if ($startDate && $endDate) {
            // no travel date range filtering when travel dates are removed
        }

        // Paginating applicant-level rows to show each traveler separately
        $applications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        // Calculate KPI Metrics for Dashboard
        $metrics = [
            'total' => VisaApplication::count(),
            'pending' => VisaApplication::whereIn('status', ['Pending', 'Submitted', 'Under Review'])->count(),
            'approved' => VisaApplication::where('status', 'Approved')->count(),
            'rejected' => VisaApplication::where('status', 'Rejected')->count(),
            'issued' => VisaApplication::where('status', 'Issued')->count(),
            'today' => VisaApplication::whereDate('created_at', now()->toDateString())->count(),
        ];

        $visaTypes = VisaType::where('is_active', true)->get();
        $agents = TravelAgent::select('id', 'company_name')->orderBy('company_name')->get();

        return view('admin.visa-management', compact('applications', 'metrics', 'visaTypes', 'agents'));
    }

    /**
     * Show creation form.
     */
    public function create()
    {
        $visaTypes = VisaType::where('is_active', true)->get();
        $agents = TravelAgent::select('id', 'company_name')->orderBy('company_name')->get();
        $officers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Visa Officer');
        })->select('id', 'name')->orderBy('name')->get();

        return view('admin.visa.create', compact('visaTypes', 'agents', 'officers'));
    }

    /**
     * Store new application with document uploads.
     */
    public function store(Request $request)
    {
        set_time_limit(120);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'passport_number' => 'required|string|max:100|unique:visa_applications,passport_number',
            'passport_expiry' => 'required|date',
            'nationality' => 'required|string|max:100',
            'travel_from' => 'required|date',
            'travel_to' => 'nullable|date|after_or_equal:travel_from',
            'visa_type_id' => 'required|exists:visa_types,id',
            'travel_agent_id' => 'nullable|exists:travel_agents,id',
            'visa_officer_id' => 'nullable|exists:users,id',
            'remarks' => 'nullable|string',
            
            // Document rules
            'passport_copy' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
            'cnic_copy' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
            'photograph' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'vaccination_certificate' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
            'visa_copy' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
        ]);

        $visaType = VisaType::findOrFail($validated['visa_type_id']);

        // Prepare application-level payload (only fields that belong to VisaApplication)
        $appData = [
            'travel_agent_id' => $validated['travel_agent_id'] ?? null,
            'visa_officer_id' => $validated['visa_officer_id'] ?? null,
            'status' => 'Submitted',
            'remarks' => $validated['remarks'] ?? null,
            'visa_type' => $validated['visa_type_id'] ?? null,
        ];

        // Create application then attach applicant record
        $application = VisaApplication::create($appData);

        // Store application-level fee fields directly
        $application->visa_fee = $visaType->base_fee;
        $application->service_charges = $visaType->service_charge;
        $application->total_amount = $visaType->base_fee + $visaType->service_charge;
        $application->save();

        // Document handles and applicant mapping
        $applicantData = [
            'visa_application_id' => $application->id,
            'applicant_number' => 1,
            'full_name' => $validated['customer_name'] ?? null,
            'passport_number' => $validated['passport_number'] ?? null,
            'passport_expiry_date' => $validated['passport_expiry'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
        ];

        if ($request->hasFile('passport_copy')) {
            $applicantData['passport_scan'] = $request->file('passport_copy')->store('visa_docs', 'public');
        }
        if ($request->hasFile('photograph')) {
            $applicantData['photo'] = $request->file('photograph')->store('visa_docs', 'public');
        }
        if ($request->hasFile('cnic_copy')) {
            $applicantData['cnic'] = $request->file('cnic_copy')->store('visa_docs', 'public');
        }

        \App\Models\VisaApplicant::create($applicantData);

        // Safely dispatch notifications without hanging execution
        rescue(function () use ($application) {
            VisaNotificationService::sendStatusNotification($application);
        }, null, false);

        return redirect()->route('admin.visa-management')->with('success', 'Visa Application registered and submitted successfully!');
    }

    /**
     * Display detailed summary for status tracking and files management.
     */
    public function show(VisaApplication $visaApplication)
    {
        $user = auth()->user();
        // If visa officer, ensure this application is assigned to them
        if (method_exists($user, 'hasRole') && $user->hasRole('visa_office') && $visaApplication->visa_officer_id !== $user->id) {
            abort(403);
        }

        $visaApplication->load(['visaType', 'travelAgent', 'visaOfficer', 'applicants']);
        $officers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Visa Officer');
        })->select('id', 'name')->orderBy('name')->get();

        return view('admin.visa.show', [
            'application' => $visaApplication,
            'officers' => $officers,
        ]);
    }

    /**
     * Show edit form.
     */
    public function edit(VisaApplication $visaApplication)
    {
        $visaApplication->load('applicants');

        $visaTypes = VisaType::where('is_active', true)->get();
        $agents = TravelAgent::select('id', 'company_name')->orderBy('company_name')->get();
        $officers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Visa Officer');
        })->select('id', 'name')->orderBy('name')->get();

        $firstApplicant = $visaApplication->applicants->sortBy('applicant_number')->first();

        return view('admin.visa.edit', [
            'application' => $visaApplication,
            'visaTypes' => $visaTypes,
            'agents' => $agents,
            'officers' => $officers,
            'firstApplicant' => $firstApplicant,
        ]);
    }

    /**
     * Update application specifications.
     */
    public function update(Request $request, VisaApplication $visaApplication)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'passport_number' => 'required|string|max:100|unique:visa_applications,passport_number,' . $visaApplication->id,
            'passport_expiry' => 'required|date',
            'nationality' => 'required|string|max:100',
            'travel_from' => 'required|date',
            'travel_to' => 'nullable|date|after_or_equal:travel_from',
            'visa_type_id' => 'required|exists:visa_types,id',
            'travel_agent_id' => 'nullable|exists:travel_agents,id',
            'visa_officer_id' => 'nullable|exists:users,id',
            'remarks' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $visaType = VisaType::findOrFail($validated['visa_type_id']);

        // Update application-level fields only
        $appData = [
            'travel_agent_id' => $validated['travel_agent_id'] ?? $visaApplication->travel_agent_id,
            'visa_officer_id' => $validated['visa_officer_id'] ?? $visaApplication->visa_officer_id,
            'remarks' => $validated['remarks'] ?? $visaApplication->remarks,
            'status' => $validated['status'] ?? $visaApplication->status,
            'visa_type' => $validated['visa_type_id'] ?? $visaApplication->visa_type,
        ];

        $visaApplication->update($appData);

        // Update fee fields
        $visaApplication->visa_fee = $visaType->base_fee;
        $visaApplication->service_charges = $visaType->service_charge;
        $visaApplication->total_amount = $visaType->base_fee + $visaType->service_charge;
        $visaApplication->save();

        // Update first applicant if exists
        $firstApplicant = $visaApplication->applicants()->orderBy('applicant_number')->first();
        if ($firstApplicant) {
            $firstApplicant->full_name = $validated['customer_name'] ?? $firstApplicant->full_name;
            $firstApplicant->passport_number = $validated['passport_number'] ?? $firstApplicant->passport_number;
            $firstApplicant->passport_expiry_date = $validated['passport_expiry'] ?? $firstApplicant->passport_expiry_date;
            $firstApplicant->nationality = $validated['nationality'] ?? $firstApplicant->nationality;
            if ($request->hasFile('passport_copy')) {
                $firstApplicant->passport_scan = $request->file('passport_copy')->store('visa_docs', 'public');
            }
            if ($request->hasFile('photograph')) {
                $firstApplicant->photo = $request->file('photograph')->store('visa_docs', 'public');
            }
            if ($request->hasFile('cnic_copy')) {
                $firstApplicant->cnic = $request->file('cnic_copy')->store('visa_docs', 'public');
            }
            $firstApplicant->save();
        }

        return redirect()->route('admin.visa-applications.show', $visaApplication)->with('success', 'Visa details updated successfully!');
    }

    /**
     * Delete application block.
     */
    public function destroy(VisaApplication $visaApplication)
    {
        // Delete related files
        $fields = ['passport_copy', 'cnic_copy', 'photograph', 'vaccination_certificate', 'visa_copy'];
        foreach ($fields as $field) {
            if ($visaApplication->$field) {
                Storage::disk('public')->delete($visaApplication->$field);
            }
        }

        $visaApplication->delete();
        return redirect()->route('admin.visa-management')->with('success', 'Visa Application deleted successfully!');
    }

    /**
     * Quick status changes.
     */
    public function updateStatus(Request $request, VisaApplication $visaApplication)
    {
        $request->validate([
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $status = $request->input('status');
        $remarks = $request->input('remarks');

        $user = auth()->user();
        if (method_exists($user, 'hasRole') && $user->hasRole('visa_office') && $visaApplication->visa_officer_id !== $user->id) {
            abort(403);
        }

        $visaApplication->status = $status;
        if ($remarks) {
            $visaApplication->remarks = $remarks;
        }
        $visaApplication->save();

        // Dispatch notifications safely
        rescue(function () use ($visaApplication) {
            VisaNotificationService::sendStatusNotification($visaApplication);
        }, null, false);

        return back()->with('success', "Visa Application status updated to {$status} successfully!");
    }

    /**
     * Assign officer.
     */
    public function assignOfficer(Request $request, VisaApplication $visaApplication)
    {
        $request->validate([
            'visa_officer_id' => 'nullable|exists:users,id',
        ]);

        $visaApplication->visa_officer_id = $request->input('visa_officer_id');
        $visaApplication->save();

        return back()->with('success', 'Visa Officer assigned successfully!');
    }

    /**
     * Render printable copy sheet.
     */
    public function print(VisaApplication $visaApplication)
    {
        $user = auth()->user();
        if (method_exists($user, 'hasRole') && $user->hasRole('visa_office') && $visaApplication->visa_officer_id !== $user->id) {
            abort(403);
        }

        $visaApplication->load(['visaType', 'travelAgent', 'visaOfficer', 'applicants']);
        return view('admin.visa.print', ['application' => $visaApplication]);
    }

    /**
     * Download uploaded files safely.
     */
    public function downloadDocument(VisaApplication $visaApplication, $field)
    {
        $user = auth()->user();
        if (method_exists($user, 'hasRole') && $user->hasRole('visa_office') && $visaApplication->visa_officer_id !== $user->id) {
            abort(403);
        }
        if (!in_array($field, ['passport_copy', 'cnic_copy', 'photograph', 'vaccination_certificate', 'visa_copy'])) {
            abort(404);
        }

        $path = $visaApplication->$field;
        if (!$path || !Storage::disk('public')->exists($path)) {
            return back()->withErrors(['document' => 'File not found on storage server.']);
        }

        return Storage::disk('public')->download($path);
    }

    /**
     * Delete files.
     */
    public function deleteDocument(VisaApplication $visaApplication, $field)
    {
        if (!in_array($field, ['passport_copy', 'cnic_copy', 'photograph', 'vaccination_certificate', 'visa_copy'])) {
            abort(404);
        }

        $path = $visaApplication->$field;
        if ($path) {
            Storage::disk('public')->delete($path);
            $visaApplication->$field = null;
            
            // Set status to Documents Required if we deleted mandatory ones
            if ($field === 'passport_copy' || $field === 'photograph') {
                $visaApplication->status = 'Documents Required';
            }
            $visaApplication->save();

            rescue(function () use ($visaApplication) {
                VisaNotificationService::sendStatusNotification($visaApplication);
            }, null, false);
        }

        return back()->with('success', 'Document deleted successfully.');
    }

    /**
     * Replace document files.
     */
    public function replaceDocument(Request $request, VisaApplication $visaApplication, $field)
    {
        if (!in_array($field, ['passport_copy', 'cnic_copy', 'photograph', 'vaccination_certificate', 'visa_copy'])) {
            abort(404);
        }

        set_time_limit(120);

        $rules = ($field === 'photograph') 
            ? 'required|image|mimes:jpeg,png,jpg,webp|max:5120' 
            : 'required|file|mimes:jpeg,png,pdf,jpg|max:5120';

        $request->validate([
            'document' => $rules,
        ]);

        // Delete old
        if ($visaApplication->$field) {
            Storage::disk('public')->delete($visaApplication->$field);
        }

        $path = $request->file('document')->store('visa_docs', 'public');
        $visaApplication->$field = $path;
        $visaApplication->save();

        return back()->with('success', 'Document replaced successfully.');
    }

    /**
     * View Reports page showing summary charts & lists.
     */
    public function reports(Request $request)
    {
        $status = $request->input('status');
        $type = $request->input('visa_type_id');
        $period = $request->input('period', 'monthly'); // daily, monthly, custom

        $query = VisaApplication::with(['visaType', 'travelAgent']);

        if ($status) {
            $query->where('status', $status);
        }
        if ($type) {
            $query->where('visa_type_id', $type);
        }

        if ($period === 'daily') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        $applications = $query->orderByDesc('created_at')->get();
        $visaTypes = VisaType::where('is_active', true)->get();

        // Calculate aggregates for report dashboards
        $statusCounts = VisaApplication::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $dailyCounts = VisaApplication::whereDate('created_at', now()->toDateString())->count();
        $monthlyCounts = VisaApplication::whereMonth('created_at', now()->month)->count();

        return view('admin.visa.reports', compact('applications', 'visaTypes', 'statusCounts', 'dailyCounts', 'monthlyCounts'));
    }

    /**
     * Assigned Applications for Visa Officer (RBAC enforced)
     */
    public function assignedApplications(Request $request)
    {
        $user = auth()->user();
        // RBAC: only visa_office or admin can access
        if (method_exists($user, 'hasRole') && ! $user->hasRole('visa_office') && ! $user->hasRole('admin')) {
            abort(403);
        }

        $search = $request->input('search');
        $passport = $request->input('passport');
        $status = $request->input('status');
        $visaTypeId = $request->input('visa_type_id');

        $query = VisaApplication::with(['visaType', 'travelAgent', 'visaOfficer', 'applicants', 'customer'])
            ->where('visa_officer_id', $user->id);

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($passport) {
            $query->whereHas('applicants', function ($q) use ($passport) {
                $q->where('passport_number', 'like', '%' . $passport . '%');
            });
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($visaTypeId) {
            $query->where('visa_type', $visaTypeId)->orWhere('visa_type_id', $visaTypeId);
        }

        $applications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $metrics = [
            'total' => VisaApplication::where('visa_officer_id', $user->id)->count(),
            'pending' => VisaApplication::where('visa_officer_id', $user->id)->whereIn('status', ['Pending', 'Submitted', 'Under Review'])->count(),
            'approved' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Approved')->count(),
            'rejected' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Rejected')->count(),
            'issued' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Issued')->count(),
        ];

        $visaTypes = VisaType::where('is_active', true)->get();
        $agents = TravelAgent::select('id', 'company_name')->orderBy('company_name')->get();

        return view('admin.visa-management', compact('applications', 'metrics', 'visaTypes', 'agents'))->with('officer_view', true);
    }

    /**
     * Document verification queue for officer (applications assigned to officer with missing documents)
     */
    public function documentQueue(Request $request)
    {
        $user = auth()->user();
        if (method_exists($user, 'hasRole') && ! $user->hasRole('visa_office') && ! $user->hasRole('admin')) {
            abort(403);
        }

        $query = VisaApplication::with(['visaType', 'travelAgent', 'visaOfficer'])
            ->where('visa_officer_id', $user->id)
            ->where(function ($q) {
                $q->whereNull('passport_copy')
                  ->orWhereNull('photograph')
                  ->orWhereNull('cnic_copy')
                  ->orWhereNull('vaccination_certificate')
                  ->orWhere('status', 'Documents Required');
            });

        $applications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $metrics = [
            'total' => $applications->total(),
        ];

        $visaTypes = VisaType::where('is_active', true)->get();
        $agents = TravelAgent::select('id', 'company_name')->orderBy('company_name')->get();

        return view('admin.visa-management', compact('applications', 'metrics', 'visaTypes', 'agents'))->with('officer_view', true)->with('document_queue', true);
    }

    /**
     * Issued visas list for officer
     */
    public function issuedVisas(Request $request)
    {
        $user = auth()->user();
        if (method_exists($user, 'hasRole') && ! $user->hasRole('visa_office') && ! $user->hasRole('admin')) {
            abort(403);
        }

        $applications = VisaApplication::with(['visaType', 'travelAgent', 'visaOfficer'])
            ->where('visa_officer_id', $user->id)
            ->where('status', 'Issued')
            ->orderByDesc('updated_at')
            ->paginate(20)->withQueryString();

        $metrics = [
            'issued' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Issued')->count(),
        ];

        $visaTypes = VisaType::where('is_active', true)->get();
        $agents = TravelAgent::select('id', 'company_name')->orderBy('company_name')->get();

        return view('admin.visa-management', compact('applications', 'metrics', 'visaTypes', 'agents'))->with('officer_view', true)->with('issued_view', true);
    }

    /**
     * Simple officer profile view data
     */
    public function officerProfile()
    {
        $user = auth()->user();
        if (method_exists($user, 'hasRole') && ! $user->hasRole('visa_office') && ! $user->hasRole('admin')) {
            abort(403);
        }

        return view('admin.visa.officer-profile', ['user' => $user]);
    }

    /**
     * Officer notifications (stub)
     */
    public function notifications()
    {
        $user = auth()->user();
        if (method_exists($user, 'hasRole') && ! $user->hasRole('visa_office') && ! $user->hasRole('admin')) {
            abort(403);
        }
        // If a notifications system exists, filter by user; else return empty array
        $notifications = method_exists($user, 'notifications') ? $user->notifications()->latest()->limit(50)->get() : collect();

        return view('admin.visa.officer-notifications', compact('notifications'));
    }

    /**
     * Mark a single notification as read
     */
    public function markNotificationRead(Request $request, \App\Models\Notification $notification)
    {
        $user = auth()->user();
        
        // Verify notification belongs to user
        if ($notification->user_id != $user->id) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead(Request $request)
    {
        $user = auth()->user();

        \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Export PDF.
     */
    public function exportPdf(Request $request)
    {
        $applications = VisaApplication::with(['visaType', 'travelAgent'])->get();
        
        // Simple HTML layout for print-to-PDF output
        return view('admin.visa.print_report', ['applications' => $applications, 'title' => 'Visa Application Export Summary']);
    }

    /**
     * Export Excel/CSV.
     */
    public function exportExcel(Request $request)
    {
        $applications = VisaApplication::with(['visaType', 'travelAgent'])->get();

        $csvFileName = 'visa_applications_export_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Application ID', 'Customer Name', 'Passport No', 'Nationality', 'Visa Type', 'Travel Agent', 'Travel Date', 'Status', 'Total Price'];

        $callback = function() use($applications, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->id,
                    $app->customer_name,
                    $app->passport_number,
                    $app->nationality,
                    $app->visaType?->name,
                    $app->travelAgent?->company_name ?? 'Direct',
                    $app->travel_date ? date('Y-m-d', strtotime($app->travel_date)) : '',
                    $app->status,
                    'SAR ' . number_format($app->total_amount, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Visa Officer - Assigned Applications (Status = "Assigned")
     */
    public function officerAssigned(Request $request)
    {
        $user = auth()->user();
        
        $search = $request->input('search');
        $passport = $request->input('passport');

        $query = VisaApplication::with(['visaType', 'travelAgent', 'visaOfficer'])
            ->where('visa_officer_id', $user->id)
            ->where('status', 'Assigned');  // ONLY Assigned status

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($passport) {
            $query->whereHas('applicants', function ($q) use ($passport) {
                $q->where('passport_number', 'like', '%' . $passport . '%');
            });
        }

        $applications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $metrics = [
            'total' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Assigned')->count(),
            'pending' => VisaApplication::where('visa_officer_id', $user->id)->whereIn('status', ['Pending', 'Submitted', 'Under Review'])->count(),
            'approved' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Approved')->count(),
            'rejected' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Rejected')->count(),
            'issued' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Issued')->count(),
            'today' => VisaApplication::where('visa_officer_id', $user->id)->whereDate('created_at', today())->count(),
        ];

        $visaTypes = VisaType::where('is_active', true)->get();
        $agents = TravelAgent::select('id', 'company_name')->orderBy('company_name')->get();

        return view('admin.visa-management', compact('applications', 'metrics', 'visaTypes', 'agents'))->with('assigned_view', true);
    }

    /**
     * Visa Officer - Document Verification Queue (Status = "Assigned" OR "Under Review" with missing documents)
     */
    public function officerDocumentQueue(Request $request)
    {
        $user = auth()->user();
        
        $search = $request->input('search');
        $passport = $request->input('passport');

        $query = VisaApplication::with(['visaType', 'travelAgent', 'visaOfficer'])
            ->where('visa_officer_id', $user->id)
            ->whereIn('status', ['Assigned', 'Under Review'])  // Only these statuses
            ->where(function ($q) {
                $q->whereNull('passport_copy')
                  ->orWhereNull('cnic_copy')
                  ->orWhereNull('photograph')
                  ->orWhereNull('vaccination_certificate');
            });

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($passport) {
            $query->whereHas('applicants', function ($q) use ($passport) {
                $q->where('passport_number', 'like', '%' . $passport . '%');
            });
        }

        $applications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $metrics = [
            'total' => VisaApplication::where('visa_officer_id', $user->id)->count(),
            'pending' => VisaApplication::where('visa_officer_id', $user->id)->whereIn('status', ['Pending', 'Submitted', 'Under Review'])->count(),
            'approved' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Approved')->count(),
            'rejected' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Rejected')->count(),
            'issued' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Issued')->count(),
            'today' => VisaApplication::where('visa_officer_id', $user->id)->whereDate('created_at', today())->count(),
        ];

        $visaTypes = VisaType::where('is_active', true)->get();
        $agents = TravelAgent::select('id', 'company_name')->orderBy('company_name')->get();

        return view('admin.visa-management', compact('applications', 'metrics', 'visaTypes', 'agents'))->with('document_queue_view', true);
    }

    /**
     * Visa Officer - Rejected Applications (Status = "Rejected")
     */
    public function rejectedApplications(Request $request)
    {
        $user = auth()->user();
        
        $search = $request->input('search');
        $passport = $request->input('passport');

        $query = VisaApplication::with(['visaType', 'travelAgent', 'visaOfficer'])
            ->where('visa_officer_id', $user->id)
            ->where('status', 'Rejected');  // ONLY Rejected status

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($passport) {
            $query->whereHas('applicants', function ($q) use ($passport) {
                $q->where('passport_number', 'like', '%' . $passport . '%');
            });
        }

        $applications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $metrics = [
            'total' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Rejected')->count(),
            'pending' => VisaApplication::where('visa_officer_id', $user->id)->whereIn('status', ['Pending', 'Submitted', 'Under Review'])->count(),
            'approved' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Approved')->count(),
            'rejected' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Rejected')->count(),
            'issued' => VisaApplication::where('visa_officer_id', $user->id)->where('status', 'Issued')->count(),
            'today' => VisaApplication::where('visa_officer_id', $user->id)->whereDate('created_at', today())->count(),
        ];

        $visaTypes = VisaType::where('is_active', true)->get();
        $agents = TravelAgent::select('id', 'company_name')->orderBy('company_name')->get();

        return view('admin.visa-management', compact('applications', 'metrics', 'visaTypes', 'agents'))->with('rejected_view', true);
    }
}
