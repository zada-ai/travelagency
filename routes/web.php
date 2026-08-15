<?php

use App\Http\Controllers\AdminAirlineController;
// Controllers Import
use App\Http\Controllers\AdminAirlineFlightController;
use App\Http\Controllers\AdminAirportController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\AdminFlightBookingController;
use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AdminHotelFacilityController;
use App\Http\Controllers\AdminHotelManagementController;
use App\Http\Controllers\AdminHotelMealPlanController;
use App\Http\Controllers\AdminHotelRoomInventoryController;
use App\Http\Controllers\AdminHotelRoomTypeController;
use App\Http\Controllers\AdminHotelSeasonalRateController;
use App\Http\Controllers\AdminPackageBookingController;
use App\Http\Controllers\AdminPackageController;
use App\Http\Controllers\AdminRoomBlockController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\AdminTravelAgentController;
use App\Http\Controllers\AdminVisaApplicationController;
use App\Http\Controllers\AdminVisaTypeController;
use App\Http\Controllers\AdminVoucherSettingController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CustomerRegistrationController;
use App\Http\Controllers\CustomerVisaController;
use App\Http\Controllers\PackageBookingController;
use App\Http\Controllers\PublicHotelBookingConfirmationController;
use App\Http\Controllers\PublicHotelBookingController;
use App\Http\Controllers\PublicHotelController;
use App\Http\Controllers\PublicTicketController;
use App\Http\Controllers\TravelAgentAuthController;
use App\Http\Controllers\HotelVoucherController;
use App\Http\Controllers\TravelAgentBookingHistoryController;
use App\Http\Controllers\TravelAgentCommissionController;
use App\Http\Controllers\TravelAgentCustomerVisaController;
use App\Http\Controllers\TravelAgentHotelController;
use App\Http\Controllers\TravelAgentRegistrationController;
use App\Http\Controllers\TravelAgentReportsController;
use App\Http\Controllers\CustomerVoucherController;
use App\Http\Controllers\AdminCustomerVoucherController;
use App\Http\Controllers\TravelAgentVisaApplicationController;
use App\Http\Controllers\VisaOfficerController;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

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

/*
|--------------------------------------------------------------------------
| DEFAULT / WELCOME ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $featuredPackages = Package::query()
        ->visibleToCustomers()
        ->where('status', 'Active')
        ->where('available_seats', '>', 0)
        ->latest()
        ->take(4)
        ->get();

    $featuredHotels = Hotel::active()
        ->visibleToPortal('customer')
        ->with('coverImage')
        ->latest('id')
        ->take(3)
        ->get();

    $featuredFlights = Ticket::forPortal('customer')
        ->whereNotIn('status', ['Cancelled', 'Rejected'])
        ->where('available_seats', '>', 0)
        ->orderByDesc('created_at')
        ->take(3)
        ->get();

    return view('home', compact('featuredPackages', 'featuredHotels', 'featuredFlights'));
})->name('home');

Route::get('/register', [CustomerRegistrationController::class, 'create'])->name('register');
Route::post('/register', [CustomerRegistrationController::class, 'store'])->name('register.submit');
/*

|--------------------------------------------------------------------------
| TRAVEL AGENT AUTHENTICATION (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:travel_agent')->group(function () {
    Route::get('/travel-agent/register', [TravelAgentRegistrationController::class, 'create'])->name('travel-agents.register');
    Route::post('/travel-agent/register', [TravelAgentRegistrationController::class, 'store'])->name('travel-agents.register.submit');
    Route::get('/travel-agent/register/success', [TravelAgentRegistrationController::class, 'success'])->name('travel-agents.register.success');

    Route::get('/travel-agent/login', [TravelAgentAuthController::class, 'showLoginForm'])->name('travel-agents.login');
    Route::post('/travel-agent/login', [TravelAgentAuthController::class, 'login'])->name('travel-agents.login.submit');
    Route::get('/travel-agent/password/reset', [TravelAgentAuthController::class, 'showForgotPasswordForm'])->name('travel-agents.password.request');
    Route::post('/travel-agent/password/email', [TravelAgentAuthController::class, 'sendResetLinkEmail'])->name('travel-agents.password.email');
    Route::get('/travel-agent/password/reset/{token}', [TravelAgentAuthController::class, 'showResetForm'])->name('travel-agents.password.reset');
    Route::post('/travel-agent/password/reset', [TravelAgentAuthController::class, 'resetPassword'])->name('travel-agents.password.update');
});

/*
|--------------------------------------------------------------------------
| TRAVEL AGENT PROTECTED ROUTES (LOGGED IN AGENT)
|--------------------------------------------------------------------------
*/
Route::get('/travel-agent/dashboard', [TravelAgentAuthController::class, 'dashboard'])
    ->middleware('auth:web,travel_agent')
    ->name('travel-agents.dashboard');

