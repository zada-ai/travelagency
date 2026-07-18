<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TravelAgent;
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

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::broker('travel_agents')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('travel_agents.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::broker('travel_agents')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($agent, $password) {
                $agent->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $agent->save();

                event(new PasswordReset($agent));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('travel-agents.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function dashboard()
    {
        $agent = Auth::guard('travel_agent')->user();

        if (! $agent || $agent->status !== 'Approved') {
            Auth::guard('travel_agent')->logout();

            return redirect()->route('travel-agents.login')->withErrors([
                'email' => 'Your account is not approved yet or has been rejected.',
            ]);
        }

        return view('travel_agents.dashboard', compact('agent'));
    }

    public function tickets()
    {
        $agent = Auth::guard('travel_agent')->user();
        $tickets = Ticket::where('status', 'Approved')
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
