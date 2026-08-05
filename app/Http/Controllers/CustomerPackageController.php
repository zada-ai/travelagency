<?php

namespace App\Http\Controllers;

use App\Models\Package;

class CustomerPackageController extends Controller
{
    public function index()
    {
        $query = Package::query();

        // Travel Agent
        if (request()->routeIs('travel-agents.packages.index')) {
            $query->visibleToAgents();
        }

        // Customer
        elseif (request()->routeIs('packages.index', 'customer.packages.create')) {
            $query->visibleToCustomers();
        }

        else {
            abort(403);
        }

        $displayPackages = $query
            ->where('status', 'Active')
            ->where('available_seats', '>', 0)
            ->latest()
            ->paginate(20);

        // Public packages page
        if (request()->routeIs('packages.index')) {
            return view('public.packages.index', compact('displayPackages'));
        }

        // Customer + Travel Agent packages page
        return view('packages.index', compact('displayPackages'));
    }
}