Route::middleware('auth:travel_agent')->group(function () {
    Route::get('/travel-agent/tickets', [TravelAgentAuthController::class, 'tickets'])->name('travel-agents.tickets');
    Route::post('/travel-agent/tickets/{ticket}/book', [AdminFlightBookingController::class, 'store'])->name('travel-agents.tickets.book');
    Route::get('/travel-agent/bookings', [AdminFlightBookingController::class, 'agentBookings'])->name('travel-agents.bookings');
    Route::get('/travel-agent/bookings/review', [AdminFlightBookingController::class, 'review'])->name('travel-agents.bookings.review');
    Route::post('/travel-agent/bookings/confirm', [AdminFlightBookingController::class, 'confirm'])->name('travel-agents.bookings.confirm');
    Route::get('/travel-agent/bookings/confirmation/{flightBooking}', [AdminFlightBookingController::class, 'confirmation'])->name('travel-agents.bookings.confirmation');
    Route::post('/travel-agent/bookings/cancel-review', [AdminFlightBookingController::class, 'cancelReview'])->name('travel-agents.bookings.cancel-review');
    Route::get('/travel-agent/group-booking', [TravelAgentHotelController::class, 'groupBooking'])->name('travel-agents.group-booking');

    // Agent Visa Applications
    Route::get('/travel-agent/visa-applications', [TravelAgentVisaApplicationController::class, 'index'])->name('travel-agents.visa-applications');
    Route::get('/travel-agent/visa-applications/create', [TravelAgentVisaApplicationController::class, 'create'])->name('travel-agents.visa-applications.create');

    Route::get('/travel-agent/sub-agents', [TravelAgentRegistrationController::class, 'indexSubAgents'])->name('travel-agents.sub-agents.index');
    Route::get('/travel-agent/sub-agents/{subAgent}', [TravelAgentRegistrationController::class, 'showSubAgent'])->name('travel-agents.sub-agents.show');
    Route::get('/travel-agent/sub-agents/{subAgent}/edit', [TravelAgentRegistrationController::class, 'editSubAgent'])->name('travel-agents.sub-agents.edit');
    Route::put('/travel-agent/sub-agents/{subAgent}', [TravelAgentRegistrationController::class, 'updateSubAgent'])->name('travel-agents.sub-agents.update');
    Route::delete('/travel-agent/sub-agents/{subAgent}', [TravelAgentRegistrationController::class, 'destroySubAgent'])->name('travel-agents.sub-agents.destroy');

    Route::get('/travel-agent/sub-agent/create', [TravelAgentRegistrationController::class, 'createSubAgent'])->name('travel-agents.sub-agents.create');
    Route::post('/travel-agent/sub-agent', [TravelAgentRegistrationController::class, 'storeSubAgent'])->name('travel-agents.sub-agents.store');
    Route::post('/travel-agent/visa-applications', [TravelAgentVisaApplicationController::class, 'store'])->name('travel-agents.visa-applications.store');
    Route::get('/travel-agent/visa-applications/{id}', [TravelAgentVisaApplicationController::class, 'show'])->name('travel-agents.visa-applications.show');
    Route::get('/travel-agent/visa-applications/{id}/edit', [TravelAgentVisaApplicationController::class, 'edit'])->name('travel-agents.visa-applications.edit');
    Route::put('/travel-agent/visa-applications/{id}', [TravelAgentVisaApplicationController::class, 'update'])->name('travel-agents.visa-applications.update');
    Route::delete('/travel-agent/visa-applications/{id}', [TravelAgentVisaApplicationController::class, 'destroy'])->name('travel-agents.visa-applications.destroy');
    Route::get('/travel-agent/visa-applications/{id}/document/{field}', [TravelAgentVisaApplicationController::class, 'downloadDocument'])->name('travel-agents.visa-applications.document.download');
    Route::get('/travel-agent/visa-applications/{id}/print', [TravelAgentVisaApplicationController::class, 'print'])->name('travel-agents.visa-applications.print');

    // Customer Visa History
    Route::get('/travel-agent/customer-visa', [TravelAgentCustomerVisaController::class, 'index'])->name('travel-agents.customer-visa.index');
    Route::get('/travel-agent/customer-visa/{id}', [TravelAgentCustomerVisaController::class, 'show'])->name('travel-agents.customer-visa.show');
    Route::get('/travel-agent/customer-visa/{id}/download-visa', [TravelAgentCustomerVisaController::class, 'downloadVisaCopy'])->name('travel-agents.customer-visa.download-visa');
    Route::get('/travel-agent/customer-visa/{id}/download-document/{field}', [TravelAgentCustomerVisaController::class, 'downloadDocument'])->name('travel-agents.customer-visa.download-document');
    // Agent Package Booking Voucher
    Route::get(
        '/travel-agent/package-bookings/{id}/voucher',
        [AdminPackageBookingController::class, 'voucher']
    )->name('travel-agents.package-bookings.voucher');
    // Booking History
    Route::get('/travel-agent/booking-history', [TravelAgentBookingHistoryController::class, 'index'])->name('travel-agents.booking-history.index');
    Route::get('/travel-agent/booking-history/hotel/{id}', [TravelAgentBookingHistoryController::class, 'showHotelBooking'])->name('travel-agents.booking-history.show-hotel');
    Route::get('/travel-agent/booking-history/flight/{id}', [TravelAgentBookingHistoryController::class, 'showFlightBooking'])->name('travel-agents.booking-history.show-flight');
    Route::post('/travel-agent/booking-history/hotel/{id}/cancel', [TravelAgentBookingHistoryController::class, 'cancelHotelBooking'])->name('travel-agents.booking-history.cancel-hotel');
    Route::post('/travel-agent/booking-history/flight/{id}/cancel', [TravelAgentBookingHistoryController::class, 'cancelFlightBooking'])->name('travel-agents.booking-history.cancel-flight');

    // Commission & Reports
    Route::get('/travel-agent/commission', [TravelAgentCommissionController::class, 'index'])->name('travel-agents.commission.index');
    Route::get('/travel-agent/reports', [TravelAgentReportsController::class, 'index'])->name('travel-agents.reports.index');
    Route::get('/travel-agent/reports/booking', [TravelAgentReportsController::class, 'bookingReport'])->name('travel-agents.reports.booking');
    Route::get('/travel-agent/reports/sales', [TravelAgentReportsController::class, 'salesReport'])->name('travel-agents.reports.sales');
    Route::get('/travel-agent/reports/commission', [TravelAgentReportsController::class, 'commissionReport'])->name('travel-agents.reports.commission');
    Route::get('/travel-agent/reports/visa', [TravelAgentReportsController::class, 'visaReport'])->name('travel-agents.reports.visa');
    Route::get('/travel-agent/reports/ticket', [TravelAgentReportsController::class, 'ticketReport'])->name('travel-agents.reports.ticket');
    Route::get('/travel-agent/reports/hotel', [TravelAgentReportsController::class, 'hotelReport'])->name('travel-agents.reports.hotel');
    Route::get('/travel-agent/reports/payment', [TravelAgentReportsController::class, 'paymentReport'])->name('travel-agents.reports.payment');
    Route::get('/travel-agent/reports/customer', [TravelAgentReportsController::class, 'customerReport'])->name('travel-agents.reports.customer');
    Route::get('/travel-agent/packages', [CustomerPackageController::class, 'index'])
        ->name('travel-agents.packages.index');
    // Agent Package Booking
    Route::get(
        '/travel-agent/packages/{package}/book',
        [PackageBookingController::class, 'create']
    )->name('travel-agents.packages.book');

    Route::post(
        '/travel-agent/packages/{package}/book',
        [PackageBookingController::class, 'store']
    )->name('travel-agents.packages.store');

    Route::post('/travel-agent/logout', [TravelAgentAuthController::class, 'logout'])->name('travel-agents.logout');
});

