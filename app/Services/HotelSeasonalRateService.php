<?php

namespace App\Services;

use App\Models\HotelSeasonalRate;
use App\Repositories\HotelSeasonalRateRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HotelSeasonalRateService
{
    public function __construct(private HotelSeasonalRateRepository $repository)
    {
    }

    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data): HotelSeasonalRate
    {
        return $this->repository->create($data);
    }

    public function update(HotelSeasonalRate $seasonalRate, array $data): HotelSeasonalRate
    {
        return $this->repository->update($seasonalRate, $data);
    }

    public function delete(HotelSeasonalRate $seasonalRate): bool
    {
        return $this->repository->delete($seasonalRate);
    }

    public function export(array $filters): Collection
    {
        return $this->repository->export($filters);
    }
}
