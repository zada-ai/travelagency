<?php

namespace App\Services;

use App\Models\HotelRoomType;
use App\Repositories\HotelRoomTypeRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HotelRoomTypeService
{
    public function __construct(private HotelRoomTypeRepository $repository)
    {
    }

    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data): HotelRoomType
    {
        return $this->repository->create($data);
    }

    public function update(HotelRoomType $roomType, array $data): HotelRoomType
    {
        return $this->repository->update($roomType, $data);
    }

    public function delete(HotelRoomType $roomType): bool
    {
        return $this->repository->delete($roomType);
    }

    public function export(array $filters): Collection
    {
        return $this->repository->export($filters);
    }
}
