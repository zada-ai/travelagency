<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelAgentRequest;
use App\Http\Requests\UpdateTravelAgentRequest;
use App\Models\TravelAgent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AgentCompany;

class TravelAgentRegistrationController extends Controller
{
    public function create()
    {
        return view('travel_agents.register', [
            'action' => route('travel-agents.register.submit'),
            'pageTitle' => 'Signup Account',
            'buttonText' => 'Submit Application',
        ]);
    }

    public function store(StoreTravelAgentRequest $request)
    {
        $requestData = $request->validated();

        $requestData['password'] = bcrypt($requestData['password']);
        $requestData['company_logo'] = $request->file('company_logo')->store('travel_agents/logos', 'public');
        $requestData['dts_license'] = $request->file('dts_license')->store('travel_agents/licenses', 'public');
        $requestData['cnic_front'] = $request->file('cnic_front')->store('travel_agents/cnic', 'public');

        if ($request->hasFile('cnic_back')) {
            $requestData['cnic_back'] = $request->file('cnic_back')->store('travel_agents/cnic', 'public');
        } else {
            unset($requestData['cnic_back']);
        }

        $requestData['status'] = 'Pending';
        $requestData['created_by'] = null;
        $requestData['parent_agent_id'] = null;
AgentCompany::firstOrCreate(
    ['name' => trim($requestData['company_name'])],
    ['status' => true]
);
        TravelAgent::create($requestData);

        return redirect()->route('travel-agents.register.success');
    }

    public function createSubAgent()
    {
        return view('travel_agents.sub_agents.create', [
            'action' => route('travel-agents.sub-agents.store'),
            'pageTitle' => 'Create Sub-Agent',
            'buttonText' => 'Create Sub-Agent',
        ]);
    }

    public function storeSubAgent(StoreTravelAgentRequest $request)
    {
        $requestData = $request->validated();

        $requestData['password'] = bcrypt($requestData['password']);
        $requestData['company_logo'] = $request->file('company_logo')->store('travel_agents/logos', 'public');
        $requestData['dts_license'] = $request->file('dts_license')->store('travel_agents/licenses', 'public');
        $requestData['cnic_front'] = $request->file('cnic_front')->store('travel_agents/cnic', 'public');

        if ($request->hasFile('cnic_back')) {
            $requestData['cnic_back'] = $request->file('cnic_back')->store('travel_agents/cnic', 'public');
        } else {
            unset($requestData['cnic_back']);
        }

        $requestData['status'] = 'Approved';
        $requestData['created_by'] = Auth::guard('travel_agent')->id();
        $requestData['parent_agent_id'] = Auth::guard('travel_agent')->id();

        TravelAgent::create($requestData);

        return redirect()->route('travel-agents.dashboard')->with('success', 'Sub-agent created successfully.');
    }

    public function indexSubAgents()
    {
        $agent = Auth::guard('travel_agent')->user();
        $subAgents = $agent->subAgents()->orderByDesc('created_at')->get();

        return view('travel_agents.sub_agents.index', compact('subAgents'));
    }

    public function showSubAgent(TravelAgent $subAgent)
    {
        $this->authorizeSubAgent($subAgent);

        return view('travel_agents.sub_agents.show', compact('subAgent'));
    }

    public function editSubAgent(TravelAgent $subAgent)
    {
        $this->authorizeSubAgent($subAgent);

        return view('travel_agents.sub_agents.edit', [
            'subAgent' => $subAgent,
            'action' => route('travel-agents.sub-agents.update', $subAgent),
            'pageTitle' => 'Edit Sub-Agent',
            'buttonText' => 'Update Sub-Agent',
        ]);
    }

    public function updateSubAgent(UpdateTravelAgentRequest $request, TravelAgent $subAgent)
    {
        $this->authorizeSubAgent($subAgent);

        $requestData = $request->validated();

        if ($request->filled('password')) {
            $requestData['password'] = bcrypt($requestData['password']);
        } else {
            unset($requestData['password']);
        }

        if ($request->hasFile('company_logo')) {
            Storage::disk('public')->delete($subAgent->company_logo);
            $requestData['company_logo'] = $request->file('company_logo')->store('travel_agents/logos', 'public');
        }

        if ($request->hasFile('dts_license')) {
            Storage::disk('public')->delete($subAgent->dts_license);
            $requestData['dts_license'] = $request->file('dts_license')->store('travel_agents/licenses', 'public');
        }

        if ($request->hasFile('cnic_front')) {
            Storage::disk('public')->delete($subAgent->cnic_front);
            $requestData['cnic_front'] = $request->file('cnic_front')->store('travel_agents/cnic', 'public');
        }

        if ($request->hasFile('cnic_back')) {
            Storage::disk('public')->delete($subAgent->cnic_back);
            $requestData['cnic_back'] = $request->file('cnic_back')->store('travel_agents/cnic', 'public');
        }

        $subAgent->update($requestData);

        return redirect()->route('travel-agents.sub-agents.index')->with('success', 'Sub-agent updated successfully.');
    }

    public function destroySubAgent(TravelAgent $subAgent)
    {
        $this->authorizeSubAgent($subAgent);

        $subAgent->delete();

        return redirect()->route('travel-agents.sub-agents.index')->with('success', 'Sub-agent deleted successfully.');
    }

    protected function authorizeSubAgent(TravelAgent $subAgent)
    {
        if (Auth::guard('travel_agent')->id() !== $subAgent->parent_agent_id) {
            abort(403);
        }
    }

    public function success()
    {
        return view('travel_agents.registration-success');
    }
}
