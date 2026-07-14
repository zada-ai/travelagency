<?php

namespace App\Repositories;

use App\Models\HotelRoomInventory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HotelRoomInventoryRepository
{
    public function query(): Builder
    {
        return HotelRoomInventory::with(['hotel', 'roomType']);
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query();

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function (Builder $sub) use ($term) {
                $sub->whereHas('hotel', fn (Builder $hotelQuery) => $hotelQuery->where('hotel_name', 'like', $term))
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

        if (! empty($filters['date'])) {
            $query->where('inventory_date', $filters['date']);
        }

        $sort = $filters['sort'] ?? 'inventory_date';
        $direction = $filters['direction'] ?? 'desc';
        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        return $query->orderBy($sort, $direction)->paginate($perPage)->withQueryString();
    }

    public function create(array $data): HotelRoomInventory
    {
        if (! isset($data['inventory_date']) && isset($data['inventory_date_from'])) {
            $data['inventory_date'] = $data['inventory_date_from'];
        }

        if (! empty($data['inventory_date_to'])) {
            $start = 
                $data['inventory_date'] instanceof \DateTimeInterface
                    ? clone $data['inventory_date']
                    : new \DateTimeImmutable($data['inventory_date']);
            $end = new \DateTimeImmutable($data['inventory_date_to']);

            $current = $start;
            $firstInventory = null;

            while ($current <= $end) {
                $date = $current->format('Y-m-d');
                $attributes = [
                    'hotel_id' => $data['hotel_id'],
                    'hotel_room_type_id' => $data['hotel_room_type_id'],
                    'inventory_date' => $date,
                ];

                $payload = array_merge($data, [
                    'inventory_date' => $date,
                    'inventory_date_to' => $date,
                ]);

                $inventory = HotelRoomInventory::updateOrCreate($attributes, $payload);
                $firstInventory = $firstInventory ?? $inventory;
                $current = $current->modify('+1 day');
            }

            return $firstInventory;
        }

        if (isset($data['inventory_date'])) {
            $attributes = [
                'hotel_id' => $data['hotel_id'],
                'hotel_room_type_id' => $data['hotel_room_type_id'],
                'inventory_date' => $data['inventory_date'],
            ];

            // keep inventory_date_to in payload if provided
            $payload = $data;

            return HotelRoomInventory::updateOrCreate($attributes, $payload);
        }

        return HotelRoomInventory::create($data);
    }

    public function update(HotelRoomInventory $inventory, array $data): HotelRoomInventory
    {
        $duplicate = HotelRoomInventory::query()
            ->where('hotel_id', $data['hotel_id'] ?? $inventory->hotel_id)
            ->where('hotel_room_type_id', $data['hotel_room_type_id'] ?? $inventory->hotel_room_type_id)
            ->whereDate('inventory_date', $data['inventory_date'] ?? $inventory->inventory_date)
            ->where('id', '!=', $inventory->id)
            ->first();

        if ($duplicate) {
            $mergeData = array_merge($duplicate->toArray(), [
                'total_rooms' => (int) ($data['total_rooms'] ?? $duplicate->total_rooms),
                'available_rooms' => (int) ($data['available_rooms'] ?? $duplicate->available_rooms),
                'booked_rooms' => (int) ($data['booked_rooms'] ?? $duplicate->booked_rooms),
                'status' => $data['status'] ?? $duplicate->status,
            ]);

            $duplicate->fill($mergeData);
            $duplicate->save();
            $inventory->delete();

            return $duplicate;
        }

        $inventory->update($data);

        return $inventory;
    }

    public function delete(HotelRoomInventory $inventory): bool
    {
        return $inventory->delete();
    }

    public function export(array $filters): Collection
    {
        return $this->paginate($filters, 1000)->getCollection();
    }
}
