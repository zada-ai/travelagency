<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelAgentCommissionController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $query = Commission::query()
            ->where('travel_agent_id', $agent->id);

        // Filters
        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('created_at', $request->month)
                  ->whereYear('created_at', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('booking_type')) {
            $query->where('booking_type', $request->booking_type);
        }

        $commissions = $query->orderByDesc('created_at')->paginate(15);

        // Dashboard cards data
        $totalCommission = Commission::where('travel_agent_id', $agent->id)->sum('commission_amount');
        $thisMonthCommission = Commission::where('travel_agent_id', $agent->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('commission_amount');
        $pendingCommission = Commission::where('travel_agent_id', $agent->id)
            ->where('payment_status', 'pending')
            ->sum('commission_amount');
        $paidCommission = Commission::where('travel_agent_id', $agent->id)
            ->where('payment_status', 'paid')
            ->sum('commission_amount');

        return view('travel_agents.commission.index', compact(
            'agent',
            'commissions',
            'totalCommission',
            'thisMonthCommission',
            'pendingCommission',
            'paidCommission'
        ));
    }
}
