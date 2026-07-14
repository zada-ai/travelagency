
        document.addEventListener('DOMContentLoaded', () => {
            const heroSliderEl = document.querySelector('.hero-slider');

            if (heroSliderEl) {
                new Swiper(heroSliderEl, {
                    loop: true,
                    speed: 800,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    keyboard: {
                        enabled: true,
                        onlyInViewport: true,
                    },
                });
            }

            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                openEffect: 'fade',
                closeEffect: 'fade',
                zoomable: true,
            });

            const checkIn = flatpickr('#detailCheckIn', { dateFormat: 'Y-m-d', minDate: 'today', onChange: updateSummary });
            const checkOut = flatpickr('#detailCheckOut', { dateFormat: 'Y-m-d', minDate: 'today', onChange: updateSummary });
            const roomSelect = document.getElementById('detailRoomType');
            const mealSelect = document.getElementById('detailMealPlan');
            const adults = document.getElementById('detailAdults');
            const children = document.getElementById('detailChildren');
            const infants = document.getElementById('detailInfants');
            const includeMeal = document.getElementById('includeMeal');
            const childLimitMessage = document.getElementById('childLimitMessage');
            const selectedGuestCount = document.getElementById('selectedGuestCount');
            const guestNamesContainer = document.getElementById('guestNamesContainer');
            const adultNamesList = document.getElementById('adultNamesList');
            const childNamesList = document.getElementById('childNamesList');
            const infantNamesList = document.getElementById('infantNamesList');
            const summaryRoomCharge = document.getElementById('summaryRoomCharge');
            const summaryMealCharge = document.getElementById('summaryMealCharge');
            const summaryTaxes = document.getElementById('summaryTaxes');
            const summaryTotal = document.getElementById('summaryTotal');
            const summaryTotalPKR = document.getElementById('summaryTotalPKR');
            const roomAvailabilityText = document.getElementById('roomAvailabilityText');
            const inventorySummaryText = document.getElementById('inventorySummaryText');
            const bookingErrorContainer = document.getElementById('bookingErrorContainer');
            const bookingErrorList = document.getElementById('bookingErrorList');
            const reserveButton = document.getElementById('reserveSubmitButton');
            const bookingForm = document.getElementById('hotelBookingForm');
            const reserveButtonText = document.getElementById('reserveButtonText');
            const reserveButtonSpinner = document.getElementById('reserveButtonSpinner');
            const oldPassengers = @json(old('passengers', []));
            const initialRoomTypes = @json($hotel->roomTypes->where('status', 'Active')->map(function ($roomType) {
                return [
                    'id' => $roomType->id,
                    'room_name' => $roomType->room_name,
                    'rate' => (float) $roomType->daily_rate,
                    'capacity' => $roomType->max_occupancy,
                    'extra_bed_price' => (float) $roomType->extra_bed_price,
                    'available_rooms' => 0,
                    'status' => 'Select your dates to check availability',
                ];
            })->values());
            let availabilityLoading = false;

            function getSelectedRoomRate() {
                return parseFloat(roomSelect.selectedOptions[0].dataset.rate || 0);
            }

            function getSelectedMealRate() {
                return parseFloat(mealSelect.selectedOptions[0].dataset.price || 0);
            }

            function updateSummary() {
                const nights = calculateNights();
                const roomRate = getSelectedRoomRate();
                const mealRate = getSelectedMealRate();
                const totalGuests = getTotalGuests();
                const roomCharge = roomRate * Math.max(nights, 1);
                const mealCharge = includeMeal.checked ? mealRate * totalGuests : 0;
                const taxes = parseFloat((roomCharge + mealCharge) * 0.10).toFixed(2);
                const total = (roomCharge + mealCharge + parseFloat(taxes)).toFixed(2);
                const availability = parseInt(roomSelect.selectedOptions[0].dataset.available || '0', 10);
                const status = roomSelect.selectedOptions[0].dataset.status || 'Select dates';

                summaryRoomCharge.textContent = `SAR ${roomCharge.toFixed(2)}`;
                summaryMealCharge.textContent = `SAR ${mealCharge.toFixed(2)}`;
                summaryTaxes.textContent = `SAR ${taxes}`;
                summaryTotal.textContent = `SAR ${total}`;
                summaryTotalPKR.textContent = `PKR ${(total * 83).toFixed(2)}`;
                selectedGuestCount.textContent = `${totalGuests} guest${totalGuests === 1 ? '' : 's'}`;
                renderGuestFields();

                if (availability > 0) {
                    roomAvailabilityText.textContent = `${availability} room${availability === 1 ? '' : 's'} available for selected dates.`;
                    roomAvailabilityText.classList.remove('text-red-600');
                    roomAvailabilityText.classList.add('text-slate-500');
                    reserveButton.removeAttribute('disabled');
                } else if (availabilityLoading) {
                    roomAvailabilityText.textContent = 'Checking availability...';
                    roomAvailabilityText.classList.remove('text-red-600');
                    roomAvailabilityText.classList.add('text-slate-500');
                    reserveButton.setAttribute('disabled', 'disabled');
                } else {
                    roomAvailabilityText.textContent = status === 'Sold Out' ? 'Sold out for selected dates.' : 'Select stay dates to check availability.';
                    roomAvailabilityText.classList.remove('text-slate-500');
                    roomAvailabilityText.classList.add('text-red-600');
                    reserveButton.setAttribute('disabled', 'disabled');
                }

                const totalAvailable = Array.from(roomSelect.options).reduce((sum, option) => {
                    return sum + parseInt(option.dataset.available || '0', 10);
                }, 0);

                inventorySummaryText.textContent = totalAvailable > 0
                    ? `${totalAvailable} rooms available across selected room types.`
                    : availabilityLoading
                        ? 'Checking inventory for selected dates...'
                        : 'No live availability data found for selected dates. Please adjust your dates.';

                if (!availabilityLoading && totalAvailable === 0 && checkIn.selectedDates.length && checkOut.selectedDates.length) {
                    bookingErrorContainer.classList.remove('hidden');
                    bookingErrorList.innerHTML = '<li>No available rooms exist for the selected dates. Please change your dates or room type.</li>';
                } else {
                    bookingErrorContainer.classList.add('hidden');
                    bookingErrorList.innerHTML = '';
                }
            }

            function calculateNights() {
                const start = checkIn.selectedDates[0];
                const end = checkOut.selectedDates[0];
                if (!start || !end) return 1;
                const diffTime = Math.max(0, end - start);
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            }

            function getTotalGuests() {
                return parseInt(adults.value, 10) + parseInt(children.value, 10) + parseInt(infants.value, 10);
            }

            function renderGuestFields() {
                const adultCount = parseInt(adults.value, 10);
                const childCount = parseInt(children.value, 10);
                const infantCount = parseInt(infants.value, 10);
                const totalGuests = getTotalGuests();
                const showNames = totalGuests > 0;

                guestNamesContainer.classList.toggle('hidden', !showNames);
                adultNamesList.innerHTML = '';
                childNamesList.innerHTML = '';
                infantNamesList.innerHTML = '';

                if (!showNames) {
                    return;
                }

                let index = 0;

                const fieldGroupMarkup = (type, count) => `
                    <div class="grid gap-4 lg:grid-cols-2 rounded-3xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-900 mb-2">${type} ${count}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">First Name</label>
                                <input type="text" name="passengers[${index}][first_name]" class="form-control rounded-3xl border-slate-200 bg-white text-sm" placeholder="First name" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Last Name</label>
                                <input type="text" name="passengers[${index}][last_name]" class="form-control rounded-3xl border-slate-200 bg-white text-sm" placeholder="Last name" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Nationality</label>
                                <input type="text" name="passengers[${index}][nationality]" class="form-control rounded-3xl border-slate-200 bg-white text-sm" placeholder="Nationality" required>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Date of Birth</label>
                                <input type="text" name="passengers[${index}][date_of_birth]" class="form-control rounded-3xl border-slate-200 bg-white text-sm passenger-dob" placeholder="YYYY-MM-DD" required readonly>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Passport Number</label>
                                <input type="text" name="passengers[${index}][passport_number]" class="form-control rounded-3xl border-slate-200 bg-white text-sm" placeholder="Passport number" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Passport Expiry</label>
                                <input type="text" name="passengers[${index}][passport_expiry]" class="form-control rounded-3xl border-slate-200 bg-white text-sm passenger-expiry" placeholder="YYYY-MM-DD" required readonly>
                            </div>
                        </div>
                    </div>
                `;

                for (let count = 1; count <= adultCount; count += 1) {
                    adultNamesList.insertAdjacentHTML('beforeend', `
                        <div>
                            <input type="hidden" name="passengers[${index}][passenger_type]" value="Adult">
                            ${fieldGroupMarkup('Adult', count)}
                        </div>
                    `);
                    index += 1;
                }

                for (let count = 1; count <= childCount; count += 1) {
                    childNamesList.insertAdjacentHTML('beforeend', `
                        <div>
                            <input type="hidden" name="passengers[${index}][passenger_type]" value="Child">
                            ${fieldGroupMarkup('Child', count)}
                        </div>
                    `);
                    index += 1;
                }

                for (let count = 1; count <= infantCount; count += 1) {
                    infantNamesList.insertAdjacentHTML('beforeend', `
                        <div>
                            <input type="hidden" name="passengers[${index}][passenger_type]" value="Infant">
                            ${fieldGroupMarkup('Infant', count)}
                        </div>
                    `);
                    index += 1;
                }

                setupPassengerDatepickers();
            }

            function setupPassengerDatepickers() {
                document.querySelectorAll('.passenger-dob').forEach((element) => {
                    if (!element._flatpickr) {
                        flatpickr(element, { dateFormat: 'Y-m-d', maxDate: 'today' });
                    }
                });
                document.querySelectorAll('.passenger-expiry').forEach((element) => {
                    if (!element._flatpickr) {
                        flatpickr(element, { dateFormat: 'Y-m-d', minDate: 'today' });
                    }
                });
            }

            function renderRoomTypeOptions(roomTypes, placeholderText = 'No rooms available for selected dates') {
                roomSelect.innerHTML = '';

                if (!roomTypes || roomTypes.length === 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.disabled = true;
                    option.selected = true;
                    option.dataset.rate = 0;
                    option.dataset.capacity = 0;
                    option.dataset.available = 0;
                    option.dataset.status = placeholderText;
                    option.textContent = placeholderText;
                    roomSelect.appendChild(option);
                    roomSelect.disabled = true;
                    return;
                }

                roomSelect.disabled = false;

                roomTypes.forEach((roomType, index) => {
                    const option = document.createElement('option');
                    option.value = roomType.id;
                    option.dataset.rate = roomType.rate;
                    option.dataset.capacity = roomType.capacity;
                    option.dataset.available = roomType.available_rooms;
                    option.dataset.status = roomType.status;
                    option.textContent = `${roomType.room_name} · SAR ${Number(roomType.rate).toFixed(2)} / night`;

                    if (roomType.available_rooms === 0 && roomType.status !== 'Select your dates to check availability') {
                        option.disabled = true;
                    }

                    if (index === 0) {
                        option.selected = true;
                    }
                    roomSelect.appendChild(option);
                });
            }

            function buildRoomOptionsForDates(availableRoomTypes) {
                return initialRoomTypes.map((baseType) => {
                    const matching = availableRoomTypes.find((type) => type.id === baseType.id);

                    if (matching) {
                        return {
                            ...baseType,
                            available_rooms: matching.available_rooms,
                            status: matching.status || 'Available',
                            unavailable_dates: matching.unavailable_dates || [],
                        };
                    }

                    return {
                        ...baseType,
                        available_rooms: 0,
                        status: 'Sold out for selected dates',
                        unavailable_dates: [],
                    };
                });
            }

            function fetchAvailability() {
                const startDate = checkIn.input.value;
                const endDate = checkOut.input.value;

                if (!startDate || !endDate) {
                    availabilityLoading = false;
                    renderRoomTypeOptions(initialRoomTypes, 'Select stay dates to see available room types');
                    updateSummary();
                    return;
                }

                availabilityLoading = true;
                renderRoomTypeOptions(initialRoomTypes, 'Checking availability...');
                updateSummary();

                fetch(`${window.location.pathname}?check_in=${encodeURIComponent(startDate)}&check_out=${encodeURIComponent(endDate)}&ajax=1`)
                    .then((response) => response.json())
                    .then((data) => {
                        availabilityLoading = false;
                        const mergedRoomTypes = buildRoomOptionsForDates(data.roomTypeAvailabilities || []);
                        renderRoomTypeOptions(mergedRoomTypes, 'No rooms available for selected dates');
                        highlightUnavailableDates(mergedRoomTypes);
                        updateSummary();
                    })
                    .catch(() => {
                        availabilityLoading = false;
                        const mergedRoomTypes = buildRoomOptionsForDates([]);
                        renderRoomTypeOptions(mergedRoomTypes, 'No rooms available for selected dates');
                        updateSummary();
                    });
            }

            function highlightUnavailableDates(roomTypes) {
                const selectedRoom = roomTypes.find((type) => type.id === parseInt(roomSelect.value, 10));
                const unavailable = selectedRoom?.unavailable_dates || [];

                if (unavailable.length === 0) {
                    return;
                }

                unavailable.forEach((date) => {
                    const dateInput = document.querySelector(`[name="check_in"]`);
                    if (dateInput) {
                        dateInput.dataset.unavailable = unavailable.join(',');
                    }
                });
            }

            function handleBookingSubmit() {
                reserveButton.disabled = true;
                reserveButtonText.textContent = 'Submitting...';
                reserveButtonSpinner.classList.remove('hidden');
            }

            function populateOldPassengerValues() {
                if (!oldPassengers.length) {
                    return;
                }

                oldPassengers.forEach((passenger, index) => {
                    if (!passenger) {
                        return;
                    }

                    const setValue = (field, value) => {
                        const input = document.querySelector(`[name="passengers[${index}][${field}]"]`);
                        if (input && value !== undefined && value !== null) {
                            input.value = value;
                        }
                    };

                    setValue('first_name', passenger.first_name);
                    setValue('last_name', passenger.last_name);
                    setValue('nationality', passenger.nationality);
                    setValue('date_of_birth', passenger.date_of_birth);
                    setValue('passport_number', passenger.passport_number);
                    setValue('passport_expiry', passenger.passport_expiry);
                });
            }

            function enforceChildLimit() {
                const childCount = parseInt(children.value, 10);
                if (childCount > 5) {
                    childLimitMessage.classList.remove('hidden');
                    window.alert('More than 5 children requires booking a second room. Please adjust your selection.');
                    children.value = '5';
                    childLimitMessage.textContent = 'Maximum 5 children allowed. Book 2 rooms for larger groups.';
                    return true;
                }

                childLimitMessage.classList.add('hidden');
                childLimitMessage.textContent = 'For more than 5 children, please book 2 rooms.';
                return false;
            }

            roomSelect.addEventListener('change', updateSummary);
            includeMeal.addEventListener('change', updateSummary);
            mealSelect.addEventListener('change', updateSummary);
            adults.addEventListener('change', updateSummary);
            children.addEventListener('change', () => {
                if (!enforceChildLimit()) {
                    updateSummary();
                }
            });
            infants.addEventListener('change', updateSummary);
            checkIn.input.addEventListener('change', fetchAvailability);
            checkOut.input.addEventListener('change', fetchAvailability);
            bookingForm.addEventListener('submit', function (event) {
                const availability = parseInt(roomSelect.selectedOptions[0].dataset.available || '0', 10);
                const hasDates = checkIn.input.value && checkOut.input.value;

                if (!hasDates || availability <= 0) {
                    event.preventDefault();

                    if (!hasDates) {
                        bookingErrorContainer.classList.remove('hidden');
                        bookingErrorList.innerHTML = '<li>Please select valid check-in and check-out dates before reserving.</li>';
                        roomAvailabilityText.textContent = 'Select stay dates to check availability.';
                        roomAvailabilityText.classList.remove('text-slate-500');
                        roomAvailabilityText.classList.add('text-red-600');
                    } else {
                        bookingErrorContainer.classList.remove('hidden');
                        bookingErrorList.innerHTML = '<li>No available rooms exist for the selected dates. Please change your dates or room type.</li>';
                    }

                    return;
                }

                handleBookingSubmit();
            });

            fetchAvailability();
        });
    
