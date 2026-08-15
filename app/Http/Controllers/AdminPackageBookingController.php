<?php

namespace App\Http\Controllers;

use App\Models\PackageBooking;
use App\Models\VoucherSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminPackageBookingController extends Controller
{
    public function index()
    {
        $bookings = PackageBooking::with([
            'package',
            'user',
        ])->latest()->paginate(20);

        return view('admin.package-bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = PackageBooking::with([
            'package',
            'user',
            'passengers',
        ])->findOrFail($id);

        return view('admin.package-bookings.show', compact('booking'));
    }


public function voucher($id)
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

    abort_unless($booking->status === 'Approved', 403);

    $setting = VoucherSetting::first();

    return view(
        'admin.package-bookings.voucher',
        compact('booking', 'setting')
    );
}



  public function update(Request $request, $id)
{
    $booking = Booking::with('passengers.details')->findOrFail($id);
    $request->validate([
        'status' => 'required|in:Pending,Approved,Cancelled,Completed',
        'passengers' => 'nullable|array',
        'passengers.*.id' => 'required|integer|exists:package_passengers,id',
        'passengers.*.passport_number' => 'required|string|max:100',
    ]);

    $data = [
        'status' => $request->status,
    ];

    /*
    |--------------------------------------------------------------------------
    | Save Visa Provider Snapshot when booking is approved
    |--------------------------------------------------------------------------
    | Voucher Management ki current company name + logo ko booking ke andar
    | permanently save kar rahe hain.
    |
    | Iska matlab:
    | - New approved voucher = current company/logo
    | - Purana voucher = purana company/logo
    | - Future mein Voucher Management change karne se old vouchers change nahi honge
    */

    if ($request->status === 'Approved' && $booking->status !== 'Approved') {
        $setting = VoucherSetting::first();

        if ($setting) {
            $data['visa_provider_company_name'] = $setting->company_name;
            $data['visa_provider_logo'] = $setting->logo;
        }
    }

    $booking->update($data);

    if ($request->filled('passengers')) {
        foreach ($request->input('passengers', []) as $passengerInput) {
            if (isset($passengerInput['id'])) {
                $passenger = $booking->passengers->firstWhere('id', $passengerInput['id']);
                if ($passenger) {
                    $passenger->passport_number = $passengerInput['passport_number'] ?? null;
                    $passenger->save();
                }
            }
        }
    }

    return redirect()
        ->back()
        ->with('success', 'Booking status and passenger details updated successfully.');
}
}