<?php

namespace App\Services;

use App\Models\HotelRoomInventory;
use App\Repositories\HotelRoomInventoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HotelRoomInventoryService
{
    public function __construct(private HotelRoomInventoryRepository $repository)
    {
    }

    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data): HotelRoomInventory
    {
        return $this->repository->create($data);
    }

    public function update(HotelRoomInventory $inventory, array $data): HotelRoomInventory
    {
        return $this->repository->update($inventory, $data);
    }

    public function delete(HotelRoomInventory $inventory): bool
    {
        return $this->repository->delete($inventory);
    }

    public function export(array $filters): Collection
    {
        return $this->repository->export($filters);
    }
}
