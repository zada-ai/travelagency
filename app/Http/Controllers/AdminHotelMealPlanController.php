<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelMealPlanRequest;
use App\Http\Requests\UpdateHotelMealPlanRequest;
use App\Models\Hotel;
use App\Models\HotelMealPlan;
use App\Services\HotelMealPlanService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminHotelMealPlanController extends Controller
{
    public function __construct(private HotelMealPlanService $service)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'hotel_id', 'status', 'sort', 'direction']);
        $mealPlans = $this->service->list($filters, 15);
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();

        return view('admin.hotel-meal-plans.index', compact('mealPlans', 'hotels', 'filters'));
    }

    public function create()
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();

        return view('admin.hotel-meal-plans.create', compact('hotels'));
    }

    public function store(StoreHotelMealPlanRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.hotel-meal-plans.index')->with('success', 'Meal plan created successfully.');
    }

    public function edit(HotelMealPlan $hotel_meal_plan)
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();

        return view('admin.hotel-meal-plans.edit', compact('hotel_meal_plan', 'hotels'));
    }

    public function update(UpdateHotelMealPlanRequest $request, HotelMealPlan $hotel_meal_plan)
    {
        $this->service->update($hotel_meal_plan, $request->validated());

        return redirect()->route('admin.hotel-meal-plans.index')->with('success', 'Meal plan updated successfully.');
    }

    public function destroy(HotelMealPlan $hotel_meal_plan)
    {
        $this->service->delete($hotel_meal_plan);

        return redirect()->route('admin.hotel-meal-plans.index')->with('success', 'Meal plan deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'hotel_id', 'status']);
        $mealPlans = $this->service->export($filters);
        $filename = 'meal_plans_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($mealPlans) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Hotel', 'Meal Plan', 'Code', 'Description', 'Price Per Person', 'Status']);
            foreach ($mealPlans as $plan) {
                fputcsv($handle, [
                    $plan->hotel?->hotel_name,
                    $plan->meal_plan_name,
                    $plan->meal_plan_code,
                    $plan->description,
                    $plan->price_per_person,
                    $plan->status,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
