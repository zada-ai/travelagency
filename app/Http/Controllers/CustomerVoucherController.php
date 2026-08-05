<?php

namespace App\Http\Controllers;

use App\Models\CustomerVoucher;
use App\Models\FlightBooking;
use App\Models\PackageBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerVoucherController extends Controller
{
    public function show(FlightBooking $flightBooking)
    {
        abort_unless($flightBooking->status === 'Confirmed', 403);
        abort_unless($flightBooking->user_id === Auth::id(), 403);

        $voucher = $this->createVoucherForFlight($flightBooking);

        return view('customer.vouchers.show', [
            'booking' => $flightBooking,
            'voucher' => $voucher,
        ]);
    }

    public function download(FlightBooking $flightBooking)
    {
        abort_unless($flightBooking->status === 'Confirmed', 403);
        abort_unless($flightBooking->user_id === Auth::id(), 403);

        $voucher = CustomerVoucher::firstWhere('flight_booking_id', $flightBooking->id);
        abort_unless($voucher, 404);

        $booking = $flightBooking;
        $pdf = Pdf::loadView('customer.vouchers.pdf', compact('booking', 'voucher'));

        return $pdf->download('voucher-' . $voucher->voucher_number . '.pdf');
    }

    public function showPackage(PackageBooking $packageBooking)
    {
        abort_unless($packageBooking->status === 'Approved', 403);
        abort_unless($packageBooking->user_id === Auth::id(), 403);

        $voucher = $this->createVoucherForPackage($packageBooking);

        return view('customer.vouchers.show', [
            'booking' => $packageBooking,
            'voucher' => $voucher,
        ]);
    }

    public function downloadPackage(PackageBooking $packageBooking)
    {
        abort_unless($packageBooking->status === 'Approved', 403);
        abort_unless($packageBooking->user_id === Auth::id(), 403);

        $voucher = CustomerVoucher::firstWhere('package_booking_id', $packageBooking->id);
        abort_unless($voucher, 404);

        $booking = $packageBooking;
        $pdf = Pdf::loadView('customer.vouchers.pdf', compact('booking', 'voucher'));

        return $pdf->download('voucher-' . $voucher->voucher_number . '.pdf');
    }

    protected function createVoucherForFlight(FlightBooking $flightBooking): CustomerVoucher
    {
        return CustomerVoucher::firstOrCreate(
            ['flight_booking_id' => $flightBooking->id],
            [
                'voucher_number' => $this->generateUniqueVoucherNumber(),
                'status' => 'Issued',
                'issued_at' => now(),
            ]
        );
    }

    protected function createVoucherForPackage(PackageBooking $packageBooking): CustomerVoucher
    {
        return CustomerVoucher::firstOrCreate(
            ['package_booking_id' => $packageBooking->id],
            [
                'voucher_number' => $this->generateUniqueVoucherNumber(),
                'status' => 'Issued',
                'issued_at' => now(),
            ]
        );
    }

    protected function generateUniqueVoucherNumber(): string
    {
        do {
            $voucherNumber = 'VCH-' . strtoupper(uniqid());
        } while (CustomerVoucher::where('voucher_number', $voucherNumber)->exists());

        return $voucherNumber;
    }
}
