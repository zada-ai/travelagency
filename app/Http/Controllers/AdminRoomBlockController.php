<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomBlockRequest;
use App\Http\Requests\UpdateRoomBlockRequest;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\RoomBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminRoomBlockController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['hotel_id', 'status']);
        $hotels = Hotel::orderBy('hotel_name')->get(['id', 'hotel_name']);
        $blocks = RoomBlock::with(['hotel', 'room'])
            ->when($request->filled('hotel_id'), fn ($query) => $query->where('hotel_id', $request->hotel_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderByDesc('block_from')
            ->paginate(20)
            ->withQueryString();

        return view('admin.room-blocks.index', compact('blocks', 'hotels', 'filters'));
    }

    public function create()
    {
        $hotels = Hotel::orderBy('hotel_name')->get(['id', 'hotel_name']);
        $rooms = HotelRoom::orderBy('room_number')->get(['id', 'hotel_id', 'room_number']);

        return view('admin.room-blocks.create', compact('hotels', 'rooms'));
    }

    public function store(StoreRoomBlockRequest $request)
    {
        RoomBlock::create($request->validated());

        return redirect()->route('admin.room-blocks.index')->with('success', 'Room block created successfully.');
    }

    public function edit(RoomBlock $room_block)
    {
        $hotels = Hotel::orderBy('hotel_name')->get(['id', 'hotel_name']);
        $rooms = HotelRoom::orderBy('room_number')->get(['id', 'hotel_id', 'room_number']);

        return view('admin.room-blocks.edit', compact('room_block', 'hotels', 'rooms'));
    }

    public function update(UpdateRoomBlockRequest $request, RoomBlock $room_block)
    {
        $room_block->update($request->validated());

        return redirect()->route('admin.room-blocks.index')->with('success', 'Room block updated successfully.');
    }

    public function destroy(RoomBlock $room_block)
    {
        $room_block->delete();

        return redirect()->route('admin.room-blocks.index')->with('success', 'Room block removed successfully.');
    }

    public function calendar(Request $request)
    {
        $hotelId = $request->query('hotel_id');
        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth()->addDay();

        $hotels = Hotel::orderBy('hotel_name')->get(['id', 'hotel_name']);
        $rooms = HotelRoom::with(['bookings' => fn ($query) => $query->whereDate('check_in', '<', $end)->whereDate('check_out', '>', $start), 'blocks' => fn ($query) => $query->whereDate('block_from', '<', $end)->whereDate('block_to', '>', $start)])
            ->when($hotelId, fn ($query) => $query->where('hotel_id', $hotelId))
            ->orderBy('hotel_id')
            ->orderBy('room_number')
            ->get();

        $days = [];
        $cursor = $start->copy();
        while ($cursor->lt($end)) {
            $days[] = $cursor->format('Y-m-d');
            $cursor->addDay();
        }

        $roomCalendar = $rooms->map(function ($room) use ($days) {
            $statusMap = [];
            foreach ($days as $date) {
                $current = Carbon::parse($date);
                $status = 'Available';

                if ($room->blocks->first(fn ($block) => $block->status === 'Active' && $block->block_from->lte($current) && $block->block_to->gt($current))) {
                    $status = 'Blocked';
                } elseif ($room->bookings->first(fn ($booking) => in_array($booking->status, Booking::BOOKED_STATUSES, true) && $booking->check_in->lte($current) && $booking->check_out->gt($current))) {
                    $status = 'Booked';
                } elseif (in_array($room->status, ['Maintenance', 'Cleaning'], true)) {
                    $status = 'Blocked';
                }

                $statusMap[$date] = $status;
            }

            return [
                'room' => $room,
                'statusMap' => $statusMap,
            ];
        });

        return view('admin.room-blocks.calendar', compact('hotels', 'hotelId', 'start', 'month', 'year', 'days', 'roomCalendar'));
    }
}
