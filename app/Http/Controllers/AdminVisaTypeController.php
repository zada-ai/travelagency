<?php

namespace App\Http\Controllers;

use App\Models\VisaType;
use Illuminate\Http\Request;

class AdminVisaTypeController extends Controller
{
    public function index()
    {
        $visaTypes = VisaType::orderBy('name')->get();
        return view('admin.visa_types.index', compact('visaTypes'));
    }

    public function create()
    {
        return view('admin.visa_types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:visa_types,code',
            'description' => 'nullable|string',
            'base_fee' => 'required|numeric|min:0',
            'service_charge' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        VisaType::create($validated);
        return redirect()->route('admin.visa-types.index')->with('success', 'Visa Type created successfully!');
    }

    public function edit(VisaType $visaType)
    {
        return view('admin.visa_types.edit', compact('visaType'));
    }

    public function update(Request $request, VisaType $visaType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:visa_types,code,' . $visaType->id,
            'description' => 'nullable|string',
            'base_fee' => 'required|numeric|min:0',
            'service_charge' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $visaType->update($validated);
        return redirect()->route('admin.visa-types.index')->with('success', 'Visa Type updated successfully!');
    }

    public function destroy(VisaType $visaType)
    {
        $visaType->delete();
        return redirect()->route('admin.visa-types.index')->with('success', 'Visa Type deleted successfully!');
    }
}
