@php
    $hotel = $hotel ?? null;
    $isEdit = $isEdit ?? false;
@endphp

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="grid gap-6 xl:grid-cols-[1.5fr_0.9fr]">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Hotel Setup Wizard</p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-900">Complete hotel setup in one page</h2>
                        <p class="mt-2 text-sm text-slate-500">Use the steps below to add hotel details, policies, location, and gallery assets without leaving the page.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span id="wizardStepBadge" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Step 1 of 4</span>
                        <span class="rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">{{ $submitLabel }}</span>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <button type="button" data-step="1" class="wizard-step-trigger rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:border-slate-300">Details</button>
                    <button type="button" data-step="2" class="wizard-step-trigger rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:border-slate-300">Policies</button>
                    <button type="button" data-step="3" class="wizard-step-trigger rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:border-slate-300">Location</button>
                    <button type="button" data-step="4" class="wizard-step-trigger rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:border-slate-300">Gallery</button>
                </div>
            </div>

            <div class="space-y-6">
                <div class="wizard-step" data-step="1">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Step 1: Hotel details</h3>
                                <p class="mt-1 text-sm text-slate-500">Define the hotel headline, category, city, and status.</p>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 lg:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Hotel Name</label>
                                <input name="hotel_name" value="{{ old('hotel_name', $hotel?->hotel_name) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                                @error('hotel_name')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Hotel Code</label>
                                <input name="hotel_code" value="{{ old('hotel_code', $hotel?->hotel_code) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                                @error('hotel_code')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">City</label>
                                <select name="city" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                                    <option value="">Select city</option>
                                    <option value="Makkah" @selected(old('city', $hotel?->city) === 'Makkah')>Makkah</option>
                                    <option value="Madina" @selected(old('city', $hotel?->city) === 'Madina')>Madina</option>
                                </select>
                                @error('city')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Category</label>
                                <select name="category" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                                    <option value="3 Star" @selected(old('category', $hotel?->category) === '3 Star')>3 Star</option>
                                    <option value="4 Star" @selected(old('category', $hotel?->category) === '4 Star')>4 Star</option>
                                    <option value="5 Star" @selected(old('category', $hotel?->category) === '5 Star')>5 Star</option>
                                </select>
                                @error('category')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                                <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                                    <option value="Active" @selected(old('status', $hotel?->status) === 'Active')>Active</option>
                                    <option value="Inactive" @selected(old('status', $hotel?->status) === 'Inactive')>Inactive</option>
                                </select>
                                @error('status')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Portal visibility</label>
                                <select name="visibility" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                                    <option value="Both" @selected(old('visibility', $hotel?->visibility ?? 'Both') === 'Both')>Both (Customer + Agent)</option>
                                    <option value="Agent Only" @selected(old('visibility', $hotel?->visibility) === 'Agent Only')>Agent Only</option>
                                    <option value="Customer Only" @selected(old('visibility', $hotel?->visibility) === 'Customer Only')>Customer Only</option>
                                </select>
                                @error('visibility')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Distance from Haram (km)</label>
                                <input name="distance_from_haram" type="number" step="0.01" value="{{ old('distance_from_haram', $hotel?->distance_from_haram) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                                @error('distance_from_haram')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex items-center gap-3 pt-6">
                                <input type="hidden" name="featured" value="0" />
                                <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <input type="checkbox" name="featured" value="1" class="h-4 w-4 text-emerald-600" @checked(old('featured', $hotel?->featured) == '1') />
                                    <span class="text-sm text-slate-700">Featured hotel</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wizard-step hidden" data-step="2">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Step 2: Stay policies</h3>
                                <p class="mt-1 text-sm text-slate-500">Add policy highlights that guests will see on the hotel profile.</p>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">About this hotel</label>
                                <textarea name="about" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">{{ old('about', $hotel?->about) }}</textarea>
                                @error('about')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Free cancellation</label>
                                    <textarea name="stay_policy_free_cancellation" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">{{ old('stay_policy_free_cancellation', $hotel?->stay_policy_free_cancellation) }}</textarea>
                                    @error('stay_policy_free_cancellation')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Haram shuttle</label>
                                    <textarea name="stay_policy_haram_shuttle" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">{{ old('stay_policy_haram_shuttle', $hotel?->stay_policy_haram_shuttle) }}</textarea>
                                    @error('stay_policy_haram_shuttle')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Flexible check-in</label>
                                    <textarea name="stay_policy_flexible_checkin" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">{{ old('stay_policy_flexible_checkin', $hotel?->stay_policy_flexible_checkin) }}</textarea>
                                    @error('stay_policy_flexible_checkin')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Inclusive breakfast</label>
                                    <textarea name="stay_policy_inclusive_breakfast" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">{{ old('stay_policy_inclusive_breakfast', $hotel?->stay_policy_inclusive_breakfast) }}</textarea>
                                    @error('stay_policy_inclusive_breakfast')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wizard-step hidden" data-step="3">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Step 3: Location & contact</h3>
                                <p class="mt-1 text-sm text-slate-500">Capture the hotel address, coordinates, and contact details.</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Address</label>
                                <textarea name="address" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">{{ old('address', $hotel?->address) }}</textarea>
                                @error('address')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Latitude</label>
                                    <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $hotel?->latitude) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                                    @error('latitude')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Longitude</label>
                                    <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $hotel?->longitude) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                                    @error('longitude')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Phone</label>
                                    <input name="phone" value="{{ old('phone', $hotel?->phone) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                                    @error('phone')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $hotel?->email) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                                    @error('email')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Website</label>
                                <input type="url" name="website" value="{{ old('website', $hotel?->website) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                                @error('website')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wizard-step hidden" data-step="4">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Step 4: Hotel gallery</h3>
                                <p class="mt-1 text-sm text-slate-500">Upload photos and manage existing gallery assets for this hotel.</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Upload Images</label>
                                <input type="file" name="images[]" multiple accept="image/png,image/jpeg,image/jpg" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                                @if ($errors->has('images'))
                                    <p class="mt-1 text-xs text-rose-500">{{ $errors->first('images') }}</p>
                                @endif
                                @foreach ($errors->get('images.*') as $message)
                                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                @endforeach
                                <p class="mt-3 text-xs text-slate-400">Accepted formats: JPG, JPEG, PNG. Max file size: 5MB each.</p>
                            </div>

                            @if($hotel?->images?->isNotEmpty())
                                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach($hotel->images as $image)
                                        <div class="rounded-3xl border border-slate-200 bg-white p-3 shadow-sm" data-image-id="{{ $image->id }}">
                                            <img src="{{ Storage::disk('public')->url($image->path) }}" alt="Hotel image {{ $loop->iteration }}" class="h-36 w-full rounded-2xl object-cover" />
                                            <div class="mt-3 space-y-3 text-sm text-slate-600">
                                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                                    <label class="inline-flex items-center gap-2 text-slate-700">
                                                        <input type="radio" name="cover_image_id" value="{{ $image->id }}" @checked(old('cover_image_id', $hotel?->coverImage?->id) == $image->id) class="text-blue-600" />
                                                        Cover
                                                    </label>
                                                    <label class="inline-flex items-center gap-2 text-slate-700">
                                                        <input type="checkbox" name="is_active[]" value="{{ $image->id }}" @checked(old('is_active') ? in_array($image->id, old('is_active', [])) : $image->is_active) class="h-4 w-4 text-blue-600" />
                                                        Active
                                                    </label>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <div class="w-full">
                                                        <label class="block text-xs text-slate-500">Order</label>
                                                        <input type="number" name="existing_image_order[{{ $image->id }}]" value="{{ old('existing_image_order.' . $image->id, $image->sort_order) }}" min="0" class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700" />
                                                    </div>
                                                    <button type="button" class="remove-image-button text-rose-600 hover:text-rose-700 text-xs font-semibold">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div id="removedImagesContainer"></div>
                        </div>

                        <p class="mt-4 text-xs text-slate-400">The first uploaded image will become the default cover until you choose another option.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <a href="{{ route('admin.hotels.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                    <div class="ml-auto flex items-center gap-3">
                        <button type="button" id="wizardPrevButton" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50" disabled>Previous</button>
                        <button type="button" id="wizardNextButton" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Next</button>
                        <button type="submit" id="wizardSubmitButton" class="rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-500">{{ $submitLabel }}</button>
                    </div>
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-slate-950 p-6 text-white shadow-sm">
                <h3 class="text-xl font-semibold">Hotel setup summary</h3>
                <p class="mt-3 text-sm leading-6 text-slate-300">This wizard keeps hotel creation and editing on a single page so your hotel details, policy highlights, location, and gallery stay together.</p>
                <div class="mt-6 space-y-3 text-sm text-slate-300">
                    <p><strong>What’s included</strong></p>
                    <ul class="space-y-2 pl-4 text-slate-300">
                        <li>• Property basics and category</li>
                        <li>• Stay policy details</li>
                        <li>• Address, coordinates, and contact info</li>
                        <li>• Image upload and gallery management</li>
                    </ul>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-slate-900">Related hotel setup sections</h3>
                <p class="mt-3 text-sm text-slate-500">After saving the hotel, continue building its room and package configuration from these linked sections.</p>
                <div class="mt-5 grid gap-3">
                    <a href="{{ route('admin.hotel-room-types.index') }}" class="block rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-900 hover:bg-slate-100">Room types</a>
                    <a href="{{ route('admin.hotel-seasonal-rates.index') }}" class="block rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-900 hover:bg-slate-100">Seasonal rates</a>
                    <a href="{{ route('admin.hotel-meal-plans.index') }}" class="block rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-900 hover:bg-slate-100">Meal plans</a>
                    <a href="{{ route('admin.hotel-room-inventory.index') }}" class="block rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-900 hover:bg-slate-100">Room inventory</a>
                </div>
            </div>
        </aside>
    </form>