// Hotel Public / Agent Shortcuts
Route::get('/travel-agents/hotels/index', [TravelAgentHotelController::class, 'index'])->name('travel-agents.hotels.index');
Route::get('/travel-agent/hotels', fn () => redirect()->route('travel-agents.hotels.index'));

/*
|--------------------------------------------------------------------------
| PUBLIC HOTEL & TICKET ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/hotels/booking', [PublicHotelBookingController::class, 'index'])->name('hotels.booking');
Route::get('/hotels/filter', [PublicHotelBookingController::class, 'filter'])->name('hotels.filter');
Route::get('/hotels/availability', [PublicHotelBookingController::class, 'availability'])->name('hotels.availability');
Route::get('/hotels/{hotel}/booking', [PublicHotelBookingController::class, 'create'])->name('hotels.booking.create');
Route::get('/hotels/book/review', [PublicHotelBookingController::class, 'reviewShow'])->name('hotels.book.review.show');
Route::post('/hotels/book/review', [PublicHotelBookingController::class, 'review'])->name('hotels.book.review');
Route::post('/hotels/book/review/edit', [PublicHotelBookingController::class, 'reviewEdit'])->name('hotels.book.review.edit');
Route::post('/hotels/book', [PublicHotelBookingController::class, 'store'])->name('hotels.book');
Route::post('/hotels/cancel/{booking}', [PublicHotelBookingController::class, 'cancel'])->name('hotels.booking.cancel');
Route::get('/hotels/booking/confirmation/{booking}', [PublicHotelBookingConfirmationController::class, 'show'])->name('hotels.booking.confirmation');

Route::get('/hotels', fn () => redirect()->route('hotels.booking'));

Route::get('/ticket/{ticket}', [PublicTicketController::class, 'show'])->name('ticket.details');
Route::get('/packages', [CustomerPackageController::class, 'index'])->name('packages.index');
Route::get('/user/ticket', [PublicTicketController::class, 'index'])->name('tickets.index');

// Customer Ticket Booking (uses same controller as agent flow but for authenticated customers)
Route::middleware('auth:web,travel_agent')->group(function () {
    Route::post('/tickets/{ticket}/book', [AdminFlightBookingController::class, 'store'])->name('tickets.book');
    Route::get('/bookings/review', [AdminFlightBookingController::class, 'review'])->name('bookings.review');
    Route::post('/bookings/confirm', [AdminFlightBookingController::class, 'confirm'])->name('bookings.confirm');
    // New Customer Voucher routes (flight bookings)
    Route::get('/customer/vouchers/{flightBooking}', [CustomerVoucherController::class, 'show'])->name('customer.vouchers.show');
    Route::get('/customer/vouchers/{flightBooking}/download', [CustomerVoucherController::class, 'download'])->name('customer.vouchers.download');
    Route::get('/bookings/confirmation/{flightBooking}', [AdminFlightBookingController::class, 'confirmation'])->name('bookings.confirmation');
    Route::post('/bookings/cancel-review', [AdminFlightBookingController::class, 'cancelReview'])->name('bookings.cancel-review');
    Route::get('/customer/bookings', [AdminFlightBookingController::class, 'customerBookings'])->name('customer.bookings');
});

    Route::middleware('auth:web')->group(function () {
        Route::get('/packages/{package}/book', [PackageBookingController::class, 'create'])->name('packages.book');
        Route::post('/packages/{package}/book', [PackageBookingController::class, 'store'])->name('packages.store');
        Route::get('/customer/packages/create', [CustomerPackageController::class, 'index'])
            ->name('customer.packages.create');
        Route::get(
            '/customer/package-bookings/{id}/voucher',
            [AdminPackageBookingController::class, 'voucher']
        )->name('customer.package-bookings.voucher');

        Route::get('/customer/vouchers/package/{packageBooking}', [CustomerVoucherController::class, 'showPackage'])->name('customer.vouchers.package.show');
        Route::get('/customer/vouchers/package/{packageBooking}/download', [CustomerVoucherController::class, 'downloadPackage'])->name('customer.vouchers.package.download');

        Route::get('/user/ticket', [PublicTicketController::class, 'index'])
            ->name('tickets.index');
        Route::get('/hotels/{hotel}', [PublicHotelController::class, 'show'])->name('hotels.details');
    });

Route::get('/user/login', function () {
    return view('user.login');
});

/*
|--------------------------------------------------------------------------
| INTERNAL STAFF ROUTES (SUPER ADMIN & VISA OFFICE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () use ($adminPages) {

    $isVisaOfficer = static function ($user): bool {
        return $user->hasRole('Visa Officer')
            || in_array(strtolower((string) ($user->role ?? '')), ['visa_officer', 'visa office', 'visa officer'], true)
            || in_array(strtolower((string) ($user->designation ?? '')), ['visa_officer', 'visa officer'], true);
    };

    $isSuperAdmin = static function ($user) use ($isVisaOfficer): bool {
        return ! $isVisaOfficer($user)
            && ($user->hasRole('Super Admin')
                || in_array(strtolower((string) ($user->role ?? '')), ['super_admin', 'super admin', 'admin'], true));
    };

    $isCustomer = static function ($user): bool {
        return $user->hasRole('Customer')
            || in_array(strtolower((string) ($user->role ?? '')), ['customer'], true)
            || in_array(strtolower((string) ($user->designation ?? '')), ['customer'], true);
    };

    // Main Dashboard Redirect (Based on Role)
    Route::get('/dashboard', function () use ($isVisaOfficer, $isSuperAdmin, $isCustomer) {
        $user = auth('web')->user();

        if ($isVisaOfficer($user)) {
            return redirect()->route('visa-office.dashboard');
        }

        if ($isCustomer($user)) {
            return redirect()->route('customer.dashboard');
        }

        abort_unless($isSuperAdmin($user), 403);

        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
        ->middleware('auth')
        ->name('customer.dashboard');

    // Customer Visa Application Routes
    Route::prefix('customer/visa')->name('customer.visa.')->middleware('auth')->group(function () {
        Route::get('/', [CustomerVisaController::class, 'index'])->name('index');
        Route::get('/create', [CustomerVisaController::class, 'create'])->name('create');
        Route::post('/', [CustomerVisaController::class, 'store'])->name('store');
        Route::get('/{id}', [CustomerVisaController::class, 'show'])->name('show');
    });

    Route::get('/home', function () {
        return redirect()->route('customer.dashboard');
    })->middleware('auth')->name('customer.home');

    Route::get('/admin/dashboard', function () use ($isSuperAdmin) {
        abort_unless($isSuperAdmin(auth('web')->user()), 403);

        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Admin: New Voucher Management (separate from old voucher system)
    Route::get('/admin/vouchers', [AdminCustomerVoucherController::class, 'index'])->name('admin.vouchers.index');
    Route::get('/admin/vouchers/{voucher}', [AdminCustomerVoucherController::class, 'show'])->name('admin.vouchers.show');
    Route::post('/admin/vouchers/generate/flight/{flightBooking}', [AdminCustomerVoucherController::class, 'generate'])->name('admin.vouchers.generate.flight');
    Route::post('/admin/vouchers/generate/package/{packageBooking}', [AdminCustomerVoucherController::class, 'generatePackage'])->name('admin.vouchers.generate.package');
    Route::get('/admin/vouchers/{voucher}/download', [AdminCustomerVoucherController::class, 'download'])->name('admin.vouchers.download');
    // Voucher updates are not allowed from show view; transport_type is set at creation via generate routes.

    /*
    |--------------------------------------------------------------------------
    | VISA OFFICER ROUTES (/visa-office/*)
    |--------------------------------------------------------------------------
    */
    Route::prefix('visa-office')->name('visa-office.')->group(function () {
        Route::get('/dashboard', [VisaOfficerController::class, 'dashboard'])->name('dashboard');

        Route::get('/visa-management', [VisaOfficerController::class, 'visaManagement'])->name('visa-management');
        Route::get('/assigned', [VisaOfficerController::class, 'assigned'])->name('assigned');
        Route::get('/document-queue', [VisaOfficerController::class, 'documentQueue'])->name('document.queue');
        Route::get('/issued', [VisaOfficerController::class, 'issued'])->name('issued');
        Route::get('/rejected', [VisaOfficerController::class, 'rejected'])->name('rejected');
        Route::get('/notifications', [VisaOfficerController::class, 'notifications'])->name('notifications');
        Route::get('/reports', [VisaOfficerController::class, 'reports'])->name('reports');
        Route::post('/notifications/{notification}/mark-read', [VisaOfficerController::class, 'markNotificationRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [VisaOfficerController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');
        Route::get('/profile', [VisaOfficerController::class, 'profile'])->name('profile');
        Route::get('/applications/{visaApplication}', [VisaOfficerController::class, 'show'])->name('applications.show');
        Route::post('/applications/{visaApplication}/status', [VisaOfficerController::class, 'updateStatus'])->name('applications.status.update');
        Route::get('/applications/{visaApplication}/print', [VisaOfficerController::class, 'print'])->name('applications.print');
        Route::get('/applications/{visaApplication}/document/{field}/download', [VisaOfficerController::class, 'downloadDocument'])->name('applications.document.download');
    });

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN MANAGEMENT ROUTES (/admin/*)
    |--------------------------------------------------------------------------
    */
    // Booking Management
    Route::get('/admin/booking-management', fn () => redirect()->route('admin.bookings.index'))->name('admin.booking-management');
    Route::get('/admin/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('/admin/bookings/export', [AdminBookingController::class, 'export'])->name('admin.bookings.export');
    Route::get('/admin/bookings/{booking}/edit', [AdminBookingController::class, 'edit'])->name('admin.bookings.edit');
    Route::put('/admin/bookings/{booking}', [AdminBookingController::class, 'update'])->name('admin.bookings.update');
    Route::delete('/admin/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('admin.bookings.destroy');
    Route::get('/admin/bookings/{booking}/print', [AdminBookingController::class, 'print'])->name('admin.bookings.print');
    Route::get('/admin/bookings/{booking}', [AdminBookingController::class, 'show'])->name('admin.bookings.show');
    Route::post('/admin/bookings/{booking}/reserve', [AdminBookingController::class, 'reserve'])->name('admin.bookings.reserve');
    Route::post('/admin/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('admin.bookings.cancel');
    Route::get('/admin/bookings/{booking}/passport/{passenger}', [AdminBookingController::class, 'downloadPassportDocument'])->name('admin.bookings.passport.download');
    Route::get('/admin/bookings/{booking}/cnic/{passenger}', [AdminBookingController::class, 'downloadCnicDocument'])->name('admin.bookings.cnic.download');

    // Airline & Tickets Management
    Route::get('/admin/airline-ticket-management', [AdminTicketController::class, 'index'])->name('admin.airline-ticket-management');
    Route::post('/admin/airline-ticket-management', [AdminTicketController::class, 'store'])->name('admin.airline-ticket-management.store');

    Route::prefix('admin')->as('admin.')->group(function () {

        Route::resource('airlines', AdminAirlineController::class)
            ->except(['show']);

        Route::resource('airports', AdminAirportController::class)
            ->except(['show']);

        Route::resource(
            'packages',
            AdminPackageController::class
        );

        // Package Booking Voucher
        Route::get(
            '/package-bookings/{id}/voucher',
            [AdminPackageBookingController::class, 'voucher']
        )->name('package-bookings.voucher');

        // Package Bookings
        Route::resource(
            'package-bookings',
            AdminPackageBookingController::class
        )->only(['index', 'show', 'update']);

        // Voucher Management
        Route::get(
            '/voucher-management',
            [AdminVoucherSettingController::class, 'index']
        )->name('voucher-management');

        Route::post(
            '/voucher-management/logo',
            [AdminVoucherSettingController::class, 'updateLogo']
        )->name('voucher-management.logo');

        // Admin: Hotel Voucher routes (prepare -> passengers -> generate)
        Route::post(
            '/hotel-vouchers/{booking}/prepare',
            [HotelVoucherController::class, 'prepare']
        )->name('hotel-vouchers.prepare');

        Route::post(
            '/hotel-vouchers/{booking}/passengers',
            [HotelVoucherController::class, 'savePassengers']
        )->name('hotel-vouchers.passengers');

        Route::get(
            '/hotel-vouchers/generate/{booking}',
            [HotelVoucherController::class, 'generate']
        )->name('hotel-vouchers.generate');
    });
    //  Route::get(
    //     '/voucher-management',
    //     [\App\Http\Controllers\AdminVoucherSettingController::class, 'index']
    // )->name('voucher-management.index');

    // Route::post(
    //     '/voucher-management/logo',
    //     [\App\Http\Controllers\AdminVoucherSettingController::class, 'updateLogo']
    // )->name('voucher-management.logo');
    Route::get('/admin/airline-flights', [AdminAirlineFlightController::class, 'index'])->name('admin.airline-flights.index');
    Route::get('/admin/airline-flights/create', [AdminAirlineFlightController::class, 'create'])->name('admin.airline-flights.create');
    Route::post('/admin/airline-flights', [AdminAirlineFlightController::class, 'store'])->name('admin.airline-flights.store');
    Route::get('/admin/airline-flights/{ticket}', [AdminAirlineFlightController::class, 'show'])->name('admin.airline-flights.show');
    Route::get('/admin/airline-flights/{ticket}/edit', [AdminAirlineFlightController::class, 'edit'])->name('admin.airline-flights.edit');
    Route::put('/admin/airline-flights/{ticket}', [AdminAirlineFlightController::class, 'update'])->name('admin.airline-flights.update');
    Route::put('/admin/airline-flights/{ticket}/status', [AdminAirlineFlightController::class, 'updateStatus'])->name('admin.airline-flights.status.update');
    Route::delete('/admin/airline-flights/{ticket}', [AdminAirlineFlightController::class, 'destroy'])->name('admin.airline-flights.destroy');

    Route::post('/admin/airline-bookings/{flightBooking}/cancel', [AdminFlightBookingController::class, 'cancel'])->name('admin.airline-bookings.cancel');
    Route::get('/admin/airline-bookings', [AdminFlightBookingController::class, 'index'])->name('admin.airline-bookings.index');
    Route::get('/admin/airline-bookings/{flightBooking}', [AdminFlightBookingController::class, 'show'])->name('admin.airline-bookings.show');
    Route::get('/admin/airline-bookings/{flightBooking}/confirm', [AdminFlightBookingController::class, 'approve'])->name('admin.airline-bookings.confirm');
    Route::put('/admin/airline-bookings/{flightBooking}/status', [AdminFlightBookingController::class, 'updateStatus'])->name('admin.airline-bookings.status.update');
    Route::delete('/admin/airline-bookings/{flightBooking}', [AdminFlightBookingController::class, 'destroy'])->name('admin.airline-bookings.destroy');

    // Hotel Management
    Route::get('/admin/hotel-management', [AdminHotelManagementController::class, 'index'])->name('admin.hotel-management');
    Route::get('/admin/hotels', [AdminHotelController::class, 'index'])->name('admin.hotels.index');
    Route::get('/admin/hotels/create', [AdminHotelController::class, 'create'])->name('admin.hotels.create');
    Route::post('/admin/hotels', [AdminHotelController::class, 'store'])->name('admin.hotels.store');
    Route::get('/admin/hotels/{hotel}/edit', [AdminHotelController::class, 'edit'])->name('admin.hotels.edit');
    Route::put('/admin/hotels/{hotel}/about', [AdminHotelController::class, 'updateAbout'])->name('admin.hotels.update-about');
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

    // Agent Management
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

    Route::get('/admin/customer-management', [AdminCustomerController::class, 'index'])->name('admin.customer-management');
    Route::get('/admin/customers/{customer}', [AdminCustomerController::class, 'show'])->name('admin.customers.show');
    Route::get('/admin/customers/{customer}/edit', [AdminCustomerController::class, 'edit'])->name('admin.customers.edit');
    Route::put('/admin/customers/{customer}', [AdminCustomerController::class, 'update'])->name('admin.customers.update');
    Route::delete('/admin/customers/{customer}', [AdminCustomerController::class, 'destroy'])->name('admin.customers.destroy');

    // Visa Management (Super Admin)
    Route::get('/admin/visa-management', [AdminVisaApplicationController::class, 'index'])->name('admin.visa-management');
    Route::post('/admin/visa-applications/{visaApplication}/status', [AdminVisaApplicationController::class, 'updateStatus'])->name('admin.visa-applications.status.update');
    Route::post('/admin/visa-applications/{visaApplication}/assign-officer', [AdminVisaApplicationController::class, 'assignOfficer'])->name('admin.visa-applications.assign-officer');
    Route::get('/admin/visa-applications/{visaApplication}/print', [AdminVisaApplicationController::class, 'print'])->name('admin.visa-applications.print');
    Route::get('/admin/visa-applications/{visaApplication}/document/{field}/download', [AdminVisaApplicationController::class, 'downloadDocument'])->name('admin.visa-applications.document.download');
    Route::delete('/admin/visa-applications/{visaApplication}/document/{field}', [AdminVisaApplicationController::class, 'deleteDocument'])->name('admin.visa-applications.document.destroy');
    Route::post('/admin/visa-applications/{visaApplication}/document/{field}/replace', [AdminVisaApplicationController::class, 'replaceDocument'])->name('admin.visa-applications.document.replace');
    Route::get('/admin/visa-reports', [AdminVisaApplicationController::class, 'reports'])->name('admin.visa-reports');
    Route::get('/admin/visa-reports/export/pdf', [AdminVisaApplicationController::class, 'exportPdf'])->name('admin.visa-reports.export.pdf');
    Route::get('/admin/visa-reports/export/excel', [AdminVisaApplicationController::class, 'exportExcel'])->name('admin.visa-reports.export.excel');
    Route::resource('/admin/visa-applications', AdminVisaApplicationController::class, ['as' => 'admin'])->except(['index']);
    Route::resource('/admin/visa-types', AdminVisaTypeController::class, ['as' => 'admin']);

    // Dynamic Pages Rendering
    foreach ($adminPages as $slug => $title) {
        if (in_array($slug, [
            'agent-management',
            'hotel-management',
            'booking-management',
            'airline-ticket-management',
            'visa-management',
            'customer-management',
            'voucher-management',
        ], true)) {
            continue;
        }
        Route::view("/admin/{$slug}", "admin.{$slug}", ['pageTitle' => $title])
            ->name("admin.{$slug}");
    }
});
Route::get('/custom-package', function () {
    return view('custom.package');
})->name('custom.package');
