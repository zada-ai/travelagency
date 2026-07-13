<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTravelAgentRequest;
use App\Models\TravelAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminTravelAgentController extends Controller
{
    public function index(Request $request)
    {
        $query = TravelAgent::query();

        if ($request->filled('q')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('company_name', 'like', '%'.$request->q.'%')
                    ->orWhere('first_name', 'like', '%'.$request->q.'%')
                    ->orWhere('last_name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%')
                    ->orWhere('mobile', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('status') && in_array($request->status, ['Pending', 'Approved', 'Rejected'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $agents = $query->latest('created_at')->paginate(10)->withQueryString();
        $countries = TravelAgent::select('country')->distinct()->orderBy('country')->pluck('country');

        $metrics = [
            'total' => TravelAgent::count(),
            'pending' => TravelAgent::where('status', 'Pending')->count(),
            'approved' => TravelAgent::where('status', 'Approved')->count(),
            'rejected' => TravelAgent::where('status', 'Rejected')->count(),
        ];

        return view('admin.agents.index', compact('agents', 'countries', 'metrics'));
    }

    public function show(TravelAgent $agent)
    {
        return view('admin.agents.show', compact('agent'));
    }

    public function edit(TravelAgent $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(UpdateTravelAgentRequest $request, TravelAgent $agent)
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('company_logo')) {
            Storage::disk('public')->delete($agent->company_logo);
            $data['company_logo'] = $request->file('company_logo')->store('travel_agents/logos', 'public');
        }

        if ($request->hasFile('dts_license')) {
            Storage::disk('public')->delete($agent->dts_license);
            $data['dts_license'] = $request->file('dts_license')->store('travel_agents/licenses', 'public');
        }

        if ($request->hasFile('cnic_front')) {
            Storage::disk('public')->delete($agent->cnic_front);
            $data['cnic_front'] = $request->file('cnic_front')->store('travel_agents/cnic', 'public');
        }

        if ($request->hasFile('cnic_back')) {
            Storage::disk('public')->delete($agent->cnic_back);
            $data['cnic_back'] = $request->file('cnic_back')->store('travel_agents/cnic', 'public');
        }

        $data['updated_by'] = Auth::id();
        $agent->update($data);

        return redirect()->route('admin.agents.show', $agent)->with('success', 'Agent profile updated successfully.');
    }

    public function approve(TravelAgent $agent)
    {
        $agent->update([
            'status' => 'Approved',
            'remarks' => null,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Agent approved successfully.');
    }

    public function reject(Request $request, TravelAgent $agent)
    {
        $request->validate([
            'remarks' => ['required', 'string', 'max:1000'],
        ]);

        $agent->update([
            'status' => 'Rejected',
            'remarks' => $request->remarks,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Agent registration rejected successfully.');
    }

    public function destroy(TravelAgent $agent)
    {
        Storage::disk('public')->delete([
            $agent->company_logo,
            $agent->dts_license,
            $agent->cnic_front,
            $agent->cnic_back,
        ]);

        $agent->delete();

        return redirect()->route('admin.agents.index')->with('success', 'Agent record deleted successfully.');
    }

    public function exportCsv(Request $request)
    {
        $agents = $this->filteredAgents($request)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="travel_agents.csv"',
        ];

        $callback = function () use ($agents) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Company Name', 'Owner Name', 'Email', 'Mobile', 'Country', 'City', 'Status', 'Registered At']);

            foreach ($agents as $agent) {
                fputcsv($handle, [
                    $agent->company_name,
                    trim($agent->first_name.' '.$agent->last_name),
                    $agent->email,
                    $agent->mobile,
                    $agent->country,
                    $agent->city,
                    $agent->status,
                    $agent->created_at->toDateString(),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        $agents = $this->filteredAgents($request)->get();
        $filename = 'travel_agents.xls';
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $html = '<table border="1"><tr><th>Company Name</th><th>Owner Name</th><th>Email</th><th>Mobile</th><th>Country</th><th>City</th><th>Status</th><th>Registered At</th></tr>';

        foreach ($agents as $agent) {
            $html .= '<tr>' .
                '<td>'.e($agent->company_name).'</td>' .
                '<td>'.e(trim($agent->first_name.' '.$agent->last_name)).'</td>' .
                '<td>'.e($agent->email).'</td>' .
                '<td>'.e($agent->mobile).'</td>' .
                '<td>'.e($agent->country).'</td>' .
                '<td>'.e($agent->city).'</td>' .
                '<td>'.e($agent->status).'</td>' .
                '<td>'.e($agent->created_at->toDateString()).'</td>' .
                '</tr>';
        }

        $html .= '</table>';

        return response($html, 200, $headers);
    }

    protected function filteredAgents(Request $request)
    {
        $query = TravelAgent::query();

        if ($request->filled('q')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('company_name', 'like', '%'.$request->q.'%')
                    ->orWhere('first_name', 'like', '%'.$request->q.'%')
                    ->orWhere('last_name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%')
                    ->orWhere('mobile', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('status') && in_array($request->status, ['Pending', 'Approved', 'Rejected'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        return $query->latest('created_at');
    }
}
