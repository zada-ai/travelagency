<?php

namespace App\Http\Controllers;

use App\Models\VisaApplication;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TravelAgentCustomerVisaController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $query = VisaApplication::query()
            ->where('travel_agent_id', $agent->id)
            ->with(['visaType', 'visaOfficer', 'applicants', 'customer']);

        // Filters
        if ($request->filled('visa_type_id')) {
            $query->where('visa_type_id', $request->visa_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($subQuery) use ($request) {
                $search = $request->search;

                $subQuery->whereHas('customer', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('passport_no', 'like', "%{$search}%");
                })
                ->orWhereHas('applicants', function ($q) use ($search) {
                    $q->where('passport_number', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $visaApplications = $query->orderByDesc('created_at')->paginate(15);
        $visaTypes = VisaType::all();

        return view('travel_agents.customer-visa.index', compact('agent', 'visaApplications', 'visaTypes'));
    }

    public function show($id)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->with(['visaType', 'visaOfficer', 'customer', 'applicants'])
            ->firstOrFail();

        return view('travel_agents.customer-visa.show', compact('agent', 'visaApplication'));
    }

    public function downloadVisaCopy($id)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        if ($visaApplication->status !== 'Issued') {
            return back()->withErrors(['error' => 'Visa copy can only be downloaded when status is Issued.']);
        }

        if (!$visaApplication->visa_copy) {
            return back()->withErrors(['error' => 'Visa copy not available.']);
        }

        $path = $visaApplication->visa_copy;
        if (!Storage::disk('public')->exists($path)) {
            return back()->withErrors(['error' => 'Visa copy file was not found on disk.']);
        }

        return response()->download(Storage::disk('public')->path($path));
    }

    public function downloadDocument($id, $field)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $visaApplication = VisaApplication::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        $allowedFields = ['passport_copy', 'cnic_copy', 'photograph', 'vaccination_certificate'];
        
        if (!in_array($field, $allowedFields)) {
            return back()->withErrors(['error' => 'Invalid document field.']);
        }

        if (!$visaApplication->$field) {
            return back()->withErrors(['error' => 'Document not available.']);
        }

        $path = $visaApplication->$field;
        if (!Storage::disk('public')->exists($path)) {
            return back()->withErrors(['error' => 'Document file was not found on disk.']);
        }

        return response()->download(Storage::disk('public')->path($path));
    }
}
