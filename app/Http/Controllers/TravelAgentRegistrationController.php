<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelAgentRequest;
use App\Models\TravelAgent;

class TravelAgentRegistrationController extends Controller
{
    public function create()
    {
        return view('travel_agents.register');
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
        $requestData['created_by'] = auth()->id();

        TravelAgent::create($requestData);

        return redirect()->route('travel-agents.register.success');
    }

    public function success()
    {
        return view('travel_agents.registration-success');
    }
}
