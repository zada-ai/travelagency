<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Commission;
use App\Models\FlightBooking;
use App\Models\VisaApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelAgentReportsController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('travel_agent')->user();
        
        return view('travel_agents.reports.index', compact('agent'));
    }

    public function bookingReport(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $query = Booking::query()->where('travel_agent_id', $agent->id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderByDesc('created_at')->get();

        if ($request->export === 'pdf') {
            return $this->exportPdf($bookings, 'booking-report');
        }

        if ($request->export === 'excel') {
            return $this->exportExcel($bookings, 'booking-report');
        }

        return view('travel_agents.reports.booking', compact('agent', 'bookings'));
    }

    public function salesReport(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $hotelBookings = Booking::where('travel_agent_id', $agent->id)
            ->when($request->filled('from_date') && $request->filled('to_date'), function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->from_date, $request->to_date]);
            })
            ->get();

        $flightBookings = FlightBooking::where('travel_agent_id', $agent->id)
            ->when($request->filled('from_date') && $request->filled('to_date'), function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->from_date, $request->to_date]);
            })
            ->get();

        $totalSales = $hotelBookings->sum('grand_total') + $flightBookings->sum('grand_total');

        return view('travel_agents.reports.sales', compact(
            'agent',
            'hotelBookings',
            'flightBookings',
            'totalSales'
        ));
    }

    public function commissionReport(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $query = Commission::where('travel_agent_id', $agent->id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $commissions = $query->orderByDesc('created_at')->get();

        return view('travel_agents.reports.commission', compact('agent', 'commissions'));
    }

    public function visaReport(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $query = VisaApplication::where('travel_agent_id', $agent->id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $visaApplications = $query->orderByDesc('created_at')->get();

        return view('travel_agents.reports.visa', compact('agent', 'visaApplications'));
    }

    public function ticketReport(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $query = FlightBooking::where('travel_agent_id', $agent->id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $bookings = $query->orderByDesc('created_at')->get();

        return view('travel_agents.reports.ticket', compact('agent', 'bookings'));
    }

    public function hotelReport(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $query = Booking::where('travel_agent_id', $agent->id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $bookings = $query->orderByDesc('created_at')->get();

        return view('travel_agents.reports.hotel', compact('agent', 'bookings'));
    }

    public function paymentReport(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $hotelPayments = Booking::where('travel_agent_id', $agent->id)
            ->selectRaw('payment_status, COUNT(*) as count, SUM(grand_total) as total')
            ->groupBy('payment_status')
            ->get();

        $flightPayments = FlightBooking::where('travel_agent_id', $agent->id)
            ->selectRaw('payment_status, COUNT(*) as count, SUM(grand_total) as total')
            ->groupBy('payment_status')
            ->get();

        return view('travel_agents.reports.payment', compact(
            'agent',
            'hotelPayments',
            'flightPayments'
        ));
    }

    public function customerReport(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $hotelCustomers = Booking::where('travel_agent_id', $agent->id)
            ->select('contact_name', 'contact_email', 'contact_phone')
            ->distinct()
            ->get();

        $flightCustomers = FlightBooking::where('travel_agent_id', $agent->id)
            ->select('contact_name', 'contact_email', 'contact_phone')
            ->distinct()
            ->get();

        $visaCustomers = \App\Models\VisaApplicant::whereHas('application', function ($q) use ($agent) {
                $q->where('travel_agent_id', $agent->id);
            })
            ->select('full_name as customer_name', 'passport_number', 'nationality')
            ->distinct()
            ->get();

        return view('travel_agents.reports.customer', compact(
            'agent',
            'hotelCustomers',
            'flightCustomers',
            'visaCustomers'
        ));
    }

    private function exportPdf($data, $filename)
    {
        // PDF export implementation would go here
        // For now, return a placeholder response
        return response()->json(['message' => 'PDF export functionality to be implemented']);
    }

    private function exportExcel($data, $filename)
    {
        // Excel export implementation would go here
        // For now, return a placeholder response
        return response()->json(['message' => 'Excel export functionality to be implemented']);
    }
}
