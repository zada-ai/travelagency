<?php

namespace App\Repositories;

use App\Models\HotelSeasonalRate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HotelSeasonalRateRepository
{
    public function query(): Builder
    {
        return HotelSeasonalRate::with(['hotel', 'roomType']);
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query();

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function (Builder $sub) use ($term) {
                $sub->where('season_name', 'like', $term)
                    ->orWhereHas('hotel', fn (Builder $hotelQuery) => $hotelQuery->where('hotel_name', 'like', $term))
                    ->orWhereHas('roomType', fn (Builder $roomQuery) => $roomQuery->where('room_name', 'like', $term));
            });
        }

        if (! empty($filters['hotel_id'])) {
            $query->where('hotel_id', $filters['hotel_id']);
        }

        if (! empty($filters['hotel_room_type_id'])) {
            $query->where('hotel_room_type_id', $filters['hotel_room_type_id']);
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

    public function create(array $data): HotelSeasonalRate
    {
        return HotelSeasonalRate::create($data);
    }

    public function update(HotelSeasonalRate $seasonalRate, array $data): HotelSeasonalRate
    {
        $seasonalRate->update($data);

        return $seasonalRate;
    }

    public function delete(HotelSeasonalRate $seasonalRate): bool
    {
        return $seasonalRate->delete();
    }

    public function export(array $filters): Collection
    {
        return $this->paginate($filters, 1000)->getCollection();
    }
}
