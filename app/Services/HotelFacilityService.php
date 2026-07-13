<?php

namespace App\Services;

use App\Models\HotelFacility;
use App\Repositories\HotelFacilityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HotelFacilityService
{
    public function __construct(private HotelFacilityRepository $repository)
    {
    }

    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data): HotelFacility
    {
        return $this->repository->create($data);
    }

    public function update(HotelFacility $facility, array $data): HotelFacility
    {
        return $this->repository->update($facility, $data);
    }

    public function delete(HotelFacility $facility): bool
    {
        return $this->repository->delete($facility);
    }

    public function export(array $filters): Collection
    {
        return $this->repository->export($filters);
    }
}
