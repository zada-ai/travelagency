<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TravelAgent;
use App\Models\VisaApplication;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class TravelAgentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('travel_agents.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $agent = TravelAgent::where('email', $request->email)->first();

        if (! $agent || ! Hash::check($request->password, $agent->password)) {
            return back()->withErrors(['email' => 'The provided credentials are incorrect.'])->withInput();
        }

        if ($agent->status === 'Pending') {
            return back()->withErrors(['email' => 'Your account is waiting for approval.'])->withInput();
        }

        if ($agent->status === 'Rejected') {
            return back()->withErrors(['email' => 'Your account has been rejected. Please contact support.'])->withInput();
        }

        Auth::guard('travel_agent')->login($agent, $request->boolean('remember'));

        return redirect()->route('travel-agents.dashboard');
    }

    public function showForgotPasswordForm()
    {
        return view('travel_agents.passwords.email');
    }

    public function dashboard()
    {
        Log::info('Travel Agent Guard', [
            'travel_agent' => Auth::guard('travel_agent')->check(),
            'web' => Auth::check(),
            'user' => Auth::user(),
            'travelAgent' => Auth::guard('travel_agent')->user(),
        ]);

        $agent = Auth::guard('travel_agent')->user();
        $internalUser = auth()->user();

        $internalUserIsVisaOfficer = $internalUser && (
            (method_exists($internalUser, 'hasRole') && ($internalUser->hasRole('visa_office') || $internalUser->hasRole('Visa Officer')))
            || in_array(strtolower($internalUser->role ?? ''), ['visa_office', 'visa officer', 'visa_officer'], true)
            || in_array(strtolower($internalUser->designation ?? ''), ['visa officer', 'visa_officer'], true)
            || str_contains(strtolower($internalUser->email ?? ''), 'officer')
            || str_contains(strtolower($internalUser->name ?? ''), 'visa officer')
        );

        // If neither guard has a user, redirect to agent login
        if (! $agent && ! $internalUser) {
            return redirect()->route('travel-agents.login');
        }

        $viewAgent = null;
        $userRole = null;
        $visaOfficerId = null;

        if ($internalUserIsVisaOfficer) {
            return redirect()->route('visa-office.dashboard');
        }

        if ($agent) {
            if ($agent->status !== 'Approved') {
                Auth::guard('travel_agent')->logout();

                return redirect()->route('travel-agents.login')->withErrors([
                    'email' => 'Your account is not approved yet or has been rejected.',
                ]);
            }

            $viewAgent = $agent;
            $userRole = $agent->role ?? 'travel_agent';
            $visaOfficerId = null;
        } else {
            $viewAgent = (object) [
                'company_name' => $internalUser->name ?? ($internalUser->email ?? 'Officer'),
                'first_name' => explode(' ', trim($internalUser->name ?? 'Officer'))[0] ?? 'Officer',
                'name' => $internalUser->name ?? null,
                'email' => $internalUser->email ?? null,
                'mobile' => $internalUser->phone ?? null,
                'phone' => $internalUser->phone ?? null,
                'city' => $internalUser->city ?? null,
                'country' => $internalUser->country ?? null,
                'company_address' => $internalUser->department ?? null,
                'department' => $internalUser->department ?? null,
                'company_logo' => null,
                'dts_license' => null,
                'cnic_front' => null,
                'cnic_back' => null,
                'status' => 'Active',
                'remarks' => null,
                'created_at' => $internalUser->created_at ?? now(),
                'employee_id' => $internalUser->employee_id ?? null,
                'designation' => $internalUser->designation ?? null,
                'role' => method_exists($internalUser, 'getRoleNames') ? Str::slug($internalUser->getRoleNames()->first() ?? 'web_user', '_') : 'web_user',
            ];

            $userRole = $internalUser->role ?? (method_exists($internalUser, 'getRoleNames') && $internalUser->getRoleNames()->first() ? Str::slug($internalUser->getRoleNames()->first(), '_') : 'web_user');
            $visaOfficerId = $internalUser->id;
            $agent = $viewAgent;
        }

        // Prepare visa-officer specific dashboard data when applicable
        $totalAssigned = 0;
        $pending = 0;
        $underReview = 0;
        $documentsRequired = 0;
        $approved = 0;
        $rejected = 0;
        $issuedToday = 0;
        $todaysTasks = 0;

        $recentApplications = collect();
        $pendingReviews = collect();
        $recentlyIssuedVisas = collect();
        $upcomingPassportExpiry = collect();
        $recentNotifications = collect();

        if (isset($visaOfficerId)) {
            $query = VisaApplication::where('visa_officer_id', $visaOfficerId);

            $totalAssigned = (int) $query->count();
            $pending = (int) (clone $query)->where('status', 'Pending')->count();
            $underReview = (int) (clone $query)->where('status', 'Under Review')->count();
            $documentsRequired = (int) (clone $query)->where('status', 'Documents Required')->count();
            $approved = (int) (clone $query)->where('status', 'Approved')->count();
            $rejected = (int) (clone $query)->where('status', 'Rejected')->count();
            $issuedToday = (int) (clone $query)->where('status', 'Issued')
                ->whereDate('updated_at', Carbon::today())
                ->count();

            $todaysTasks = (int) (clone $query)->whereDate('created_at', Carbon::today())->count();

            $recentApplications = (clone $query)->orderByDesc('created_at')->limit(10)->get();
            $pendingReviews = (clone $query)->whereIn('status', ['Pending', 'Documents Required'])->orderByDesc('created_at')->limit(10)->get();
            $recentlyIssuedVisas = (clone $query)->where('status', 'Issued')->orderByDesc('updated_at')->limit(10)->get();
            $upcomingPassportExpiry = VisaApplication::where('visa_officer_id', $visaOfficerId)
                ->whereHas('applicants', function ($applicantQuery) {
                    $applicantQuery->whereBetween('passport_expiry_date', [Carbon::today(), Carbon::today()->addDays(90)]);
                })
                ->with(['applicants' => function ($applicantQuery) {
                    $applicantQuery->whereBetween('passport_expiry_date', [Carbon::today(), Carbon::today()->addDays(90)]);
                }])
                ->get()
                ->sortBy(function ($application) {
                    return $application->applicants->min('passport_expiry_date');
                })
                ->take(10);
        }

        Log::info('Dashboard Role', [
            'userRole' => $userRole,
            'agent' => $agent,
            'viewAgent' => $viewAgent,
        ]);

        $subAgents = collect();
        if ($agent instanceof TravelAgent) {
            $subAgents = $agent->subAgents()->orderByDesc('created_at')->get();
        }

        return view('travel_agents.dashboard', compact(
            'agent',
            'viewAgent',
            'userRole',
            'totalAssigned',
            'pending',
            'underReview',
            'documentsRequired',
            'approved',
            'rejected',
            'issuedToday',
            'todaysTasks',
            'recentApplications',
            'pendingReviews',
            'recentlyIssuedVisas',
            'upcomingPassportExpiry',
            'recentNotifications',
            'subAgents'
        ));

    }

    public function tickets(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();

        $tickets = Ticket::query()
            ->forPortal('agent')
            ->when($request->filled('from'), fn ($query) => $query->where('route', 'like', '%' . $request->input('from') . '%'))
            ->when($request->filled('to'), fn ($query) => $query->where('route', 'like', '%' . $request->input('to') . '%'))
            ->when($request->filled('departure'), fn ($query) => $query->whereDate('departure_date', $request->input('departure')))
            ->when($request->filled('return'), fn ($query) => $query->whereDate('return_date', $request->input('return')))
            ->when($request->filled('airline'), fn ($query) => $query->where('airline', 'like', '%' . $request->input('airline') . '%'))
            ->whereNotIn('status', ['Cancelled', 'Rejected'])
            ->orderByDesc('created_at')
            ->get();

        return view('travel_agents.agenttickets.agentticket', compact('agent', 'tickets'));
    }

    public function logout(Request $request)
    {
        Auth::guard('travel_agent')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('travel-agents.login');
    }
}
