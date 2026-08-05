<?php

namespace App\Http\Controllers;

use App\Models\PackageBooking;

class PackageVoucherController extends Controller
{
    public function show($id)
    {
        $booking = PackageBooking::with([
            'package.outboundFlight.departureAirport',
            'package.outboundFlight.arrivalAirport',
            'package.outboundFlight.returnDepartureAirport',
            'package.outboundFlight.returnArrivalAirport',
            'package.returnFlight.departureAirport',
            'package.returnFlight.arrivalAirport',
            'package.returnFlight.returnDepartureAirport',
            'package.returnFlight.returnArrivalAirport',
            'package.hotelStays',
            'user',
            'passengers',
        ])->findOrFail($id);

        // Voucher sirf approved booking ka banega
        abort_unless($booking->status === 'Approved', 403, 'Voucher is only available for approved bookings.');

        return view('admin.package-bookings.voucher', compact('booking'));
    }
}