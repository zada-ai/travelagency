<?php

namespace App\Repositories;

use App\Models\HotelRoomType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HotelRoomTypeRepository
{
    public function query(): Builder
    {
        return HotelRoomType::with('hotel');
    }

    public function find(int $id): ?HotelRoomType
    {
        return HotelRoomType::find($id);
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query();

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function (Builder $sub) use ($term) {
                $sub->where('room_name', 'like', $term)
                    ->orWhere('room_code', 'like', $term)
                    ->orWhereHas('hotel', function (Builder $hotelQuery) use ($term) {
                        $hotelQuery->where('hotel_name', 'like', $term)
                            ->orWhere('hotel_code', 'like', $term);
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

    public function create(array $data): HotelRoomType
    {
        return HotelRoomType::create($data);
    }

    public function update(HotelRoomType $roomType, array $data): HotelRoomType
    {
        $roomType->update($data);

        return $roomType;
    }

    public function delete(HotelRoomType $roomType): bool
    {
        return $roomType->delete();
    }

    public function export(array $filters): Collection
    {
        return $this->paginate($filters, 1000)->getCollection();
    }
}
