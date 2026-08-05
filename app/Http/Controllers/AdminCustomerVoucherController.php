<?php

namespace App\Http\Controllers;

use App\Models\CustomerVoucher;
use App\Models\FlightBooking;
use App\Models\PackageBooking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminCustomerVoucherController extends Controller
{
    /**
     * Voucher Management
     */
    public function index(Request $request)
    {
        $query = CustomerVoucher::with([
            'flightBooking.user',
            'flightBooking.agent',
            'flightBooking.ticket.airlineMaster',
            'flightBooking.ticket.departureAirport',
            'flightBooking.ticket.arrivalAirport',
            'flightBooking.ticket.returnDepartureAirport',
            'flightBooking.ticket.returnArrivalAirport',

            'packageBooking.user',
            'packageBooking.package',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('type') &&
            in_array($request->input('type'), ['flight', 'package'], true)
        ) {
            if ($request->input('type') === 'flight') {
                $query->whereNotNull('flight_booking_id');
            } else {
                $query->whereNotNull('package_booking_id');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('q')) {
            $q = $request->input('q');

            $query->where(function ($query) use ($q) {

                $query->where(
                    'voucher_number',
                    'like',
                    "%{$q}%"
                )

                ->orWhereHas('flightBooking', function ($query) use ($q) {

                    $query->where(
                        'reference',
                        'like',
                        "%{$q}%"
                    )

                    ->orWhereHas('ticket', function ($query) use ($q) {

                        $query->where(
                            'route',
                            'like',
                            "%{$q}%"
                        )
                        ->orWhere(
                            'flight_number',
                            'like',
                            "%{$q}%"
                        );
                    });
                })

                ->orWhereHas('packageBooking', function ($query) use ($q) {

                    $query->where(
                        'reference_number',
                        'like',
                        "%{$q}%"
                    )

                    ->orWhereHas('package', function ($query) use ($q) {

                        $query->where(
                            'name',
                            'like',
                            "%{$q}%"
                        );
                    });
                })

                ->orWhereHas('flightBooking.user', function ($query) use ($q) {

                    $query->where(
                        'name',
                        'like',
                        "%{$q}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$q}%"
                    );
                })

                ->orWhereHas('packageBooking.user', function ($query) use ($q) {

                    $query->where(
                        'name',
                        'like',
                        "%{$q}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$q}%"
                    );
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | EXISTING VOUCHERS
        |--------------------------------------------------------------------------
        */

        $vouchers = $query
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | CONFIRMED FLIGHT BOOKINGS WITHOUT VOUCHER
        |--------------------------------------------------------------------------
        */

        $approvedFlightBookings = FlightBooking::with([
            'ticket.airlineMaster',
            'ticket.departureAirport',
            'ticket.arrivalAirport',
            'ticket.returnDepartureAirport',
            'ticket.returnArrivalAirport',
            'user',
            'agent',
            'voucher',
        ])
            ->where('status', 'Confirmed')
            ->whereDoesntHave('voucher')
            ->orderByDesc('created_at')
            ->paginate(
                15,
                ['*'],
                'approved_bookings_page'
            );

        return view(
            'admin.vouchers.index',
            compact(
                'vouchers',
                'approvedFlightBookings'
            )
        );
    }

    /**
     * Show voucher
     */
    public function show(CustomerVoucher $voucher)
    {
        $voucher->load([
            'flightBooking.user',
            'flightBooking.agent',
            'flightBooking.ticket.airlineMaster',
            'flightBooking.ticket.departureAirport',
            'flightBooking.ticket.arrivalAirport',
            'flightBooking.ticket.returnDepartureAirport',
            'flightBooking.ticket.returnArrivalAirport',
            'flightBooking.passengers',

            'packageBooking.user',
            'packageBooking.package',
        ]);

        return view(
            'admin.vouchers.show',
            compact('voucher')
        );
    }

    /**
     * Download voucher PDF
     */
    public function download(CustomerVoucher $voucher)
    {
        $voucher->load([
            'flightBooking.user',
            'flightBooking.agent',
            'flightBooking.ticket.airlineMaster',
            'flightBooking.ticket.departureAirport',
            'flightBooking.ticket.arrivalAirport',
            'flightBooking.ticket.returnDepartureAirport',
            'flightBooking.ticket.returnArrivalAirport',
            'flightBooking.passengers',

            'packageBooking.user',
            'packageBooking.package',
        ]);

        $booking = $voucher->flightBooking
            ?? $voucher->packageBooking;

        abort_unless($booking, 404);

        $pdf = Pdf::loadView(
            'customer.vouchers.pdf',
            [
                'booking' => $booking,
                'voucher' => $voucher,
            ]
        );

        return $pdf->download(
            'voucher-' .
            $voucher->voucher_number .
            '.pdf'
        );
    }

    /**
     * Generate flight voucher
     */
    public function generate(FlightBooking $flightBooking)
    {
        abort_unless(
            $flightBooking->status === 'Confirmed',
            403
        );

        $voucher = $this->createVoucherForFlight(
            $flightBooking
        );

        return redirect()
            ->route(
                'admin.vouchers.show',
                [
                    'voucher' => $voucher->id
                ]
            )
            ->with(
                'success',
                'Flight voucher generated successfully.'
            );
    }

    /**
     * Generate package voucher
     */
    public function generatePackage(
        PackageBooking $packageBooking
    ) {
        abort_unless(
            $packageBooking->status === 'Approved',
            403
        );

        $voucher = $this->createVoucherForPackage(
            $packageBooking
        );

        return redirect()
            ->route(
                'admin.vouchers.show',
                [
                    'voucher' => $voucher->id
                ]
            )
            ->with(
                'success',
                'Package voucher generated successfully.'
            );
    }

    /**
     * Create flight voucher
     */
    protected function createVoucherForFlight(
        FlightBooking $flightBooking
    ): CustomerVoucher {

        return CustomerVoucher::firstOrCreate(
            [
                'flight_booking_id' => $flightBooking->id,
            ],
            [
                'voucher_number' =>
                    $this->generateUniqueVoucherNumber(),

                'status' => 'Issued',

                'issued_at' => now(),
            ]
        );
    }

    /**
     * Create package voucher
     */
    protected function createVoucherForPackage(
        PackageBooking $packageBooking
    ): CustomerVoucher {

        return CustomerVoucher::firstOrCreate(
            [
                'package_booking_id' => $packageBooking->id,
            ],
            [
                'voucher_number' =>
                    $this->generateUniqueVoucherNumber(),

                'status' => 'Issued',

                'issued_at' => now(),
            ]
        );
    }

    /**
     * Generate unique voucher number
     */
    protected function generateUniqueVoucherNumber(): string
    {
        do {
            $voucherNumber =
                'VCH-' .
                strtoupper(
                    uniqid()
                );

        } while (
            CustomerVoucher::where(
                'voucher_number',
                $voucherNumber
            )->exists()
        );

        return $voucherNumber;
    }
}   