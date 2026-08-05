<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackageBookingController extends Controller
{
public function create(\App\Models\Package $package)
{
    $customer = auth('web')->user();

    $package->load([
        'hotelStays',
        'transportRates',
        'outboundFlight.departureAirport',
        'outboundFlight.arrivalAirport',
        'outboundFlight.returnDepartureAirport',
        'outboundFlight.returnArrivalAirport',
        'returnFlight.departureAirport',
        'returnFlight.arrivalAirport',
        'returnFlight.returnDepartureAirport',
        'returnFlight.returnArrivalAirport',
    ]);

    return view('packages.book', compact('package', 'customer'));
}
    public function store(\Illuminate\Http\Request $request, \App\Models\Package $package)
    {
        $request->validate([
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            
            'passengers' => 'required|array',
            'passengers.*.type' => 'required|string|in:Adult,Child,Infant',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.dob' => 'required|date',
            'passengers.*.cnic_document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'passengers.*.passport_document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $adults = (int) $request->adults;
        $children = (int) $request->children;
        $infants = (int) $request->infants;
        
        $totalSeatsRequired = $adults + $children + $infants;

        if ($package->available_seats < $totalSeatsRequired) {
            return back()->withInput()->with('error', "Only {$package->available_seats} seats available.");
        }

        if (! is_array($request->passengers) || count($request->passengers) !== $totalSeatsRequired) {
            return back()->withInput()->with('error', 'Passenger details must be provided for every selected seat.');
        }

        $bookingAdultCount = 0;
        $bookingChildCount = 0;
        $bookingInfantCount = 0;

        $passengersToCreate = [];
        foreach ($request->passengers as $pData) {
            $type = $pData['type'];
            $dob = \Carbon\Carbon::parse($pData['dob']);
            $age = $dob->age;

            if ($type !== 'Adult' && $age >= 2) {
                $type = 'Adult';
            }

            if ($type === 'Adult') {
                $bookingAdultCount++;
            } elseif ($type === 'Child') {
                $bookingChildCount++;
            } else {
                $bookingInfantCount++;
            }

            $passengersToCreate[] = [
                'type' => $type,
                'name' => $pData['name'],
                'dob' => $pData['dob'],
                'cnic_document' => $pData['cnic_document'],
                'passport_document' => $pData['passport_document'],
            ];
        }

        $adultPrice = $package->effectiveAdultPrice();
        $childPrice = $package->effectiveChildPrice();
        $infantPrice = $package->effectiveInfantPrice();

        $totalPassengers = $bookingAdultCount + $bookingChildCount + $bookingInfantCount;
        $passengerTotal = ($bookingAdultCount * $adultPrice) + ($bookingChildCount * $childPrice) + ($bookingInfantCount * $infantPrice);
        $visaTotal = $package->has_visa ? $package->effectiveVisaProcessingPrice() * $totalPassengers : 0;
        $transportTotal = 0;

        if ($package->has_transport) {
            $transportTotal = $package->transportTotal(
                $bookingAdultCount + $bookingChildCount,
                $bookingInfantCount
            );
        }

        $hotelTotal = $package->has_hotel ? $package->hotelStays->sum(fn($stay) => (float) ($stay->price_per_person ?? 0)) * $totalPassengers : 0;
        $flightTotal = $package->flightTotal($bookingAdultCount, $bookingChildCount, $bookingInfantCount);

        $totalPrice = $passengerTotal + $visaTotal + $transportTotal + $hotelTotal + $flightTotal;

        $booking = \App\Models\PackageBooking::create([
            'package_id' => $package->id,
            'user_id' => auth('web')->id(),
            'reference_number' => 'PKG-' . strtoupper(uniqid()),
            'adults' => $bookingAdultCount,
            'children' => $bookingChildCount,
            'infants' => $bookingInfantCount,
            'total_price' => $totalPrice,
            'status' => 'Pending',
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
        ]);

        foreach ($passengersToCreate as $index => $pData) {
            $cnicPath = $pData['cnic_document']->store('public/package_documents');
            $passportPath = $pData['passport_document']->store('public/package_documents');
            
            \App\Models\PackagePassenger::create([
                'package_booking_id' => $booking->id,
                'type' => $pData['type'],
                'name' => $pData['name'],
                'dob' => $pData['dob'],
                'cnic_document' => $cnicPath,
                'passport_document' => $passportPath,
            ]);
        }
        
        $package->decrement('available_seats', $totalSeatsRequired);

        return redirect()->route('customer.bookings')->with('success', 'Your package has been successfully booked! We will review your documents and contact you shortly.');
    }
}
