<?php

namespace App\Repositories;

use App\Models\HotelMealPlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HotelMealPlanRepository
{
    public function query(): Builder
    {
        return HotelMealPlan::with('hotel');
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query();

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function (Builder $sub) use ($term) {
                $sub->where('meal_plan_name', 'like', $term)
                    ->orWhere('meal_plan_code', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhereHas('hotel', function (Builder $hotelQuery) use ($term) {
                        $hotelQuery->where('hotel_name', 'like', $term);
                    });
            });
        }

        if (! empty($filters['hotel_id'])) {
            $query->where('hotel_id', $filters['hotel_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sort = $filters['sort'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        return $query->orderBy($sort, $direction)->paginate($perPage)->withQueryString();
    }

    public function create(array $data): HotelMealPlan
    {
        return HotelMealPlan::create($data);
    }

    public function update(HotelMealPlan $mealPlan, array $data): HotelMealPlan
    {
        $mealPlan->update($data);

        return $mealPlan;
    }

    public function delete(HotelMealPlan $mealPlan): bool
    {
        return $mealPlan->delete();
    }

    public function export(array $filters): Collection
    {
        return $this->paginate($filters, 1000)->getCollection();
    }
}
