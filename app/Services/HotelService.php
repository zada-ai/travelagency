<?php

namespace App\Services;

use App\Models\Hotel;
use App\Repositories\HotelRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class HotelService
{
    public function __construct(private HotelRepository $repository)
    {
    }

    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data): Hotel
    {
        return $this->repository->create($data);
    }

    public function update(Hotel $hotel, array $data): Hotel
    {
        return $this->repository->update($hotel, $data);
    }

    public function delete(Hotel $hotel): bool
    {
        Storage::disk('public')->delete($hotel->images()->pluck('path')->all());

        return $this->repository->delete($hotel);
    }

    public function export(array $filters): Collection
    {
        return $this->repository->export($filters);
    }

    public function cities(): Collection
    {
        return $this->repository->allCities();
    }
}
