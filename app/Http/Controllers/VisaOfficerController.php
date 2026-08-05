<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\VisaApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisaOfficerController extends Controller
{
    protected function getVisaOfficerUser()
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        $hasRole = method_exists($user, 'hasRole') && (
            $user->hasRole('Visa Officer')
            || $user->hasRole('visa_office')
            || $user->hasRole('visa officer')
        );

        $directRole = strtolower((string) ($user->role ?? ''));
        $directDesignation = strtolower((string) ($user->designation ?? ''));

        if (! $hasRole && ! in_array($directRole, ['visa_officer', 'visa officer', 'visa_office'], true)
            && ! in_array($directDesignation, ['visa_officer', 'visa officer', 'visa_office'], true)) {
            abort(403);
        }

        return $user;
    }

    protected function officerQuery(): Builder
    {
        return VisaApplication::with(['visaType', 'travelAgent', 'visaOfficer', 'applicants', 'customer'])
            ->where('visa_officer_id', $this->getVisaOfficerUser()->id);
    }

    protected function getOfficerMetrics(): array
    {
        $user = $this->getVisaOfficerUser();
        $query = VisaApplication::where('visa_officer_id', $user->id);

        return [
            'total' => $query->count(),
            'pending' => (clone $query)->whereIn('status', ['Pending', 'Submitted', 'Under Review'])->count(),
            'under_review' => (clone $query)->where('status', 'Under Review')->count(),
            'documents_required' => (clone $query)->where(function ($sub) {
                $sub->whereNull('passport_copy')
                    ->orWhereNull('cnic_copy')
                    ->orWhereNull('photograph')
                    ->orWhereNull('vaccination_certificate')
                    ->orWhere('status', 'Documents Required');
            })->count(),
            'approved' => (clone $query)->where('status', 'Approved')->count(),
            'rejected' => (clone $query)->where('status', 'Rejected')->count(),
            'issued' => (clone $query)->where('status', 'Issued')->count(),
            'today' => (clone $query)->whereDate('created_at', today())->count(),
        ];
    }

    public function dashboard()
    {
        $user = $this->getVisaOfficerUser();
        $query = VisaApplication::where('visa_officer_id', $user->id);

        $metrics = [
            'totalAssigned' => $query->count(),
            'pending' => (clone $query)->where('status', 'Pending')->count(),
            'underReview' => (clone $query)->where('status', 'Under Review')->count(),
            'documentsRequired' => (clone $query)->where(function ($sub) {
                $sub->whereNull('passport_copy')
                    ->orWhereNull('cnic_copy')
                    ->orWhereNull('photograph')
                    ->orWhereNull('vaccination_certificate')
                    ->orWhere('status', 'Documents Required');
            })->count(),
            'approved' => (clone $query)->where('status', 'Approved')->count(),
            'rejected' => (clone $query)->where('status', 'Rejected')->count(),
            'issuedToday' => (clone $query)->where('status', 'Issued')->whereDate('updated_at', today())->count(),
            'todaysTasks' => (clone $query)->whereDate('created_at', today())->count(),
        ];

        $recentApplications = (clone $query)->orderByDesc('created_at')->limit(10)->get();
        $pendingReviews = (clone $query)->whereIn('status', ['Pending', 'Documents Required'])->orderByDesc('created_at')->limit(10)->get();
        $recentlyIssuedVisas = (clone $query)->where('status', 'Issued')->orderByDesc('updated_at')->limit(10)->get();
        $recentNotifications = Notification::where('user_id', $user->id)->latest()->limit(10)->get();

        return view('visa_officer.dashboard', array_merge(
            [
                'agent' => $user,
            ],
            $metrics,
            compact('recentApplications', 'pendingReviews', 'recentlyIssuedVisas', 'recentNotifications')
        ));
    }

    public function visaManagement(Request $request)
    {
        $this->getVisaOfficerUser();

        $query = $this->officerQuery();
        $search = $request->input('search');
        $passport = $request->input('passport');
        $status = $request->input('status');

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

        $applications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $metrics = $this->getOfficerMetrics();

        return view('visa_officer.applications.index', [
            'title' => 'Visa Management',
            'description' => 'Manage all applications assigned to you across the officer workflow.',
            'applications' => $applications,
            'metrics' => $metrics,
        ]);
    }

    public function assigned(Request $request)
    {
        $this->getVisaOfficerUser();

        $query = $this->officerQuery()->where('status', 'Assigned');
        $search = $request->input('search');
        $passport = $request->input('passport');

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
        $metrics = $this->getOfficerMetrics();

        return view('visa_officer.applications.index', [
            'title' => 'Assigned Applications',
            'description' => 'Applications currently assigned to your desk for verification and review.',
            'applications' => $applications,
            'metrics' => $metrics,
        ]);
    }

    public function documentQueue(Request $request)
    {
        $this->getVisaOfficerUser();

        $query = $this->officerQuery()
            ->whereIn('status', ['Assigned', 'Under Review'])
            ->where(function ($sub) {
                $sub->whereNull('passport_copy')
                    ->orWhereNull('cnic_copy')
                    ->orWhereNull('photograph')
                    ->orWhereNull('vaccination_certificate');
            });

        $search = $request->input('search');
        $passport = $request->input('passport');

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
        $metrics = $this->getOfficerMetrics();

        return view('visa_officer.applications.index', [
            'title' => 'Document Verification Queue',
            'description' => 'Review documents and follow up on applications that require additional uploads.',
            'applications' => $applications,
            'metrics' => $metrics,
        ]);
    }

    public function issued(Request $request)
    {
        $this->getVisaOfficerUser();

        $query = $this->officerQuery()->where('status', 'Issued');
        $search = $request->input('search');
        $passport = $request->input('passport');

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

        $applications = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();
        $metrics = $this->getOfficerMetrics();

        return view('visa_officer.applications.index', [
            'title' => 'Issued Visas',
            'description' => 'Applications that have completed the officer workflow and have been issued.',
            'applications' => $applications,
            'metrics' => $metrics,
        ]);
    }

    public function rejected(Request $request)
    {
        $this->getVisaOfficerUser();

        $query = $this->officerQuery()->where('status', 'Rejected');
        $search = $request->input('search');
        $passport = $request->input('passport');

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

        $applications = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();
        $metrics = $this->getOfficerMetrics();

        return view('visa_officer.applications.index', [
            'title' => 'Rejected Applications',
            'description' => 'Applications marked rejected during review or document verification.',
            'applications' => $applications,
            'metrics' => $metrics,
        ]);
    }

    public function profile()
    {
        $user = $this->getVisaOfficerUser();

        return view('visa_officer.profile', [
            'user' => $user,
        ]);
    }

    public function notifications()
    {
        $user = $this->getVisaOfficerUser();

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(50)
            ->get();

        return view('visa_officer.notifications', compact('notifications'));
    }

    public function reports(Request $request)
    {
        $this->getVisaOfficerUser();

        $query = $this->officerQuery();
        $search = $request->input('search');
        $status = $request->input('status');

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($status) {
            $query->where('status', $status);
        }

        $applicationsByStatus = $query->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $metrics = $this->getOfficerMetrics();

        return view('visa_officer.reports', [
            'metrics' => $metrics,
            'applicationsByStatus' => $applicationsByStatus,
        ]);
    }

    public function markNotificationRead(Notification $notification)
    {
        $user = $this->getVisaOfficerUser();

        if ($notification->user_id !== $user->id) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllNotificationsRead()
    {
        $user = $this->getVisaOfficerUser();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function show(VisaApplication $visaApplication)
    {
        $user = $this->getVisaOfficerUser();

        if ($visaApplication->visa_officer_id !== $user->id) {
            abort(403);
        }

        $visaApplication->load(['visaType', 'travelAgent', 'visaOfficer']);

        return view('visa_officer.applications.show', [
            'application' => $visaApplication,
        ]);
    }

    public function updateStatus(Request $request, VisaApplication $visaApplication)
    {
        $user = $this->getVisaOfficerUser();

        if ($visaApplication->visa_officer_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|string',
            'remarks' => 'nullable|string',
            'visa_copy' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:5120',
        ]);

        $status = $validated['status'];
        $visaApplication->status = $status;

        if (! empty($validated['remarks'])) {
            $visaApplication->remarks = $validated['remarks'];
        }

        if ($status === 'Issued' && $request->hasFile('visa_copy')) {
            $path = $request->file('visa_copy')->store('visa_docs', 'public');
            $visaApplication->visa_copy = $path;
        }

        $visaApplication->save();

        return back()->with('success', "Visa Application status updated to {$status} successfully.");
    }

    public function print(VisaApplication $visaApplication)
    {
        $user = $this->getVisaOfficerUser();

        if ($visaApplication->visa_officer_id !== $user->id) {
            abort(403);
        }

        $visaApplication->load(['visaType', 'travelAgent', 'visaOfficer']);

        return view('visa_officer.applications.print', ['application' => $visaApplication]);
    }

    public function downloadDocument(VisaApplication $visaApplication, $field)
    {
        $user = $this->getVisaOfficerUser();

        if ($visaApplication->visa_officer_id !== $user->id) {
            abort(403);
        }

        $allowedFields = ['passport_copy', 'cnic_copy', 'photograph', 'vaccination_certificate', 'visa_copy'];
        if (! in_array($field, $allowedFields, true)) {
            abort(404);
        }

        $path = $visaApplication->$field;
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return back()->withErrors(['document' => 'File not found.']);
        }

        return Storage::disk('public')->download($path);
    }
}
