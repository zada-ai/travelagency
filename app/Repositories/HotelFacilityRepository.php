<?php

namespace App\Repositories;

use App\Models\HotelFacility;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HotelFacilityRepository
{
    public function query(): Builder
    {
        return HotelFacility::with('hotel');
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query();

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function (Builder $sub) use ($term) {
                $sub->where('facility_name', 'like', $term)
                    ->orWhere('facility_code', 'like', $term)
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

    public function create(array $data): HotelFacility
    {
        return HotelFacility::create($data);
    }

    public function update(HotelFacility $facility, array $data): HotelFacility
    {
        $facility->update($data);

        return $facility;
    }

    public function delete(HotelFacility $facility): bool
    {
        return $facility->delete();
    }

    public function export(array $filters): Collection
    {
        return $this->paginate($filters, 1000)->getCollection();
    }
}
