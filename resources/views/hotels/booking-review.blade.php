<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Review | {{ $hotel->hotel_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-100 text-slate-900">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto bg-white rounded-[2rem] shadow-xl overflow-hidden">
            <div class="bg-blue-600 text-white p-8">
                <h1 class="text-4xl font-bold">Booking Review</h1>
                <p class="mt-2 text-slate-100 text-sm">Review your reservation details before confirming.</p>
            </div>

            <div class="p-8 space-y-8">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Stay Details</p>
                        <h2 class="mt-3 text-xl font-semibold text-slate-900">{{ $hotel->hotel_name }}</h2>
                        <p class="text-sm text-slate-600">{{ $hotel->city }}</p>
                        <div class="mt-4 text-sm text-slate-600 space-y-2">
                            <div class="flex justify-between"><span>Check-in</span><span>{{ $checkIn->format('d M Y') }}</span></div>
                            <div class="flex justify-between"><span>Check-out</span><span>{{ $checkOut->format('d M Y') }}</span></div>
                            <div class="flex justify-between"><span>Nights</span><span>{{ $nights }}</span></div>
                            <div class="flex justify-between"><span>Room</span><span>{{ $roomType->room_name }}</span></div>
                            <div class="flex justify-between"><span>Meal plan</span><span>{{ $mealPlan->meal_plan_name ?? 'No meals' }}</span></div>
                        </div>
                    </div>
                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Room Details</p>
                        <div class="mt-4 text-sm text-slate-600 space-y-2">
                            <div class="flex justify-between"><span>Room type</span><span>{{ $roomType->room_name }}</span></div>
                            <div class="flex justify-between"><span>Max occupancy</span><span>{{ $roomType->max_occupancy }}</span></div>
                            <div class="flex justify-between"><span>Daily rate</span><span>SAR {{ number_format($roomType->daily_rate, 2) }}</span></div>
                            <div class="flex justify-between"><span>Total nights</span><span>{{ $nights }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Contact Information</p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 text-sm text-slate-600">
                        <div>
                            <p class="font-semibold text-slate-900">Contact name</p>
                            <p>{{ $contactName ?? ($request->input('contact_name') ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Email</p>
                            <p>{{ $contactEmail ?? ($request->input('contact_email') ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Phone</p>
                            <p>{{ $contactPhone ?? ($request->input('contact_phone') ?? 'N/A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Passenger Breakdown</p>
                    <div class="mt-4 space-y-4 text-sm text-slate-600">
                        @foreach($passengers as $index => $passenger)
                            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-semibold text-slate-900">{{ $passenger['passenger_type'] ?? 'Passenger' }} {{ $index + 1 }}</span>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2 text-sm text-slate-600">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Full name</p>
                                        <p class="font-medium text-slate-900">{{ $passenger['full_name'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Date of birth</p>
                                        <p>{{ $passenger['date_of_birth'] ?? 'N/A' }}</p>
                                        @if(!empty($passenger['passport_expiry']))
                                            <p class="text-xs text-slate-500 mt-1">Expiry: {{ $passenger['passport_expiry'] }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Passport Document</p>
                                        @if(isset($passengerFiles[$index]['passport_document']['name']))
                                            <p class="text-blue-600">✓ {{ $passengerFiles[$index]['passport_document']['name'] }}</p>
                                        @elseif(!empty($passengerTempPaths[$index]['passport_temp_path']))
                                            <p class="text-blue-600">✓ {{ basename($passengerTempPaths[$index]['passport_temp_path']) }}</p>
                                        @elseif(!empty($passenger['passport_document_name']))
                                            <p class="text-blue-600">✓ {{ $passenger['passport_document_name'] }}</p>
                                        @else
                                            <p>N/A</p>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">CNIC Document</p>
                                        @if(isset($passengerFiles[$index]['cnic_document']['name']))
                                            <p class="text-blue-600">✓ {{ $passengerFiles[$index]['cnic_document']['name'] }}</p>
                                        @elseif(!empty($passengerTempPaths[$index]['cnic_temp_path']))
                                            <p class="text-blue-600">✓ {{ basename($passengerTempPaths[$index]['cnic_temp_path']) }}</p>
                                        @elseif(!empty($passenger['cnic_document_name']))
                                            <p class="text-blue-600">✓ {{ $passenger['cnic_document_name'] }}</p>
                                        @else
                                            <p>N/A</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Price Summary</p>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between"><span>Room charge</span><span>SAR {{ number_format($roomCharge, 2) }}</span></div>
                        <div class="flex justify-between"><span>Meal charge</span><span>SAR {{ number_format($mealCharge, 2) }}</span></div>
                        <div class="flex justify-between"><span>Transport service</span><span>SAR {{ number_format($transportPrice, 2) }}</span></div>
                        <div class="flex justify-between"><span>Visa processing</span><span>SAR {{ number_format($visaPrice, 2) }}</span></div>
                        <div class="flex justify-between"><span>Taxes & fees</span><span>SAR {{ number_format($taxes, 2) }}</span></div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between font-semibold text-slate-900"><span>Total amount</span><span>SAR {{ number_format($grandTotal, 2) }}</span></div>
                        <div class="flex justify-between text-sm text-slate-500"><span>Total in PKR</span><span>PKR {{ number_format($totalInPKR, 2) }}</span></div>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <form action="{{ route('hotels.book.review.edit') }}" method="POST" class="sm:col-span-1">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                        <input type="hidden" name="hotel_room_type_id" value="{{ $roomType->id }}">
                        <input type="hidden" name="meal_plan_id" value="{{ $mealPlan->id ?? '' }}">
                        <input type="hidden" name="check_in" value="{{ $checkIn->format('Y-m-d') }}">
                        <input type="hidden" name="check_out" value="{{ $checkOut->format('Y-m-d') }}">
                        <input type="hidden" name="adults" value="{{ $request->input('adults', 0) }}">
                        <input type="hidden" name="children" value="{{ $request->input('children', 0) }}">
                        <input type="hidden" name="infants" value="{{ $request->input('infants', 0) }}">
                        <input type="hidden" name="include_meal" value="{{ $request->boolean('include_meal') ? 1 : 0 }}">
                        <input type="hidden" name="include_visa" value="{{ $request->boolean('include_visa') ? 1 : 0 }}">
                        <input type="hidden" name="include_transport" value="{{ $request->boolean('include_transport') ? 1 : 0 }}">
                        <input type="hidden" name="contact_name" value="{{ $contactName ?? $request->input('contact_name', '') }}">
                        <input type="hidden" name="contact_email" value="{{ $contactEmail ?? $request->input('contact_email', '') }}">
                        <input type="hidden" name="contact_phone" value="{{ $contactPhone ?? $request->input('contact_phone', '') }}">
                        @foreach($passengers as $index => $passenger)
                            @foreach($passenger as $field => $value)
                                @if(in_array($field, ['passport_temp_path', 'cnic_temp_path', 'passport_document_name', 'cnic_document_name'], true))
                                    <input type="hidden" name="passengers[{{ $index }}][{{ $field }}]" value="{{ $value }}">
                                @endif
                                @if(!in_array($field, ['passport_document', 'cnic_document'], true))
                                    <input type="hidden" name="passengers[{{ $index }}][{{ $field }}]" value="{{ $value }}">
                                @endif
                            @endforeach
                            @if(!empty($passengerTempPaths[$index]['passport_temp_path']))
                                <input type="hidden" name="passengers[{{ $index }}][passport_temp_path]" value="{{ $passengerTempPaths[$index]['passport_temp_path'] }}">
                            @endif
                            @if(!empty($passengerTempPaths[$index]['cnic_temp_path']))
                                <input type="hidden" name="passengers[{{ $index }}][cnic_temp_path]" value="{{ $passengerTempPaths[$index]['cnic_temp_path'] }}">
                            @endif
                        @endforeach
                        <button type="submit" class="w-full rounded-3xl border border-slate-200 bg-white py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50 transition">Back to Edit</button>
                    </form>

                    <form action="{{ route('hotels.book') }}" method="POST" class="sm:col-span-1">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                        <input type="hidden" name="hotel_room_type_id" value="{{ $roomType->id }}">
                        <input type="hidden" name="meal_plan_id" value="{{ $mealPlan->id ?? '' }}">
                        <input type="hidden" name="check_in" value="{{ $checkIn->format('Y-m-d') }}">
                        <input type="hidden" name="check_out" value="{{ $checkOut->format('Y-m-d') }}">
                        <input type="hidden" name="adults" value="{{ $request->input('adults', 0) }}">
                        <input type="hidden" name="children" value="{{ $request->input('children', 0) }}">
                        <input type="hidden" name="infants" value="{{ $request->input('infants', 0) }}">
                        <input type="hidden" name="include_meal" value="{{ $request->boolean('include_meal') ? 1 : 0 }}">
                        <input type="hidden" name="include_visa" value="{{ $request->boolean('include_visa') ? 1 : 0 }}">
                        <input type="hidden" name="include_transport" value="{{ $request->boolean('include_transport') ? 1 : 0 }}">
                        <input type="hidden" name="contact_name" value="{{ $contactName ?? $request->input('contact_name', '') }}">
                        <input type="hidden" name="contact_email" value="{{ $contactEmail ?? $request->input('contact_email', '') }}">
                        <input type="hidden" name="contact_phone" value="{{ $contactPhone ?? $request->input('contact_phone', '') }}">
                        @foreach($passengers as $index => $passenger)
                            @foreach($passenger as $field => $value)
                                @if(in_array($field, ['passport_temp_path', 'cnic_temp_path', 'passport_document_name', 'cnic_document_name'], true))
                                    <input type="hidden" name="passengers[{{ $index }}][{{ $field }}]" value="{{ $value }}">
                                @endif
                                @if(!in_array($field, ['passport_document', 'cnic_document'], true))
                                    <input type="hidden" name="passengers[{{ $index }}][{{ $field }}]" value="{{ $value }}">
                                @endif
                            @endforeach
                            @if(!empty($passengerTempPaths[$index]['passport_temp_path']))
                                <input type="hidden" name="passengers[{{ $index }}][passport_temp_path]" value="{{ $passengerTempPaths[$index]['passport_temp_path'] }}">
                            @endif
                            @if(!empty($passengerTempPaths[$index]['cnic_temp_path']))
                                <input type="hidden" name="passengers[{{ $index }}][cnic_temp_path]" value="{{ $passengerTempPaths[$index]['cnic_temp_path'] }}">
                            @endif
                        @endforeach
                        <button type="submit" class="w-full rounded-3xl bg-emerald-500 py-3 text-sm font-semibold text-slate-950 hover:bg-emerald-400 transition">Confirm Booking</button>
                    </form>

                    <div class="sm:col-span-1">
                        <a href="{{ route('hotels.details', ['hotel' => $hotel->id]) }}" class="inline-flex w-full items-center justify-center rounded-3xl border border-rose-500 bg-white py-3 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">Cancel Booking</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
