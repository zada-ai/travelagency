<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAirlineRequest;
use App\Models\Airline;
use Illuminate\Http\Request;

class AdminAirlineController extends Controller
{
    public function index()
    {
        $airlines = Airline::orderBy('name')->paginate(15);

        return view('admin.airlines.index', compact('airlines'));
    }

    public function create()
    {
        return view('admin.airlines.create');
    }

    public function store(StoreAirlineRequest $request)
    {
        Airline::create($request->validated());

        return redirect()->route('admin.airlines.index')->with('success', 'Airline created successfully.');
    }

    public function edit(Airline $airline)
    {
        return view('admin.airlines.edit', compact('airline'));
    }

    public function update(StoreAirlineRequest $request, Airline $airline)
    {
        $airline->update($request->validated());

        return redirect()->route('admin.airlines.index')->with('success', 'Airline updated successfully.');
    }

    public function destroy(Airline $airline)
    {
        $airline->delete();

        return redirect()->route('admin.airlines.index')->with('success', 'Airline deleted successfully.');
    }
}
