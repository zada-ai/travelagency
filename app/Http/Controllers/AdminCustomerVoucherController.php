<?php

namespace App\Http\Controllers;

use App\Models\CustomerVoucher;
use App\Models\FlightBooking;
use App\Models\PackageBooking;
use App\Models\VoucherSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
                            'title',
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
            'passengers',
        ])
            ->where('status', 'Approved')
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
            'passengers',
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
            'packageBooking.passengers',
        ]);

        $this->assertBookingEligibleForVoucher(
            $voucher->flightBooking ?? $voucher->packageBooking
        );

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
            'passengers',
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
            'packageBooking.passengers',
        ]);

        $booking = $voucher->flightBooking
            ?? $voucher->packageBooking;

        $this->assertBookingEligibleForVoucher($booking);

        $setting = VoucherSetting::first();

        $pdf = Pdf::loadView(
            'customer.vouchers.pdf',
            [
                'booking' => $booking,
                'voucher' => $voucher,
                'setting' => $setting,
            ]
        );

        return $pdf->download(
            'voucher-' .
            $voucher->voucher_number .
            '.pdf'
        );
    }

    

    protected function ensureAdmin(): void
    {
        $user = Auth::guard('web')->user();

        abort_unless(
            $user &&
            (
                $user->hasRole('Super Admin')
                || in_array(
                    strtolower((string) ($user->role ?? '')),
                    ['super_admin', 'super admin', 'admin'],
                    true
                )
            ),
            403
        );
    }

    protected function assertBookingEligibleForVoucher($booking): void
    {
        abort_unless($booking, 404);
        abort_unless(
            $booking->status === 'Approved',
            403,
            'Voucher is only available for approved bookings.'
        );
    }

    protected function resolveAdminCompanyAttributes(Request $request): array
    {
        $data = $request->validate([
            'admin_company_name' => ['required', 'string', 'max:255'],
            'admin_company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'transport_type' => ['nullable', 'string', 'max:100'],
        ]);

        if ($request->hasFile('admin_company_logo')) {
            $directory = public_path('voucher-images');

            if (! File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $filename = 'voucher-admin-logo-' . time() . '-' . uniqid() . '.' . $request->file('admin_company_logo')->extension();

            $request->file('admin_company_logo')->move($directory, $filename);

            $data['admin_company_logo'] = 'voucher-images/' . $filename;
        } else {
            unset($data['admin_company_logo']);
        }

        if (empty($data['transport_type'])) {
            unset($data['transport_type']);
        }

        return $data;
    }

    protected function resolveVoucherPassengers(Request $request, string $type): array
    {
        $passengerRules = [
            'id' => ['required', 'integer'],
            'passport_number' => ['required', 'string', 'max:255'],
        ];

        $attributePrefix = $type === 'flight' ? 'passengers' : 'passengers';

        $data = $request->validate([
            "{$attributePrefix}" => ['required', 'array', 'min:1'],
            "{$attributePrefix}.*.id" => $passengerRules['id'],
            "{$attributePrefix}.*.passport_number" => $passengerRules['passport_number'],
        ]);

        return $data[$attributePrefix] ?? [];
    }

    /**
     * Generate flight voucher
     */
    public function generate(Request $request, FlightBooking $flightBooking)
    {
        $this->ensureAdmin();

        abort_unless(
            $flightBooking->status === 'Approved',
            403
        );

        $adminAttributes = $this->resolveAdminCompanyAttributes($request);
        $passengerInputs = $this->resolveVoucherPassengers($request, 'flight');

        $flightBooking->load('passengers');

        $voucher = $this->createVoucherForFlight(
            $flightBooking,
            $adminAttributes
        );

        $this->syncVoucherPassengers(
            $voucher,
            $passengerInputs,
            $flightBooking->passengers
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
    public function generatePackage(Request $request, PackageBooking $packageBooking)
    {
        $this->ensureAdmin();

        abort_unless(
            $packageBooking->status === 'Approved',
            403
        );

        $adminAttributes = $this->resolveAdminCompanyAttributes($request);
        $passengerInputs = $this->resolveVoucherPassengers($request, 'package');

        $packageBooking->load('passengers');

        $voucher = $this->createVoucherForPackage(
            $packageBooking,
            $adminAttributes
        );

        $this->syncVoucherPassengers(
            $voucher,
            $passengerInputs,
            $packageBooking->passengers
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
        FlightBooking $flightBooking,
        array $adminAttributes = []
    ): CustomerVoucher {

        return CustomerVoucher::updateOrCreate(
            [
                'flight_booking_id' => $flightBooking->id,
            ],
            array_merge(
                [
                    'voucher_number' =>
                        $this->generateUniqueVoucherNumber(),

                    'status' => 'Issued',

                    'issued_at' => now(),
                ],
                $adminAttributes
            )
        );
    }

    /**
     * Create package voucher
     */
    protected function createVoucherForPackage(
        PackageBooking $packageBooking,
        array $adminAttributes = []
    ): CustomerVoucher {

        return CustomerVoucher::updateOrCreate(
            [
                'package_booking_id' => $packageBooking->id,
            ],
            array_merge(
                [
                    'voucher_number' =>
                        $this->generateUniqueVoucherNumber(),

                    'status' => 'Issued',

                    'issued_at' => now(),
                ],
                $adminAttributes
            )
        );
    }

    protected function syncVoucherPassengers(
        CustomerVoucher $voucher,
        array $passengerInputs,
        $bookingPassengers
    ): void {
        $passengerIds = collect($passengerInputs)
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $voucher->passengers()
            ->whereNotIn('passenger_id', $passengerIds)
            ->delete();

        foreach ($passengerInputs as $passengerInput) {
            $bookingPassenger = $bookingPassengers
                ->firstWhere('id', $passengerInput['id']);

            if (! $bookingPassenger) {
                continue;
            }

            $voucher->passengers()->updateOrCreate(
                [
                    'customer_voucher_id' => $voucher->id,
                    'passenger_id' => $bookingPassenger->id,
                ],
                [
                    'passenger_type' => $bookingPassenger->passenger_type ?? $bookingPassenger->type ?? null,
                    'first_name' => $bookingPassenger->first_name ?? null,
                    'last_name' => $bookingPassenger->last_name ?? null,
                    'name' => $bookingPassenger->name ?? trim(
                        ($bookingPassenger->first_name ?? '') . ' ' . ($bookingPassenger->last_name ?? '')
                    ),
                    'passport_number' => $passengerInput['passport_number'],
                ]
            );
        }
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