<?php

namespace App\Http\Controllers;

use App\Models\TravelAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function dashboard()
    {
        $agent = Auth::guard('travel_agent')->user();

        return view('travel_agents.dashboard', compact('agent'));
    }

    public function logout(Request $request)
    {
        Auth::guard('travel_agent')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('travel-agents.login');
    }
}
