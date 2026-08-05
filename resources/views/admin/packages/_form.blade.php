@php
    $hotelStays = old('hotel_stays', isset($package) ? $package->hotelStays->map(function ($stay) {
        $values = $stay->toArray();

        if (! empty($values['room_sharing_options']) && is_array($values['room_sharing_options'])) {
            $values['room_sharing_options'] = implode(', ', array_filter(array_map('trim', $values['room_sharing_options'])));
        }

        return $values;
    })->toArray() : []);
@endphp
<style>
    #hotelStaysSection .hotel-stay-row,
    #hotelStaysSection .hotel-stay-row label,
    #hotelStaysSection .hotel-stay-row h6,
    #hotelStaysSection .hotel-stay-row .form-text,
    #hotelStaysSection .hotel-stay-row .form-check-label {
        color: #000000 !important;
        opacity: 1 !important;
    }

    #hotelStaysSection .hotel-stay-row input,
    #hotelStaysSection .hotel-stay-row textarea,
    #hotelStaysSection .hotel-stay-row select {
        color: #000000 !important;
        -webkit-text-fill-color: #000000 !important;
        opacity: 1 !important;
    }

    #hotelStaysSection .hotel-stay-row input::placeholder,
    #hotelStaysSection .hotel-stay-row textarea::placeholder {
        color: #6b7280 !important;
        -webkit-text-fill-color: #6b7280 !important;
        opacity: 1 !important;
    }
</style>

