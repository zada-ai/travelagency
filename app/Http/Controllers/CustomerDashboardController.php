<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FlightBooking;
use App\Models\VisaApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless($user && $user->hasRole('Customer'), 403);

        $customer = $user->customer()->first();

        $visaApplicationsQuery = VisaApplication::query();

        if ($customer) {
            $visaApplicationsQuery->where('customer_id', $customer->id);
        } else {
            $name = $user->name;
            $email = $user->email;

            $visaApplicationsQuery->whereHas('customer', function ($q) use ($name, $email) {
                $q->where('first_name', 'like', '%' . $name . '%')
                  ->orWhere('last_name', 'like', '%' . $name . '%')
                  ->orWhereHas('user', function ($q2) use ($email) {
                      $q2->where('email', 'like', '%' . $email . '%');
                  });
            });
        }

        $visaApplications = $visaApplicationsQuery->orderByDesc('created_at')->limit(10)->get();

        $hotelBookings = Booking::query()
            ->where('contact_email', $user->email)
            ->orWhere('contact_name', 'like', '%' . ($customer?->first_name ?? $user->name) . '%')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $flightBookings = FlightBooking::query()
            ->where(function ($q) use ($user, $customer) {
                $q->where('user_id', $user->id)
                    ->orWhere('contact_email', $user->email)
                    ->orWhere('contact_name', 'like', '%' . ($customer?->first_name ?? $user->name) . '%');
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard.customer', compact(
            'user',
            'customer',
            'visaApplications',
            'hotelBookings',
            'flightBookings'
        ))->with('userRole', 'customer');
    }
}
