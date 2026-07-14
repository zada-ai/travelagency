<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'hotel_id', 'status', 'check_in_from', 'check_in_to']);

        $query = Booking::with(['hotel', 'roomType', 'mealPlan']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($builder) use ($search) {
                $builder->where('reference_number', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%")
                    ->orWhere('contact_phone', 'like', "%{$search}%")
                    ->orWhereHas('hotel', fn ($hotelQuery) => $hotelQuery->where('hotel_name', 'like', "%{$search}%"))
                    ->orWhereHas('roomType', fn ($roomTypeQuery) => $roomTypeQuery->where('room_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('check_in_from')) {
            $query->whereDate('check_in', '>=', $request->check_in_from);
        }

        if ($request->filled('check_in_to')) {
            $query->whereDate('check_in', '<=', $request->check_in_to);
        }

        $bookings = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $hotels = Hotel::orderBy('hotel_name')->get();

        return view('admin.bookings.index', compact('bookings', 'hotels', 'filters'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['hotel', 'roomType', 'mealPlan', 'passengers']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status !== 'Cancelled') {
            $booking->cancel();
        }

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking cancelled successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'hotel_id', 'status', 'check_in_from', 'check_in_to']);

        $query = Booking::with(['hotel', 'roomType']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($builder) use ($search) {
                $builder->where('reference_number', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%")
                    ->orWhere('contact_phone', 'like', "%{$search}%")
                    ->orWhereHas('hotel', fn ($hotelQuery) => $hotelQuery->where('hotel_name', 'like', "%{$search}%"))
                    ->orWhereHas('roomType', fn ($roomTypeQuery) => $roomTypeQuery->where('room_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('check_in_from')) {
            $query->whereDate('check_in', '>=', $request->check_in_from);
        }

        if ($request->filled('check_in_to')) {
            $query->whereDate('check_in', '<=', $request->check_in_to);
        }

        $filename = 'bookings_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Reference',
                'Hotel',
                'Room Type',
                'Check In',
                'Check Out',
                'Guests',
                'Status',
                'Contact',
                'Total',
                'Created At',
            ]);

            $query->orderByDesc('created_at')->chunk(100, function ($bookings) use ($handle) {
                foreach ($bookings as $booking) {
                    fputcsv($handle, [
                        $booking->reference_number,
                        $booking->hotel->hotel_name ?? '-',
                        $booking->roomType->room_name ?? '-',
                        $booking->check_in->format('Y-m-d'),
                        $booking->check_out->format('Y-m-d'),
                        $booking->total_passengers,
                        $booking->status,
                        $booking->contact_email,
                        number_format($booking->grand_total, 2),
                        $booking->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