</div>

@push('scripts')
    <script>
        (function () {
            const initWizard = function () {
                const stepButtons = Array.from(document.querySelectorAll('.wizard-step-trigger'));
                const steps = Array.from(document.querySelectorAll('.wizard-step'));
                const prevButton = document.getElementById('wizardPrevButton');
                const nextButton = document.getElementById('wizardNextButton');
                const submitButton = document.getElementById('wizardSubmitButton');
                const stepBadge = document.getElementById('wizardStepBadge');
                const form = document.querySelector('form');
                let currentStep = 1;
                const totalSteps = steps.length;

                if (!stepButtons.length || !steps.length || !prevButton || !nextButton || !submitButton || !stepBadge || !form) {
                    return;
                }

                document.querySelector('form').addEventListener('submit', () => {
                    console.log('FORM SUBMITTED');
                });

                document.getElementById('wizardSubmitButton').addEventListener('click', () => {
                    console.log('BUTTON CLICKED');
                });

                form.addEventListener('invalid', function (event) {
                    const invalidControl = event.target;
                    const stepSection = invalidControl.closest('.wizard-step');
                    if (!stepSection) {
                        return;
                    }

                    const invalidStep = Number(stepSection.dataset.step);
                    if (invalidStep !== currentStep) {
                        currentStep = invalidStep;
                        updateWizard();
                        invalidControl.focus({ preventScroll: true });
                    }
                }, true);

                const fieldStepMapping = {
                    hotel_name: 1,
                    hotel_code: 1,
                    city: 1,
                    category: 1,
                    status: 1,
                    visibility: 1,
                    distance_from_haram: 1,
                    featured: 1,
                    about: 2,
                    stay_policy_free_cancellation: 2,
                    stay_policy_haram_shuttle: 2,
                    stay_policy_flexible_checkin: 2,
                    stay_policy_inclusive_breakfast: 2,
                    address: 3,
                    latitude: 3,
                    longitude: 3,
                    phone: 3,
                    email: 3,
                    website: 3,
                    images: 4,
                    cover_image_id: 4,
                    is_active: 4,
                    existing_image_order: 4,
                    remove_images: 4,
                };

                let serverErrorFields = @json($errors->keys());
                if (!Array.isArray(serverErrorFields)) {
                    serverErrorFields = [];
                }

                for (const key of serverErrorFields) {
                    const baseName = String(key).split('.')[0];
                    if (fieldStepMapping[baseName]) {
                        currentStep = fieldStepMapping[baseName];
                        break;
                    }
                }

                function updateWizard() {
                    steps.forEach((stepSection) => {
                        stepSection.classList.toggle('hidden', Number(stepSection.dataset.step) !== currentStep);
                    });

                    stepButtons.forEach((button) => {
                        const step = Number(button.dataset.step);
                        if (step === currentStep) {
                            button.classList.add('bg-slate-950', 'text-white', 'border-slate-950');
                            button.classList.remove('bg-white', 'text-slate-700');
                        } else {
                            button.classList.add('bg-white', 'text-slate-700');
                            button.classList.remove('bg-slate-950', 'text-white', 'border-slate-950');
                        }
                    });

                    prevButton.disabled = currentStep === 1;
                    if (currentStep === totalSteps) {
                        nextButton.style.display = 'none';
                        submitButton.style.display = 'inline-flex';
                        submitButton.classList.remove('hidden');
                    } else {
                        nextButton.style.display = 'inline-flex';
                        submitButton.style.display = 'none';
                    }
                    stepBadge.textContent = `Step ${currentStep} of ${totalSteps}`;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

                stepButtons.forEach((button) => {
                    button.addEventListener('click', function () {
                        currentStep = Number(button.dataset.step);
                        updateWizard();
                    });
                });

                prevButton.addEventListener('click', function () {
                    if (currentStep > 1) {
                        currentStep -= 1;
                        updateWizard();
                    }
                });

                nextButton.addEventListener('click', function () {
                    if (currentStep < totalSteps) {
                        currentStep += 1;
                        updateWizard();
                    }
                });

                document.querySelectorAll('.remove-image-button').forEach(function (button) {
                    button.addEventListener('click', function (event) {
                        const card = event.currentTarget.closest('[data-image-id]');
                        if (!card) {
                            return;
                        }

                        const imageId = card.dataset.imageId;
                        const form = card.closest('form');
                        if (!form) {
                            return;
                        }

                        if (!form.querySelector(`input[name="remove_images[]"][value="${imageId}"]`)) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'remove_images[]';
                            input.value = imageId;
                            form.appendChild(input);
                        }

                        card.remove();
                    });
                });

                function updateWizard() {
                    steps.forEach((stepSection) => {
                        const isActive = Number(stepSection.dataset.step) === currentStep;
                        stepSection.classList.toggle('hidden', !isActive);
                    });

                    stepButtons.forEach((button) => {
                        const step = Number(button.dataset.step);
                        if (step === currentStep) {
                            button.classList.add('bg-slate-950', 'text-white', 'border-slate-950');
                            button.classList.remove('bg-white', 'text-slate-700');
                        } else {
                            button.classList.add('bg-white', 'text-slate-700');
                            button.classList.remove('bg-slate-950', 'text-white', 'border-slate-950');
                        }
                    });

                    prevButton.disabled = currentStep === 1;
                    if (currentStep === totalSteps) {
                        nextButton.style.display = 'none';
                        submitButton.style.display = 'inline-flex';
                        submitButton.classList.remove('hidden');
                    } else {
                        nextButton.style.display = 'inline-flex';
                        submitButton.style.display = 'none';
                    }
                    stepBadge.textContent = `Step ${currentStep} of ${totalSteps}`;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

                updateWizard();
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initWizard);
            } else {
                initWizard();
            }
        }());
    </script>
@endpush
