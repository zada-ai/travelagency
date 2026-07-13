<?php

namespace App\Repositories;

use App\Models\Hotel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HotelRepository
{
    public function query(): Builder
    {
        return Hotel::query();
    }

    public function find(int $id): ?Hotel
    {
        return Hotel::find($id);
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query();

        if (! empty($filters['search'])) {
            $query->where(function (Builder $subQuery) use ($filters) {
                $term = '%' . $filters['search'] . '%';
                $subQuery->where('hotel_name', 'like', $term)
                    ->orWhere('hotel_code', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhere('address', 'like', $term);
            });
        }

        if (! empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        $sort = $filters['sort'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        return $query->orderBy($sort, $direction)->paginate($perPage)->withQueryString();
    }

    public function allCities(): Collection
    {
        return $this->query()
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');
    }

    public function create(array $data): Hotel
    {
        return Hotel::create($data);
    }

    public function update(Hotel $hotel, array $data): Hotel
    {
        $hotel->update($data);

        return $hotel;
    }

    public function delete(Hotel $hotel): bool
    {
        return $hotel->delete();
    }

    public function export(array $filters): Collection
    {
        return $this->paginate($filters, 1000)->getCollection();
    }
}
