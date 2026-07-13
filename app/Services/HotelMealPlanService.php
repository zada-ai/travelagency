<?php

namespace App\Services;

use App\Models\HotelMealPlan;
use App\Repositories\HotelMealPlanRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HotelMealPlanService
{
    public function __construct(private HotelMealPlanRepository $repository)
    {
    }

    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data): HotelMealPlan
    {
        return $this->repository->create($data);
    }

    public function update(HotelMealPlan $mealPlan, array $data): HotelMealPlan
    {
        return $this->repository->update($mealPlan, $data);
    }

    public function delete(HotelMealPlan $mealPlan): bool
    {
        return $this->repository->delete($mealPlan);
    }

    public function export(array $filters): Collection
    {
        return $this->repository->export($filters);
    }
}
