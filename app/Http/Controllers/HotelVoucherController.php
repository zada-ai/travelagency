<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\VoucherSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HotelVoucherController extends Controller
{
    /**
     * Step 1:
     * Save company name + logo.
     */
    public function prepare(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'company_name' => [
                'required',
                'string',
                'max:255'
            ],

            'company_logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get/Create Voucher Setting
        |--------------------------------------------------------------------------
        */

        $voucherSetting = VoucherSetting::first();

        if (!$voucherSetting) {
            $voucherSetting = new VoucherSetting();
        }

        /*
        |--------------------------------------------------------------------------
        | Company Name
        |--------------------------------------------------------------------------
        */

        $voucherSetting->company_name =
            $data['company_name'];

        /*
        |--------------------------------------------------------------------------
        | Company Logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('company_logo')) {

            $directory =
                public_path('voucher-images');

            if (!File::exists($directory)) {
                File::makeDirectory(
                    $directory,
                    0755,
                    true
                );
            }

            /*
            | Delete old logo
            */

            if (
                !empty($voucherSetting->logo)
            ) {

                $oldLogo =
                    public_path(
                        $voucherSetting->logo
                    );

                if (
                    File::exists($oldLogo)
                ) {
                    File::delete($oldLogo);
                }
            }

            /*
            | New filename
            */

            $filename =
                'voucher-admin-logo-' .
                time() .
                '-' .
                uniqid() .
                '.' .
                $request
                    ->file('company_logo')
                    ->extension();

            /*
            | Move file
            */

            $request
                ->file('company_logo')
                ->move(
                    $directory,
                    $filename
                );

            /*
            | Save path in DB
            */

            $logoPath =
                'voucher-images/' .
                $filename;

            $voucherSetting->logo =
                $logoPath;

            /*
            | Session
            */

            session([
                'voucher_company_logo' =>
                    $logoPath
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Save DB
        |--------------------------------------------------------------------------
        */

        $voucherSetting->save();

        /*
        |--------------------------------------------------------------------------
        | Session
        |--------------------------------------------------------------------------
        */

        session([
            'voucher_company_name' =>
                $data['company_name'],

            'voucher_step_2' =>
                true,
        ]);

        return redirect()
            ->route(
                'admin.bookings.show',
                $booking
            )
            ->with(
                'success',
                'Company details saved successfully.'
            );
    }

    /**
     * Save passenger passport numbers.
     */
    public function savePassengers(
        Request $request,
        Booking $booking
    ) {
        $inputs = $request->validate([
            'passengers' => [
                'required',
                'array'
            ],

            'room_number' => [
                'nullable',
                'string',
                'max:255'
            ],

            'payment_status' => [
                'nullable',
                'string',
                'max:50'
            ],

            'passengers.*.passport_number' => [
                'nullable',
                'string',
                'max:255'
            ],
        ]);

        $passengers =
            $inputs['passengers'] ?? [];

        foreach (
            $passengers as $id => $values
        ) {

            $bp =
                BookingPassenger::where(
                    'id',
                    $id
                )
                ->where(
                    'booking_id',
                    $booking->id
                )
                ->first();

            if (!$bp) {
                continue;
            }

            $bp->update([
                'passport_number' =>
                    $values['passport_number']
                    ?? $bp->passport_number,
            ]);
        }

        if (!empty($inputs['room_number'])) {
            session([
                'voucher_room_number' => trim($inputs['room_number'])
            ]);
        }

        if (!empty($inputs['payment_status'])) {
            session([
                'voucher_payment_status' => trim($inputs['payment_status'])
            ]);

            $booking->update([
                'payment_status' => trim($inputs['payment_status'])
            ]);
        }

        session([
            'voucher_step_2' => true
        ]);

        return redirect()
            ->route(
                'admin.bookings.show',
                $booking
            )
            ->with(
                'success',
                'Passenger passport numbers saved.'
            );
    }

    public function passengers(Request $request, Booking $booking)
    {
        return $this->savePassengers($request, $booking);
    }

    /**
     * Generate / Preview Hotel Voucher.
     */
    public function generate(
        Booking $booking
    ) {
        abort_unless(
            in_array($booking->status, Booking::BOOKED_STATUSES, true),
            403,
            'Voucher is only available for reserved bookings.'
        );

        $booking->load([
            'hotel',
            'roomType',
            'room',
            'mealPlan',
            'passengers',
            'travelAgent',
        ]);

        $voucherSetting =
            VoucherSetting::first();

        $voucherRoomNumber =
            $booking->room?->room_number
            ?? session('voucher_room_number')
            ?? 'Pending';

        $voucherPaymentStatus =
            $booking->payment_status
            ?? session('voucher_payment_status')
            ?? 'Pending';

        return view(
            'admin.hotel-vouchers.show',
            compact(
                'booking',
                'voucherSetting',
                'voucherRoomNumber',
                'voucherPaymentStatus'
            )
        );
    }

    /**
     * Show voucher to booking owner (customer) or related travel agent.
     */
    public function showForOwner(Booking $booking)
    {
        abort_unless(
            in_array($booking->status, Booking::BOOKED_STATUSES, true),
            403,
            'Voucher is only available for reserved bookings.'
        );

        // Load relations used in voucher view
        $booking->load([
            'hotel',
            'roomType',
            'room',
            'mealPlan',
            'passengers',
            'travelAgent',
        ]);

        $user = auth()->guard('web')->user();
        $agent = auth()->guard('travel_agent')->user();

        $isOwner = false;

        if ($user) {
            if (!empty($user->email) && $user->email === $booking->contact_email) {
                $isOwner = true;
            }

            if (! $isOwner && !empty($user->phone) && $user->phone === $booking->contact_phone) {
                $isOwner = true;
            }

            // If booking has user_id column and matches
            if (! $isOwner && isset($booking->user_id) && $booking->user_id === $user->id) {
                $isOwner = true;
            }
        }

        if ($agent && !$isOwner) {
            if (isset($booking->travel_agent_id) && $booking->travel_agent_id === $agent->id) {
                $isOwner = true;
            }
        }

        abort_unless($isOwner, 403);

        $voucherSetting = VoucherSetting::first();

        $voucherRoomNumber =
            $booking->room?->room_number
            ?? session('voucher_room_number')
            ?? 'Pending';

        $voucherPaymentStatus =
            $booking->payment_status
            ?? session('voucher_payment_status')
            ?? 'Pending';

        return view(
            'admin.hotel-vouchers.show',
            compact(
                'booking',
                'voucherSetting',
                'voucherRoomNumber',
                'voucherPaymentStatus'
            )
        );
    }
}