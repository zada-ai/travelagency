<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAirportRequest;
use App\Models\Airport;
use Illuminate\Http\Request;

class AdminAirportController extends Controller
{
    public function index()
    {
        $airports = Airport::orderBy('city')->orderBy('code')->paginate(15);

        return view('admin.airports.index', compact('airports'));
    }

    public function create()
    {
        return view('admin.airports.create');
    }

    public function store(StoreAirportRequest $request)
    {
        Airport::create($request->validated());

        return redirect()->route('admin.airports.index')->with('success', 'Airport created successfully.');
    }

    public function edit(Airport $airport)
    {
        return view('admin.airports.edit', compact('airport'));
    }

    public function update(StoreAirportRequest $request, Airport $airport)
    {
        $airport->update($request->validated());

        return redirect()->route('admin.airports.index')->with('success', 'Airport updated successfully.');
    }

    public function destroy(Airport $airport)
    {
        $airport->delete();

        return redirect()->route('admin.airports.index')->with('success', 'Airport deleted successfully.');
    }
}
