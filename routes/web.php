<?php

use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminHotelManagementController;
use App\Http\Controllers\AdminHotelRoomTypeController;
use App\Http\Controllers\AdminHotelFacilityController;
use App\Http\Controllers\AdminHotelMealPlanController;
use App\Http\Controllers\AdminHotelSeasonalRateController;
use App\Http\Controllers\AdminTravelAgentController;
use App\Http\Controllers\TravelAgentAuthController;
use App\Http\Controllers\TravelAgentRegistrationController;
use App\Http\Controllers\TravelAgentHotelController;
use App\Http\Controllers\PublicHotelBookingConfirmationController;
use App\Http\Controllers\PublicHotelBookingController;
use App\Http\Controllers\PublicHotelController;
use App\Http\Controllers\AdminHotelRoomInventoryController;
use App\Http\Controllers\AdminRoomBlockController;
use Illuminate\Support\Facades\Route;
use App\Models\Ticket;
$adminPages = [
    'user-management' => 'User Management',
    'customer-management' => 'Customer Management',
    'agent-management' => 'Agent Management',
    'airline-ticket-management' => 'Airline / Ticket Management',
    'hotel-management' => 'Hotel Management',
    'visa-management' => 'Visa Management',
    'package-builder' => 'Package Builder',
    'dynamic-package-calculator' => 'Dynamic Package Calculator',
    'quote-management' => 'Quote Management',
    'booking-management' => 'Booking Management',
    'transport-management' => 'Transport Management',
    'voucher-management' => 'Voucher Management',
    'payment-management' => 'Payment Management',
    'accounting' => 'Accounting',
    'crm' => 'CRM',
    'reports' => 'Reports',
    'notifications' => 'Notifications',
    'website-cms' => 'Website CMS',
    'dynamic-package-builder' => 'Dynamic Package Builder',
];

// Default welcome page (Agar login nahi hai to auto login page par le jaye)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::middleware('guest:travel_agent')->group(function () {
    Route::get('/travel-agent/register', [TravelAgentRegistrationController::class, 'create'])->name('travel-agents.register');
    Route::post('/travel-agent/register', [TravelAgentRegistrationController::class, 'store']);
    Route::get('/travel-agent/register/success', [TravelAgentRegistrationController::class, 'success'])->name('travel-agents.register.success');

    Route::get('/travel-agent/login', [TravelAgentAuthController::class, 'showLoginForm'])->name('travel-agents.login');
    Route::post('/travel-agent/login', [TravelAgentAuthController::class, 'login'])->name('travel-agents.login.submit');
});

Route::middleware('auth:travel_agent')->group(function () {
    Route::get('/travel-agent/dashboard', [TravelAgentAuthController::class, 'dashboard'])->name('travel-agents.dashboard');
    Route::post('/travel-agent/logout', [TravelAgentAuthController::class, 'logout'])->name('travel-agents.logout');
});

Route::get('/travel-agents/hotels/index', [TravelAgentHotelController::class, 'index'])->name('travel-agents.hotels.index');
Route::get('/travel-agent/hotels', fn () => redirect()->route('travel-agents.hotels.index'));

Route::get('/hotels/booking', [PublicHotelBookingController::class, 'index'])->name('hotels.booking');
Route::post('/hotels/book', [PublicHotelBookingController::class, 'store'])->name('hotels.book');
Route::post('/hotels/cancel/{booking}', [PublicHotelBookingController::class, 'cancel'])->name('hotels.booking.cancel');
Route::get('/hotels/booking/confirmation/{booking}', [PublicHotelBookingConfirmationController::class, 'show'])->name('hotels.booking.confirmation');
Route::get('/hotels/{hotel}', [PublicHotelController::class, 'show'])->name('hotels.details');
Route::get('/hotels', fn () => redirect()->route('hotels.booking'));