<div class="row g-4 pkg-form-wrap">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-xl mb-4 app-card" style="--d:.05s">
            <div class="card-header bg-white border-bottom border-light py-3 app-card-header">
                <span class="header-dot"></span>
                <h6 class="mb-0 fw-bold text-dark">Basic Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-medium">Package Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $package->title ?? '') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Airline / Provider</label>
                        <input type="text" name="airline" class="form-control" value="{{ old('airline', $package->airline ?? '') }}" placeholder="e.g. Saudi Airlines">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Duration</label>
                        <input type="text" name="duration" class="form-control" value="{{ old('duration', $package->duration ?? '') }}" placeholder="e.g. 15 Days / 14 Nights">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Origin Route</label>
                        <input type="text" name="origin" class="form-control" value="{{ old('origin', $package->origin ?? '') }}" placeholder="e.g. ISB">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Destination Route</label>
                        <input type="text" name="destination" class="form-control" value="{{ old('destination', $package->destination ?? '') }}" placeholder="e.g. JED">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Departure Date</label>
                        <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date', isset($package) && $package->departure_date ? $package->departure_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Return Date</label>
                        <input type="date" name="return_date" class="form-control" value="{{ old('return_date', isset($package) && $package->return_date ? $package->return_date->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Makkah Hotel</label>
                        <input type="text" name="makkah_hotel" class="form-control" value="{{ old('makkah_hotel', $package->makkah_hotel ?? '') }}" placeholder="e.g. Pullman Zamzam (5★)">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Madinah Hotel</label>
                        <input type="text" name="madinah_hotel" class="form-control" value="{{ old('madinah_hotel', $package->madinah_hotel ?? '') }}" placeholder="e.g. Anwar Al Madinah (5★)">
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-xl mb-4 app-card" style="--d:.15s">
            <div class="card-header bg-white border-bottom border-light py-3 d-flex align-items-center justify-content-between flex-wrap gap-2 app-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="header-dot header-dot-green"></span>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">Hotel Accommodation</h6>
                        <p class="text-muted mb-0 small">Add one or more hotel stays for this package.</p>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-pill-outline" id="addHotelStayButton">
                    <span class="btn-plus">+</span> Add Hotel Stay
                </button>
            </div>
            <div class="card-body" id="hotelStaysSection">
                @error('hotel_stays')
                    <div class="alert alert-danger py-2">{{ $message }}</div>
                @enderror

                <div id="hotelStaysContainer">
                    @if(count($hotelStays))
                        @foreach($hotelStays as $index => $stay)
                          <div class="border rounded-3 p-3 mb-3 bg-green text-dark hotel-stay-row" data-index="{{ $index }}" style="--d:{{ $index * 0.06 }}s">
                                <div class="d-flex align-items-center justify-content-between mb-3 gap-2">
                                    <h6 class="mb-0 stay-title">Hotel Stay {{ $index + 1 }}</h6>
                                    <button type="button" class="btn btn-sm btn-danger-soft removeHotelStayButton">Remove</button>
                                </div>
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Hotel Name</label>
                                        <input type="text" name="hotel_stays[{{ $index }}][hotel_name]" class="form-control" value="{{ old("hotel_stays.$index.hotel_name", $stay['hotel_name'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">City</label>
                                        <input type="text" name="hotel_stays[{{ $index }}][city]" class="form-control" value="{{ old("hotel_stays.$index.city", $stay['city'] ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Star Rating</label>
                                        <input type="number" min="0" max="5" name="hotel_stays[{{ $index }}][star_rating]" class="form-control" value="{{ old("hotel_stays.$index.star_rating", $stay['star_rating'] ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Check-in</label>
                                        <input type="date" name="hotel_stays[{{ $index }}][check_in]" class="form-control" value="{{ old("hotel_stays.$index.check_in", isset($stay['check_in']) && $stay['check_in'] ? \Carbon\Carbon::parse($stay['check_in'])->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Check-out</label>
                                        <input type="date" name="hotel_stays[{{ $index }}][check_out]" class="form-control" value="{{ old("hotel_stays.$index.check_out", isset($stay['check_out']) && $stay['check_out'] ? \Carbon\Carbon::parse($stay['check_out'])->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Nights</label>
                                        <input type="number" min="1" name="hotel_stays[{{ $index }}][nights]" class="form-control" value="{{ old("hotel_stays.$index.nights", $stay['nights'] ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Room Type</label>
                                        <input type="text" name="hotel_stays[{{ $index }}][room_type]" class="form-control" value="{{ old("hotel_stays.$index.room_type", $stay['room_type'] ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Price Per Person</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">SAR</span>
                                            <input type="number" step="0.01" min="0" name="hotel_stays[{{ $index }}][price_per_person]" class="form-control border-start-0 ps-0" value="{{ old("hotel_stays.$index.price_per_person", $stay['price_per_person'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Distance From Haram</label>
                                        <input type="text" name="hotel_stays[{{ $index }}][distance_from_haram]" class="form-control" value="{{ old("hotel_stays.$index.distance_from_haram", $stay['distance_from_haram'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Walking Time</label>
                                        <input type="text" name="hotel_stays[{{ $index }}][walking_time]" class="form-control" value="{{ old("hotel_stays.$index.walking_time", $stay['walking_time'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Room Sharing Options</label>
                                        <textarea name="hotel_stays[{{ $index }}][room_sharing_options]" class="form-control" rows="2" placeholder="e.g. Double, Triple">{{ old("hotel_stays.$index.room_sharing_options", $stay['room_sharing_options'] ?? '') }}</textarea>
                                        <div class="form-text">Enter comma-separated options such as Double, Triple.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Transport Notes</label>
                                        <textarea name="hotel_stays[{{ $index }}][transport_notes]" class="form-control" rows="2">{{ old("hotel_stays.$index.transport_notes", $stay['transport_notes'] ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="hotel_stays[{{ $index }}][custom_to_haram]" value="1" {{ old("hotel_stays.$index.custom_to_haram", $stay['custom_to_haram'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label">Custom to Haram</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div id="hotelStaysPlaceholder" class="text-muted small empty-state">
                            <span class="empty-icon">🏨</span>
                            No hotel stays added yet. Click Add Hotel Stay to start.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-xl mb-4 app-card sticky-side" style="--d:.1s">
            <div class="card-header bg-white border-bottom border-light py-3 app-card-header">
                <span class="header-dot header-dot-blue"></span>
                <h6 class="mb-0 fw-bold text-dark">Pricing &amp; Seats</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-medium">Default Price / Fallback</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">SAR</span>
                        <input type="number" step="0.01" name="price" class="form-control border-start-0 ps-0 @error('price') is-invalid @enderror" value="{{ old('price', $package->price ?? '') }}" required>
                    </div>
                    <div class="form-text">Used when a dedicated adult/child/infant price is not provided.</div>
                    @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Adult Price</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">SAR</span>
                            <input type="number" step="0.01" min="0" name="adult_price" class="form-control border-start-0 ps-0 @error('adult_price') is-invalid @enderror" value="{{ old('adult_price', $package->adult_price ?? $package->price ?? '') }}">
                        </div>
                        @error('adult_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Child Price</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">SAR</span>
                            <input type="number" step="0.01" min="0" name="child_price" class="form-control border-start-0 ps-0 @error('child_price') is-invalid @enderror" value="{{ old('child_price', $package->child_price ?? $package->adult_price ?? $package->price ?? '') }}">
                        </div>
                        @error('child_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Infant Price</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">SAR</span>
                            <input type="number" step="0.01" min="0" name="infant_price" class="form-control border-start-0 ps-0 @error('infant_price') is-invalid @enderror" value="{{ old('infant_price', $package->infant_price ?? $package->child_price ?? $package->adult_price ?? $package->price ?? '') }}">
                        </div>
                        @error('infant_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Visa Processing Price</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">SAR</span>
                            <input type="number" step="0.01" min="0" name="visa_processing_price" class="form-control border-start-0 ps-0 @error('visa_processing_price') is-invalid @enderror" value="{{ old('visa_processing_price', $package->visa_processing_price ?? 1400) }}">
                        </div>
                        @error('visa_processing_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                   <div class="col-12">
    <div class="border rounded-3 p-3 bg-light">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="mb-1 fw-bold text-dark">Transport Pricing</h6>
                <small class="text-muted">
                    Complete transport price based on passenger count
                </small>
            </div>
            <span class="badge bg-success-subtle text-success">
                SAR
            </span>
        </div>

        <div class="row g-3">

            {{-- 1 Passenger --}}
            <div class="col-md-6">
                <label class="form-label fw-medium">
                    1 Passenger
                </label>
                <div class="input-group">
                    <span class="input-group-text">SAR</span>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="transport_rates[1][price]"
                        class="form-control"
                        value="{{ old('transport_rates.1.price', 850) }}"
                    >
                </div>
            </div>

            {{-- 2 Passengers --}}
            <div class="col-md-6">
                <label class="form-label fw-medium">
                    2 Passengers
                </label>
                <div class="input-group">
                    <span class="input-group-text">SAR</span>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="transport_rates[2][price]"
                        class="form-control"
                        value="{{ old('transport_rates.2.price', 800) }}"
                    >
                </div>
            </div>

            {{-- 3 Passengers --}}
            <div class="col-md-6">
                <label class="form-label fw-medium">
                    3 Passengers
                </label>
                <div class="input-group">
                    <span class="input-group-text">SAR</span>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="transport_rates[3][price]"
                        class="form-control"
                        value="{{ old('transport_rates.3.price', 745) }}"
                    >
                </div>
            </div>

            {{-- 4 Passengers --}}
            <div class="col-md-6">
                <label class="form-label fw-medium">
                    4 Passengers
                </label>
                <div class="input-group">
                    <span class="input-group-text">SAR</span>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="transport_rates[4][price]"
                        class="form-control"
                        value="{{ old('transport_rates.4.price', 725) }}"
                    >
                </div>
            </div>

            {{-- 5-49 Passengers --}}
            <div class="col-md-6">
                <label class="form-label fw-medium">
                    5–49 Passengers
                </label>
                <div class="input-group">
                    <span class="input-group-text">SAR</span>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="transport_rates[5_49][price]"
                        class="form-control"
                        value="{{ old('transport_rates.5_49.price', 650) }}"
                    >
                </div>
            </div>

            {{-- Infant --}}
            <div class="col-md-6">
                <label class="form-label fw-medium">
                    Infant (0–2 Years)
                </label>
                <div class="input-group">
                    <span class="input-group-text">SAR</span>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="transport_rates[infant][price]"
                        class="form-control"
                        value="{{ old('transport_rates.infant.price', 540) }}"
                    >
                </div>
            </div>

        </div>

        <div class="form-text mt-3">
            Transport pricing is the complete price for the passenger group,
            not a per-person price.
        </div>
    </div>
</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Total Seats <span class="text-danger">*</span></label>
                    <input type="number" name="total_seats" class="form-control @error('total_seats') is-invalid @enderror" value="{{ old('total_seats', $package->total_seats ?? '50') }}" required>
                </div>

                <hr class="section-divider my-4">

                <h6 class="fw-bold text-dark mb-3">Inclusions</h6>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" name="has_visa" id="has_visa" value="1" {{ old('has_visa', $package->has_visa ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_visa">Visa Included</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" name="has_hotel" id="has_hotel" value="1" {{ old('has_hotel', $package->has_hotel ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_hotel">Hotel Included</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" name="has_transport" id="has_transport" value="1" {{ old('has_transport', $package->has_transport ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_transport">Transport Included</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" name="has_flight" id="has_flight" value="1" {{ old('has_flight', $package->has_flight ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_flight">Flight Included</label>
                </div>

                <div id="flightSelectionSection" class="mb-3 flight-collapsible {{ old('has_flight', $package->has_flight ?? true) ? 'is-open' : '' }}">
                    <div class="border rounded-3 p-4 mb-3 bg-slate-950 border-slate-800 shadow-sm flight-config-section">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                            <div>
                                <h6 class="mb-1 text-white fw-bold">✈ Flight Details</h6>
                                <p class="text-muted small mb-0">Configure outbound and return flights for this package</p>
                            </div>
                        </div>

                        <div class="row gx-3 gy-3">
                            <div class="col-md-6">
                                <div class="flight-config-card h-100">
                                    <div class="mb-3">
                                        <div class="text-uppercase text-secondary small fw-semibold mb-2">✈ Outbound Flight</div>
                                        <div class="text-white fw-semibold mb-2">Select Flight</div>
                                        <select name="outbound_flight_id" id="outboundFlightSelect" class="form-select flight-form-select">
                                            <option value="">Select outbound flight</option>
                                            @foreach($tickets as $ticket)
                                                <option value="{{ $ticket->id }}" {{ old('outbound_flight_id', $package->outbound_flight_id ?? '') == $ticket->id ? 'selected' : '' }}>
                                                    {{ $ticket->departureAirport?->code ?? explode(' - ', $ticket->route)[0] ?? $ticket->route }} → {{ $ticket->arrivalAirport?->code ?? explode(' - ', $ticket->route)[1] ?? $ticket->route }} | {{ $ticket->departure_date?->format('d M Y') ?? '' }} | {{ $ticket->flight_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="outboundFlightDetails" class="flight-details-card">
                                        <div class="flight-details-title">Outbound Flight Details</div>
                                        <div class="flight-empty">
                                            <div class="flight-empty-icon">✈</div>
                                            <div>
                                                <div class="fw-semibold text-white">No outbound flight selected</div>
                                                <div class="text-muted small">Choose a flight from the dropdown above.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="flight-config-card h-100">
                                    <div class="mb-3">
                                        <div class="text-uppercase text-secondary small fw-semibold mb-2">↩ Return Flight</div>
                                        <div class="text-white fw-semibold mb-2">Select Flight</div>
                                        <select name="return_flight_id" id="returnFlightSelect" class="form-select flight-form-select">
                                            <option value="">Select return flight (optional)</option>
                                            @foreach($tickets as $ticket)
                                                <option value="{{ $ticket->id }}" {{ old('return_flight_id', $package->return_flight_id ?? '') == $ticket->id ? 'selected' : '' }}>
                                                    {{ $ticket->departureAirport?->code ?? explode(' - ', $ticket->route)[0] ?? $ticket->route }} → {{ $ticket->arrivalAirport?->code ?? explode(' - ', $ticket->route)[1] ?? $ticket->route }} | {{ $ticket->departure_date?->format('d M Y') ?? '' }} | {{ $ticket->flight_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="returnFlightDetails" class="flight-details-card">
                                        <div class="flight-details-title">Return Flight Details</div>
                                        <div class="flight-empty">
                                            <div class="flight-empty-icon">↩</div>
                                            <div>
                                                <div class="fw-semibold text-white">No return flight selected</div>
                                                <div class="text-muted small">Return flight is optional.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" name="has_meals" id="has_meals" value="1" {{ old('has_meals', $package->has_meals ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_meals">Meals Included</label>
                </div>

                <hr class="section-divider my-4">

                <div class="mb-3">
                    <label class="form-label fw-medium">Badge (e.g. Premium)</label>
                    <input type="text" name="badge" class="form-control" value="{{ old('badge', $package->badge ?? '') }}">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Package Visibility</label>
                    <p class="text-muted small mb-3">
                        Select which users can see this package.
                    </p>

                    <div class="border rounded-3 p-3 visibility-box">

                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="show_to_agents"
                                id="show_to_agents"
                                value="1"
                                {{ old('show_to_agents', $package->show_to_agents ?? true) ? 'checked' : '' }}
                            >

                            <label class="form-check-label fw-medium" for="show_to_agents">
                                Show to Agents
                            </label>
                        </div>

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="show_to_customers"
                                id="show_to_customers"
                                value="1"
                                {{ old('show_to_customers', $package->show_to_customers ?? true) ? 'checked' : '' }}
                            >

                            <label class="form-check-label fw-medium" for="show_to_customers">
                                Show to Customers
                            </label>
                        </div>

                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        <option value="Active" {{ old('status', $package->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Draft" {{ old('status', $package->status ?? '') == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Full" {{ old('status', $package->status ?? '') == 'Full' ? 'selected' : '' }}>Full</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm py-2 btn-submit-glow">
                    {{ $isEdit ? 'Update Package' : 'Create Package' }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ===================== THEME TOKENS (Blue + Green) ===================== */
.pkg-form-wrap {
    --blue-50:  #eff6ff;
    --blue-100: #dbeafe;
    --blue-400: #60a5fa;
    --blue-500: #3b82f6;
    --blue-600: #2563eb;
    --blue-700: #1d4ed8;
    --green-400: #34d399;
    --green-500: #10b981;
    --green-600: #059669;
    --gradient-bg: linear-gradient(135deg, var(--blue-600) 0%, var(--green-500) 100%);
    --gradient-bg-soft: linear-gradient(135deg, rgba(37,99,235,0.08) 0%, rgba(16,185,129,0.08) 100%);
    --shadow-soft: 0 10px 30px -12px rgba(37, 99, 235, 0.18);
    --shadow-hover: 0 16px 40px -14px rgba(16, 185, 129, 0.3);
    animation: pageFadeIn .5s ease both;
}

@keyframes pageFadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes rowSlideIn {
    from { opacity: 0; transform: translateY(-10px) scale(.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

@keyframes rowSlideOut {
    from { opacity: 1; transform: translateY(0) scale(1); max-height: 900px; }
    to   { opacity: 0; transform: translateY(-8px) scale(.98); max-height: 0; margin: 0; padding-top: 0; padding-bottom: 0; }
}

@keyframes gradientShift {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.35); }
    50%      { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
}

@keyframes dotPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%      { transform: scale(1.3); opacity: .7; }
}

/* ===================== CARDS ===================== */
.pkg-form-wrap .app-card {
    animation: fadeInUp .55s ease both;
    animation-delay: var(--d, 0s);
    border-radius: 1.1rem !important;
    overflow: hidden;
    transition: transform .3s ease, box-shadow .3s ease;
    box-shadow: var(--shadow-soft) !important;
}
.pkg-form-wrap .app-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover) !important;
}

.pkg-form-wrap .app-card-header {
    display: flex;
    align-items: center;
    gap: .6rem;
    background: linear-gradient(90deg, var(--blue-50), #ecfdf5) !important;
    border-bottom: 1px solid rgba(37, 99, 235, 0.08) !important;
}

.pkg-form-wrap .header-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--blue-500);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    animation: dotPulse 2.4s ease-in-out infinite;
    flex-shrink: 0;
}
.pkg-form-wrap .header-dot-green { background: var(--green-500); box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15); }
.pkg-form-wrap .header-dot-blue  { background: var(--blue-500);  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); }

/* ===================== INPUTS ===================== */
.pkg-form-wrap .form-control,
.pkg-form-wrap .form-select {
    border-radius: .65rem;
    border: 1.5px solid #e2e8f0;
    transition: border-color .25s ease, box-shadow .25s ease, transform .15s ease;
}
.pkg-form-wrap .form-control:focus,
.pkg-form-wrap .form-select:focus {
    border-color: var(--blue-500);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    transform: translateY(-1px);
}
.pkg-form-wrap .input-group-text {
    border-radius: .65rem 0 0 .65rem;
    border: 1.5px solid #e2e8f0;
    border-right: none;
    background: var(--blue-50) !important;
    color: var(--blue-700) !important;
    font-weight: 600;
}
.pkg-form-wrap .input-group:focus-within .input-group-text {
    border-color: var(--blue-500);
}

/* ===================== BUTTONS ===================== */
.pkg-form-wrap .btn-pill-outline {
    border: 1.5px solid var(--blue-500);
    color: var(--blue-600);
    background: #fff;
    border-radius: 999px;
    padding: .4rem 1rem;
    font-weight: 600;
    transition: all .25s ease;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
}
.pkg-form-wrap .btn-pill-outline:hover {
    background: var(--gradient-bg);
    color: #fff;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px -6px rgba(16, 185, 129, 0.45);
}
.pkg-form-wrap .btn-plus {
    display: inline-block;
    transition: transform .3s ease;
}
.pkg-form-wrap .btn-pill-outline:hover .btn-plus {
    transform: rotate(90deg);
}

.pkg-form-wrap .btn-danger-soft {
    background: #fee2e2;
    color: #dc2626;
    border: none;
    border-radius: 999px;
    font-weight: 600;
    padding: .3rem .9rem;
    transition: all .2s ease;
}
.pkg-form-wrap .btn-danger-soft:hover {
    background: #dc2626;
    color: #fff;
    transform: scale(1.05);
}

.pkg-form-wrap .btn-submit-glow {
    background: var(--gradient-bg);
    background-size: 200% 200%;
    border: none;
    color: #fff;
    letter-spacing: .02em;
    animation: gradientShift 4s ease infinite, pulseGlow 3s ease-in-out infinite;
    transition: transform .25s ease, box-shadow .25s ease;
}
.pkg-form-wrap .btn-submit-glow:hover {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 12px 26px -8px rgba(37, 99, 235, 0.5);
    color: #fff;
}
.pkg-form-wrap .btn-submit-glow:active {
    transform: translateY(0) scale(.99);
}

/* ===================== HOTEL STAY ROWS ===================== */
.pkg-form-wrap .hotel-stay-row {
    background: #fff;
    border: 1.5px solid #e6f0ff !important;
    border-radius: 1rem !important;
    position: relative;
    overflow: hidden;
    animation: rowSlideIn .4s ease both;
    animation-delay: var(--d, 0s);
    transition: box-shadow .25s ease, border-color .25s ease;
}
.pkg-form-wrap .hotel-stay-row::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: var(--gradient-bg);
}
.pkg-form-wrap .hotel-stay-row:hover {
    border-color: var(--green-400) !important;
    box-shadow: 0 8px 22px -10px rgba(16, 185, 129, 0.35);
}
.pkg-form-wrap .hotel-stay-row.row-leaving {
    animation: rowSlideOut .35s ease forwards;
    pointer-events: none;
}
.pkg-form-wrap .stay-title {
    color: var(--blue-700);
    font-weight: 700;
}

.pkg-form-wrap .empty-state {
    text-align: center;
    padding: 2rem 1rem;
    border: 2px dashed #cbd5e1;
    border-radius: 1rem;
    background: var(--gradient-bg-soft);
    animation: fadeInUp .4s ease both;
}
.pkg-form-wrap .empty-icon {
    display: block;
    font-size: 1.8rem;
    margin-bottom: .4rem;
}

/* ===================== SWITCHES ===================== */
.pkg-form-wrap .form-check.form-switch .form-check-input {
    width: 2.6em;
    height: 1.4em;
    border-color: #cbd5e1;
    transition: background-color .25s ease, border-color .25s ease, box-shadow .25s ease;
    cursor: pointer;
}
.pkg-form-wrap .form-check.form-switch .form-check-input:checked {
    background-color: var(--green-500);
    border-color: var(--green-500);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
}
.pkg-form-wrap .form-check.form-switch .form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}
.pkg-form-wrap .form-check-label {
    transition: color .2s ease;
}
.pkg-form-wrap .form-check.form-switch:hover .form-check-label {
    color: var(--blue-600);
}

.pkg-form-wrap .form-check-input[type="checkbox"]:not([role="switch"]) {
    transition: all .2s ease;
    cursor: pointer;
}
.pkg-form-wrap .form-check-input[type="checkbox"]:checked {
    background-color: var(--blue-500);
    border-color: var(--blue-500);
}

.pkg-form-wrap .visibility-box {
    background: var(--gradient-bg-soft);
    border-color: #dbeafe !important;
    transition: box-shadow .25s ease;
}
.pkg-form-wrap .visibility-box:hover {
    box-shadow: inset 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.pkg-form-wrap .section-divider {
    border: none;
    height: 2px;
    background: linear-gradient(90deg, var(--blue-100), var(--green-400), var(--blue-100));
    background-size: 200% 100%;
    animation: gradientShift 6s ease infinite;
    opacity: .6;
}

/* ===================== FLIGHT SECTION COLLAPSE ===================== */
.pkg-form-wrap .flight-collapsible {
    display: grid;
    grid-template-rows: 0fr;
    opacity: 0;
    transition: grid-template-rows .4s ease, opacity .35s ease;
    overflow: hidden;
}
.pkg-form-wrap .flight-collapsible > .flight-config-section {
    min-height: 0;
    overflow: hidden;
}
.pkg-form-wrap .flight-collapsible.is-open {
    grid-template-rows: 1fr;
    opacity: 1;
}
.pkg-form-wrap .flight-collapsible > * {
    min-height: 0;
}

.flight-config-section {
    background: linear-gradient(160deg, #0f172a 0%, #0b1e2f 100%);
    animation: fadeInUp .4s ease both;
}
.flight-config-card {
    background: #111827;
    border: 1px solid rgba(148, 163, 184, 0.12);
    border-radius: 1rem;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    transition: border-color .25s ease, transform .25s ease;
}
.flight-config-card:hover {
    border-color: rgba(52, 211, 153, 0.4);
    transform: translateY(-2px);
}
.flight-config-card .text-secondary {
    color: #94a3b8 !important;
}
.flight-form-select {
    background: #0f172a;
    border: 1px solid rgba(148, 163, 184, 0.24);
    color: #f8fafc;
    border-radius: 0.85rem;
    min-height: 3.1rem;
    padding: 0.75rem 0.9rem;
    box-shadow: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    appearance: none;
}
.flight-form-select:focus {
    border-color: var(--green-400);
    box-shadow: 0 0 0 0.2rem rgba(52, 211, 153, 0.2);
    outline: none;
}
.flight-form-select option {
    background: #0f172a;
    color: #f8fafc;
}
.flight-details-card {
    background: #111827;
    border: 1px solid rgba(148, 163, 184, 0.14);
    border-radius: 1rem;
    padding: 1rem;
    min-height: 186px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 0.85rem;
    transition: border-color .3s ease;
    animation: fadeInUp .35s ease both;
}
.flight-details-title {
    font-size: 0.85rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #94a3b8;
}
.flight-empty {
    display: flex;
    align-items: flex-start;
    gap: 0.95rem;
}
.flight-empty-icon {
    font-size: 1.5rem;
    line-height: 1;
    color: var(--green-400);
    margin-top: 0.15rem;
    animation: dotPulse 2.6s ease-in-out infinite;
}
.flight-details-card .fw-semibold {
    color: #f8fafc;
}
.flight-detail-row {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    color: #cbd5e1;
    font-size: 0.95rem;
}
.flight-detail-code {
    font-size: 1.5rem;
    font-weight: 700;
    color: #f8fafc;
}
.flight-detail-city {
    color: #94a3b8;
    font-size: 0.95rem;
}
.flight-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.8rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}
.flight-badge.cabin {
    background: rgba(52, 211, 153, 0.15);
    color: var(--green-400);
}
.flight-badge.type {
    background: rgba(59, 130, 246, 0.15);
    color: var(--blue-400);
}
.flight-config-section,
.flight-config-card,
.flight-details-card {
    user-select: text;
}

/* ===================== RESPONSIVE ===================== */
@media (max-width: 991.98px) {
    .pkg-form-wrap .sticky-side { position: static; }
    .pkg-form-wrap .app-card { border-radius: .9rem !important; }
}

@media (max-width: 767.98px) {
    .pkg-form-wrap .card-body { padding: 1rem; }
    .pkg-form-wrap .app-card-header { flex-wrap: wrap; padding: 1rem !important; }
    .pkg-form-wrap .btn-pill-outline { width: 100%; justify-content: center; }
    .flight-config-card { padding: 1rem; }
    .flight-details-card { min-height: 150px; }
    .pkg-form-wrap .hotel-stay-row { padding: .85rem !important; }
}

@media (max-width: 575.98px) {
    .pkg-form-wrap .input-group-text { font-size: .8rem; padding: .4rem .55rem; }
    .pkg-form-wrap .form-control, .pkg-form-wrap .form-select { font-size: .9rem; }
    .pkg-form-wrap .btn-submit-glow { font-size: .95rem; }
}

@media (prefers-reduced-motion: reduce) {
    .pkg-form-wrap, .pkg-form-wrap * {
        animation-duration: .001ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .001ms !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
    (function() {
        const container = document.getElementById('hotelStaysContainer');
        const addButton = document.getElementById('addHotelStayButton');

        function createHotelStayRow(index, stay = {}) {
            const wrapper = document.createElement('div');
            wrapper.className = 'border rounded-3 p-3 mb-3 hotel-stay-row';
            wrapper.dataset.index = index;
            wrapper.innerHTML = `
                <div class="d-flex align-items-center justify-content-between mb-3 gap-2">
                    <h6 class="mb-0 stay-title">Hotel Stay ${index + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger-soft removeHotelStayButton">Remove</button>
                </div>
                <div class="row gy-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Hotel Name</label>
                        <input type="text" name="hotel_stays[${index}][hotel_name]" class="form-control" value="${stay.hotel_name || ''}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">City</label>
                        <input type="text" name="hotel_stays[${index}][city]" class="form-control" value="${stay.city || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Star Rating</label>
                        <input type="number" min="0" max="5" name="hotel_stays[${index}][star_rating]" class="form-control" value="${stay.star_rating || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Check-in</label>
                        <input type="date" name="hotel_stays[${index}][check_in]" class="form-control" value="${stay.check_in || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Check-out</label>
                        <input type="date" name="hotel_stays[${index}][check_out]" class="form-control" value="${stay.check_out || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Nights</label>
                        <input type="number" min="1" name="hotel_stays[${index}][nights]" class="form-control" value="${stay.nights || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Room Type</label>
                        <input type="text" name="hotel_stays[${index}][room_type]" class="form-control" value="${stay.room_type || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Price Per Person</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">SAR</span>
                            <input type="number" step="0.01" min="0" name="hotel_stays[${index}][price_per_person]" class="form-control border-start-0 ps-0" value="${stay.price_per_person || ''}">
                        </div>
                    </div>
                            <div class="col-md-6">
                        <label class="form-label fw-medium">Distance From Haram</label>
                        <input type="text" name="hotel_stays[${index}][distance_from_haram]" class="form-control" value="${stay.distance_from_haram || ''}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Walking Time</label>
                        <input type="text" name="hotel_stays[${index}][walking_time]" class="form-control" value="${stay.walking_time || ''}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Room Sharing Options</label>
                        <textarea name="hotel_stays[${index}][room_sharing_options]" class="form-control" rows="2" placeholder="e.g. Double, Triple">${stay.room_sharing_options || ''}</textarea>
                        <div class="form-text">Enter comma-separated options such as Double, Triple.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Transport Notes</label>
                        <textarea name="hotel_stays[${index}][transport_notes]" class="form-control" rows="2">${stay.transport_notes || ''}</textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hotel_stays[${index}][custom_to_haram]" value="1" ${stay.custom_to_haram ? 'checked' : ''}>
                            <label class="form-check-label">Custom to Haram</label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="hotel_stays[${index}][sort_order]" value="${typeof stay.sort_order !== 'undefined' ? stay.sort_order : index}">
            `;

            wrapper.querySelector('.removeHotelStayButton').addEventListener('click', function() {
                removeRowAnimated(wrapper);
            });

            return wrapper;
        }
        

        function removeRowAnimated(row) {
            row.classList.add('row-leaving');
            const cleanup = () => {
                row.remove();
                refreshIndexes();
            };
            row.addEventListener('animationend', cleanup, { once: true });
            // Safety fallback in case animationend doesn't fire
            setTimeout(cleanup, 450);
        }

        function maybeTogglePlaceholder() {
            const placeholder = document.getElementById('hotelStaysPlaceholder');
            const rows = container.querySelectorAll('.hotel-stay-row');

            if (rows.length === 0 && ! placeholder) {
                const empty = document.createElement('div');
                empty.id = 'hotelStaysPlaceholder';
                empty.className = 'text-muted small empty-state';
                empty.innerHTML = '<span class="empty-icon">🏨</span>No hotel stays added yet. Click Add Hotel Stay to start.';
                container.appendChild(empty);
            }

            if (rows.length > 0 && placeholder) {
                placeholder.remove();
            }
        }

        function refreshIndexes() {
            const rows = container.querySelectorAll('.hotel-stay-row');
            rows.forEach((row, index) => {
                row.dataset.index = index;
                row.querySelector('h6').textContent = `Hotel Stay ${index + 1}`;
                row.querySelectorAll('input').forEach((input) => {
                    const name = input.name.replace(/hotel_stays\[\d+\]/, `hotel_stays[${index}]`);
                    input.name = name;
                });
            });

            maybeTogglePlaceholder();
        }

        if (addButton) {
            addButton.addEventListener('click', function() {
                const index = container.querySelectorAll('.hotel-stay-row').length;
                const row = createHotelStayRow(index, {});
                container.appendChild(row);
                maybeTogglePlaceholder();
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        }

        document.querySelectorAll('.removeHotelStayButton').forEach((button) => {
            button.addEventListener('click', function(event) {
                const row = event.target.closest('.hotel-stay-row');
                if (row) {
                    removeRowAnimated(row);
                }
            });
        });

        const tickets = @json($tickets ?? []);

        function getTicketById(ticketId) {
            return tickets.find((ticket) => String(ticket.id) === String(ticketId));
        }

        function parseRouteSegments(route) {
            if (!route || typeof route !== 'string') {
                return [];
            }

            const segments = route.trim().split(/\s*-\s*/);
            return segments.length === 2 ? segments : [route.trim()];
        }

        function formatTicketRoute(ticket) {
            const departureCode = ticket.departureAirport?.code ?? parseRouteSegments(ticket.route)[0] ?? 'N/A';
            const arrivalCode = ticket.arrivalAirport?.code ?? parseRouteSegments(ticket.route)[1] ?? 'N/A';
            return `${departureCode} → ${arrivalCode}`;
        }

        function formatTicketLabel(ticket) {
            return `${ticket.departureAirport?.code ?? parseRouteSegments(ticket.route)[0] ?? ticket.route} → ${ticket.arrivalAirport?.code ?? parseRouteSegments(ticket.route)[1] ?? ticket.route} | ${ticket.departure_date ? new Date(ticket.departure_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A'} | ${ticket.flight_number}`;
        }

        function updateReturnOptions() {
            const outboundSelect = document.getElementById('outboundFlightSelect');
            const returnSelect = document.getElementById('returnFlightSelect');
            if (!outboundSelect || !returnSelect) {
                return;
            }

            const selectedOutbound = getTicketById(outboundSelect.value);
            const currentReturnTicketId = returnSelect.value;

            const matchByAirport = selectedOutbound?.arrivalAirport?.id
                ? tickets.filter((ticket) => String(ticket.id) !== String(selectedOutbound.id)
                    && String(ticket.departureAirport?.id ?? '') === String(selectedOutbound.arrivalAirport.id))
                : [];

            const returnOptions = matchByAirport.length > 0
                ? matchByAirport
                : tickets.filter((ticket) => String(ticket.id) !== String(selectedOutbound?.id));

            const defaultOptionText = returnSelect.querySelector('option[value=""]')?.textContent || 'Select return flight (optional)';
            returnSelect.innerHTML = `<option value="">${defaultOptionText}</option>` + returnOptions.map((ticket) => {
                return `<option value="${ticket.id}">${formatTicketLabel(ticket)}</option>`;
            }).join('');

            if (returnOptions.some((ticket) => String(ticket.id) === String(currentReturnTicketId))) {
                returnSelect.value = currentReturnTicketId;
            }
        }

        function formatTicketDetails(ticket) {
            if (! ticket) {
                return '<p class="text-muted small">No flight selected.</p>';
            }

            const departureAirport = ticket.departureAirport?.code ?? parseRouteSegments(ticket.route)[0] ?? 'N/A';
            const arrivalAirport = ticket.arrivalAirport?.code ?? parseRouteSegments(ticket.route)[1] ?? 'N/A';
            const departureDate = ticket.departure_date ? new Date(ticket.departure_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';
            const returnDate = ticket.return_date ? new Date(ticket.return_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';
            const departureTime = ticket.departure_time ?? 'N/A';
            const arrivalTime = ticket.arrival_time ?? 'N/A';
            const cabinClasses = ['Economy', 'Premium Economy', 'Business', 'First']
                .filter((cabin) => ticket[cabin.toLowerCase().replace(' ', '_') + '_seats'] > 0)
                .join(', ') || 'Economy';

            return `
                <div class="row g-2 mb-2">
                    <div class="col-12">
                        <strong class="d-block mb-1">${ticket.flight_number} · ${ticket.ticket_type ?? 'N/A'}</strong>
                    </div>
                    <div class="col-6"><span class="text-muted small">Route</span><div>${departureAirport} → ${arrivalAirport}</div></div>
                    <div class="col-6"><span class="text-muted small">Date</span><div>${departureDate}</div></div>
                    <div class="col-6"><span class="text-muted small">Time</span><div>${departureTime} → ${arrivalTime}</div></div>
                    <div class="col-6"><span class="text-muted small">Cabin Class</span><div>${cabinClasses}</div></div>
                    <div class="col-12"><span class="text-muted small">Flight Type</span><div>${ticket.ticket_type ?? 'N/A'}</div></div>
                </div>
            `;
        }

        function updateFlightDetails() {
            const outboundSelect = document.getElementById('outboundFlightSelect');
            const returnSelect = document.getElementById('returnFlightSelect');
            const outboundDetails = document.getElementById('outboundFlightDetails');
            const returnDetails = document.getElementById('returnFlightDetails');

            outboundDetails.innerHTML = '<div class="fw-semibold mb-1">Outbound Flight Details</div>' + formatTicketDetails(getTicketById(outboundSelect.value));
            returnDetails.innerHTML = '<div class="fw-semibold mb-1">Return Flight Details</div>' + formatTicketDetails(getTicketById(returnSelect.value));
        }

        function toggleFlightSection() {
            const section = document.getElementById('flightSelectionSection');
            if (! section) {
                return;
            }
            section.classList.toggle('is-open', document.getElementById('has_flight').checked);
        }

        const outboundSelect = document.getElementById('outboundFlightSelect');
        const returnSelect = document.getElementById('returnFlightSelect');
        const hasFlightToggle = document.getElementById('has_flight');

        if (outboundSelect) {
            outboundSelect.addEventListener('change', function() {
                updateReturnOptions();
                updateFlightDetails();
            });
        }

        if (returnSelect) {
            returnSelect.addEventListener('change', updateFlightDetails);
        }

        if (hasFlightToggle) {
            hasFlightToggle.addEventListener('change', function() {
                toggleFlightSection();
            });
        }

        updateReturnOptions();
        updateFlightDetails();
        maybeTogglePlaceholder();
        toggleFlightSection();
    })();
</script>
@endpush