Route::middleware(['auth'])->group(function () use ($adminPages) {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/admin/booking-management', fn () => redirect()->route('admin.bookings.index'))->name('admin.booking-management');
    Route::get('/admin/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('/admin/bookings/export', [AdminBookingController::class, 'export'])->name('admin.bookings.export');
    Route::get('/admin/bookings/{booking}/edit', [AdminBookingController::class, 'edit'])->name('admin.bookings.edit');
    Route::put('/admin/bookings/{booking}', [AdminBookingController::class, 'update'])->name('admin.bookings.update');
    Route::delete('/admin/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('admin.bookings.destroy');
    Route::get('/admin/bookings/{booking}/print', [AdminBookingController::class, 'print'])->name('admin.bookings.print');
    Route::get('/admin/bookings/{booking}', [AdminBookingController::class, 'show'])->name('admin.bookings.show');
    Route::post('/admin/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('admin.bookings.cancel');

    Route::get('/admin/hotel-management', [AdminHotelManagementController::class, 'index'])->name('admin.hotel-management');

    Route::get('/admin/hotels', [AdminHotelController::class, 'index'])->name('admin.hotels.index');
    Route::get('/admin/hotels/create', [AdminHotelController::class, 'create'])->name('admin.hotels.create');
    Route::post('/admin/hotels', [AdminHotelController::class, 'store'])->name('admin.hotels.store');
    Route::get('/admin/hotels/{hotel}/edit', [AdminHotelController::class, 'edit'])->name('admin.hotels.edit');
    Route::put('/admin/hotels/{hotel}', [AdminHotelController::class, 'update'])->name('admin.hotels.update');
    Route::delete('/admin/hotels/{hotel}', [AdminHotelController::class, 'destroy'])->name('admin.hotels.destroy');
    Route::get('/admin/hotels/export', [AdminHotelController::class, 'export'])->name('admin.hotels.export');
    Route::post('/admin/hotels/upload-images', [AdminHotelController::class, 'uploadImages'])->name('admin.hotels.upload-images');
    Route::get('/admin/hotel-images', [AdminHotelController::class, 'hotelImageIndex'])->name('admin.hotel-images.index');
    Route::get('/admin/hotel-images/{hotelImage}', [AdminHotelController::class, 'hotelImageShow'])->name('admin.hotel-images.show');
    Route::get('/admin/hotel-images/{hotelImage}/edit', [AdminHotelController::class, 'hotelImageEdit'])->name('admin.hotel-images.edit');
    Route::put('/admin/hotel-images/{hotelImage}', [AdminHotelController::class, 'hotelImageUpdate'])->name('admin.hotel-images.update');
    Route::delete('/admin/hotel-images/{hotelImage}', [AdminHotelController::class, 'hotelImageDestroy'])->name('admin.hotel-images.destroy');

    Route::get('/admin/hotel-room-types', [AdminHotelRoomTypeController::class, 'index'])->name('admin.hotel-room-types.index');
    Route::get('/admin/hotel-room-types/create', [AdminHotelRoomTypeController::class, 'create'])->name('admin.hotel-room-types.create');
    Route::post('/admin/hotel-room-types', [AdminHotelRoomTypeController::class, 'store'])->name('admin.hotel-room-types.store');
    Route::get('/admin/hotel-room-types/{hotel_room_type}/edit', [AdminHotelRoomTypeController::class, 'edit'])->name('admin.hotel-room-types.edit');
    Route::put('/admin/hotel-room-types/{hotel_room_type}', [AdminHotelRoomTypeController::class, 'update'])->name('admin.hotel-room-types.update');
    Route::delete('/admin/hotel-room-types/{hotel_room_type}', [AdminHotelRoomTypeController::class, 'destroy'])->name('admin.hotel-room-types.destroy');
    Route::get('/admin/hotel-room-types/export', [AdminHotelRoomTypeController::class, 'export'])->name('admin.hotel-room-types.export');

    Route::get('/admin/hotel-room-blocks', [AdminRoomBlockController::class, 'index'])->name('admin.room-blocks.index');
    Route::get('/admin/hotel-room-blocks/create', [AdminRoomBlockController::class, 'create'])->name('admin.room-blocks.create');
    Route::post('/admin/hotel-room-blocks', [AdminRoomBlockController::class, 'store'])->name('admin.room-blocks.store');
    Route::get('/admin/hotel-room-blocks/{room_block}/edit', [AdminRoomBlockController::class, 'edit'])->name('admin.room-blocks.edit');
    Route::put('/admin/hotel-room-blocks/{room_block}', [AdminRoomBlockController::class, 'update'])->name('admin.room-blocks.update');
    Route::delete('/admin/hotel-room-blocks/{room_block}', [AdminRoomBlockController::class, 'destroy'])->name('admin.room-blocks.destroy');
    Route::get('/admin/hotel-room-blocks/calendar', [AdminRoomBlockController::class, 'calendar'])->name('admin.room-blocks.calendar');

    Route::get('/admin/hotel-seasonal-rates', [AdminHotelSeasonalRateController::class, 'index'])->name('admin.hotel-seasonal-rates.index');
    Route::get('/admin/hotel-seasonal-rates/create', [AdminHotelSeasonalRateController::class, 'create'])->name('admin.hotel-seasonal-rates.create');
    Route::post('/admin/hotel-seasonal-rates', [AdminHotelSeasonalRateController::class, 'store'])->name('admin.hotel-seasonal-rates.store');
    Route::get('/admin/hotel-seasonal-rates/{hotel_seasonal_rate}/edit', [AdminHotelSeasonalRateController::class, 'edit'])->name('admin.hotel-seasonal-rates.edit');
    Route::put('/admin/hotel-seasonal-rates/{hotel_seasonal_rate}', [AdminHotelSeasonalRateController::class, 'update'])->name('admin.hotel-seasonal-rates.update');
    Route::delete('/admin/hotel-seasonal-rates/{hotel_seasonal_rate}', [AdminHotelSeasonalRateController::class, 'destroy'])->name('admin.hotel-seasonal-rates.destroy');
    Route::get('/admin/hotel-seasonal-rates/export', [AdminHotelSeasonalRateController::class, 'export'])->name('admin.hotel-seasonal-rates.export');

    Route::get('/admin/hotel-meal-plans', [AdminHotelMealPlanController::class, 'index'])->name('admin.hotel-meal-plans.index');
    Route::get('/admin/hotel-meal-plans/create', [AdminHotelMealPlanController::class, 'create'])->name('admin.hotel-meal-plans.create');
    Route::post('/admin/hotel-meal-plans', [AdminHotelMealPlanController::class, 'store'])->name('admin.hotel-meal-plans.store');
    Route::get('/admin/hotel-meal-plans/{hotel_meal_plan}/edit', [AdminHotelMealPlanController::class, 'edit'])->name('admin.hotel-meal-plans.edit');
    Route::put('/admin/hotel-meal-plans/{hotel_meal_plan}', [AdminHotelMealPlanController::class, 'update'])->name('admin.hotel-meal-plans.update');
    Route::delete('/admin/hotel-meal-plans/{hotel_meal_plan}', [AdminHotelMealPlanController::class, 'destroy'])->name('admin.hotel-meal-plans.destroy');
    Route::get('/admin/hotel-meal-plans/export', [AdminHotelMealPlanController::class, 'export'])->name('admin.hotel-meal-plans.export');

    Route::get('/admin/hotel-facilities', [AdminHotelFacilityController::class, 'index'])->name('admin.hotel-facilities.index');
    Route::get('/admin/hotel-facilities/create', [AdminHotelFacilityController::class, 'create'])->name('admin.hotel-facilities.create');
    Route::post('/admin/hotel-facilities', [AdminHotelFacilityController::class, 'store'])->name('admin.hotel-facilities.store');
    Route::get('/admin/hotel-facilities/{hotel_facility}/edit', [AdminHotelFacilityController::class, 'edit'])->name('admin.hotel-facilities.edit');
    Route::put('/admin/hotel-facilities/{hotel_facility}', [AdminHotelFacilityController::class, 'update'])->name('admin.hotel-facilities.update');
    Route::delete('/admin/hotel-facilities/{hotel_facility}', [AdminHotelFacilityController::class, 'destroy'])->name('admin.hotel-facilities.destroy');
    Route::get('/admin/hotel-facilities/export', [AdminHotelFacilityController::class, 'export'])->name('admin.hotel-facilities.export');

    Route::get('/admin/hotel-room-inventory', [AdminHotelRoomInventoryController::class, 'index'])->name('admin.hotel-room-inventory.index');
    Route::get('/admin/hotel-room-inventory/create', [AdminHotelRoomInventoryController::class, 'create'])->name('admin.hotel-room-inventory.create');
    Route::post('/admin/hotel-room-inventory', [AdminHotelRoomInventoryController::class, 'store'])->name('admin.hotel-room-inventory.store');
    Route::get('/admin/hotel-room-inventory/{hotel_room_inventory}/edit', [AdminHotelRoomInventoryController::class, 'edit'])->name('admin.hotel-room-inventory.edit');
    Route::put('/admin/hotel-room-inventory/{hotel_room_inventory}', [AdminHotelRoomInventoryController::class, 'update'])->name('admin.hotel-room-inventory.update');
    Route::delete('/admin/hotel-room-inventory/{hotel_room_inventory}', [AdminHotelRoomInventoryController::class, 'destroy'])->name('admin.hotel-room-inventory.destroy');
    Route::get('/admin/hotel-room-inventory/export', [AdminHotelRoomInventoryController::class, 'export'])->name('admin.hotel-room-inventory.export');

    Route::get('/admin/agent-management', [AdminTravelAgentController::class, 'index'])->name('admin.agent-management');
    Route::get('/admin/agents', [AdminTravelAgentController::class, 'index'])->name('admin.agents.index');
    Route::get('/admin/agents/export/csv', [AdminTravelAgentController::class, 'exportCsv'])->name('admin.agents.export.csv');
    Route::get('/admin/agents/export/excel', [AdminTravelAgentController::class, 'exportExcel'])->name('admin.agents.export.excel');
    Route::get('/admin/agents/{agent}', [AdminTravelAgentController::class, 'show'])->name('admin.agents.show');
    Route::get('/admin/agents/{agent}/edit', [AdminTravelAgentController::class, 'edit'])->name('admin.agents.edit');
    Route::put('/admin/agents/{agent}', [AdminTravelAgentController::class, 'update'])->name('admin.agents.update');
    Route::post('/admin/agents/{agent}/approve', [AdminTravelAgentController::class, 'approve'])->name('admin.agents.approve');
    Route::post('/admin/agents/{agent}/reject', [AdminTravelAgentController::class, 'reject'])->name('admin.agents.reject');
    Route::delete('/admin/agents/{agent}', [AdminTravelAgentController::class, 'destroy'])->name('admin.agents.destroy');

    foreach ($adminPages as $slug => $title) {
        if (in_array($slug, ['agent-management', 'hotel-management', 'booking-management'], true)) {
            continue;
        }
        Route::view("/admin/{$slug}", "admin.{$slug}", ['pageTitle' => $title])
            ->name("admin.{$slug}");
    }
});
Route::get('/ticket', function () {
    return view('user.ticket');
});

Route::get('/user/ticket', function () {
    return view('user.ticket');
});