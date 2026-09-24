<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\AircraftPartController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SkydivingController;
use App\Http\Controllers\SkydiveBookingController;
use App\Http\Controllers\GliderController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\DispatchController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\StaffLogbookController;
use App\Http\Controllers\AircraftMaintenanceController;
use App\Http\Controllers\MaintenanceTypeController;
use App\Http\Controllers\MaintenanceScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\InvoiceController;


Route::get('/test-ai', function () {
    $response = Http::withToken(env('OPENAI_API_KEY'))
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => 'Rewrite this professionally: I want a job']
            ],
        ]);

    // return $response->json();
    return "This is a simulated AI response";
});
Route::post('/apply', [
    ApplicationController::class,
    'store',
])->name('application.store');

Route::get('/onboarding/completed', function () {
    return view('onboarding.completed');
})->name('onboarding.completed');

Route::get('/onboarding/{token}', [
    OnboardingController::class,
    'show',
])->name('onboarding.show');

Route::post('/onboarding/{token}', [
    OnboardingController::class,
    'store',
])->name('onboarding.store');

Route::get('/onboarding/{token}/details', [
    OnboardingController::class,
    'details',
])->name('onboarding.details');

Route::post('/onboarding/{token}/details', [
    OnboardingController::class,
    'saveDetails',
])->name('onboarding.details.store');

Route::get(
    '/aircraft/{id}/summary',
    [FlightController::class, 'summary']
)->name('aircraft.summary');


Route::post('/profile/image/update', [ProfileController::class, 'updateProfileImage'])
    ->name('profile.image.update');

Route::middleware(['auth'])->group(function () {

    Route::get('/aircraft-maintenance', [AircraftMaintenanceController::class, 'dashboard'])
        ->name('aircraftmaintenance.dashboard');
    Route::get('/aircraft-maintenance/{aircraft}', [AircraftMaintenanceController::class, 'showAircraft'])
        ->name('aircraftmaintenance.show');
    Route::post(
        '/maintenance/perform',
        [AircraftMaintenanceController::class, 'perform']
    )->name('maintenance.perform');
    Route::get(
        '/maintenance/{schedule}/details',
        [AircraftMaintenanceController::class, 'maintenanceDetails']
    )->name('maintenance.details');
    // Maintenance Types
    Route::resource('maintenance-types', MaintenanceTypeController::class);


    // Maintenance History (placeholder for now)
    Route::get('/maintenance-history', [AircraftMaintenanceController::class, 'allHistory'])
        ->name('maintenance-history.index');

    Route::get('/aircraft/{aircraft}/history', [AircraftMaintenanceController::class, 'history'])
        ->name('aircraft.history');

});
Route::middleware(['auth'])->group(function () {

    Route::get('/maintenance-schedules', [MaintenanceScheduleController::class, 'index'])
        ->name('maintenance-schedules.index');

    Route::post('/maintenance-schedules', [MaintenanceScheduleController::class, 'store'])
        ->name('maintenance-schedules.store');

    Route::put('/maintenance-schedules/{schedule}', [MaintenanceScheduleController::class, 'update'])
        ->name('maintenance-schedules.update');

    Route::delete('/maintenance-schedules/{schedule}', [MaintenanceScheduleController::class, 'destroy'])
        ->name('maintenance-schedules.destroy');

});
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::post('/users/{user}/approve', [
            UserController::class,
            'approve'
        ])->name('users.approve');

        Route::post('/users/{user}/disapprove', [
            UserController::class,
            'disapprove'
        ])->name('users.disapprove');


        Route::get('/applications', [
            ApplicationController::class,
            'index',
        ])->name('applications.index');

        Route::get('/applications/{application}', [
            ApplicationController::class,
            'show',
        ])->name('admin.applications.show');

        Route::post('/applications/{application}/approve', [
            ApplicationController::class,
            'approve',
        ])->name('admin.applications.approve');

        Route::post('/applications/{application}/reject', [
            ApplicationController::class,
            'reject',
        ])->name('admin.applications.reject');
    });

Route::get('/users/{user}', [UserController::class, 'show'])
    ->name('admin.users.show');


Route::get('/users', [
    UserController::class,
    'list'
])->name('users.list');
Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
    ->name('invoices.show');

Route::post('/users/{id}/deactivate', [UserController::class, 'deactivate'])
    ->name('users.deactivate');

Route::post('/users/{id}/activate', [UserController::class, 'activate'])
    ->name('users.activate');

Route::middleware(['auth', 'role:student'])->group(function () {

    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');

    Route::post('/student/assign-instructor', [StudentController::class, 'assignInstructor']);

    Route::post('/student/update-progress', [StudentController::class, 'updateProgress']);

});

Route::post('/student/request-instructor', [StudentController::class, 'requestInstructor'])
    ->name('student.request.instructor');
Route::get('/student/calendar-events', [StudentController::class, 'calendar']);
Route::get('/student/aircraft-resources', [StudentController::class, 'aircraftResources']);
Route::post('/student/schedule/{id}/reschedule', [StudentController::class, 'reschedule']);

Route::get('/instructor/request/{id}/accept', [InstructorController::class, 'acceptRequest1'])
    ->name('instructor.request.accept');

Route::get('/instructor/request/{id}/reject', [InstructorController::class, 'rejectRequest1'])
    ->name('instructor.request.reject');
Route::post('/instructor/request/accept/{id}', [InstructorController::class, 'acceptRequest']);
Route::post('/instructor/request/reject/{id}', [InstructorController::class, 'rejectRequest']);

Route::post('/notifications/read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return response()->json(['success' => true]);
});
Route::middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'dashboard'])
        ->name('instructor.dashboard');
});
Route::get('/instructor/logbook/{id}', [InstructorController::class, 'showLogbook']);
Route::post('/logbook/approve/{id}', [LogbookController::class, 'approve']);
Route::post('/notifications/read-one/{id}', function ($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    return response()->json(['success' => true]);
});
Route::resource('instructors', InstructorController::class)->middleware('auth');
Route::get('/instructors', [InstructorController::class, 'index'])->name('instructors.index')->middleware('auth');
Route::get('/instructors/create', [InstructorController::class, 'create'])->name('instructors.create')->middleware('auth');


Route::get('/instructors/{id}', [InstructorController::class, 'show'])->name('instructors.show');

Route::delete('/schedules/{id}/cancel', [StudentController::class, 'cancel'])
    ->name('schedules.cancel');

Route::middleware(['auth'])->group(function () {

    Route::get('/logbooks', [LogbookController::class, 'index'])
        ->name('logbooks.index');
    Route::get('/logbooks/view', [LogbookController::class, 'index'])
        ->name('logbooks.view');
    Route::post(
        '/flights/schedule/{schedule}/block-time/request',
        [LogbookController::class, 'requestBlockTime']
    )
        ->name('flights.schedule.block-time.request');

    Route::get('/logbooks/export/pdf', [LogbookController::class, 'exportPdf'])
        ->name('logbooks.export.pdf');

    Route::get('/logbooks/{id}', [LogbookController::class, 'show'])->name('logbooks.show');

    Route::get('/logbooks/{logbook}/edit', [LogbookController::class, 'edit']);

    Route::put('/logbooks/{logbook}', [LogbookController::class, 'update']);

    Route::post('/logbook/store', [LogbookController::class, 'store'])
        ->name('logbook.store');
    Route::get('/logbook/create', [LogbookController::class, 'create'])
        ->name('logbook.create');

    Route::post('/logbook/{id}/approve', [LogbookController::class, 'approve'])
        ->name('logbook.approve');

    Route::delete('/logbooks/{id}', [LogbookController::class, 'destroy'])
        ->name('logbooks.destroy');

    Route::post('/logbooks/{id}/restore', [LogbookController::class, 'restore'])
        ->name('logbooks.restore');
});
Route::get('/student/{id}/pdf', [StudentController::class, 'downloadPdf'])
    ->name('student.profile.pdf');

Route::get('/admin/tach-history', [AdminController::class, 'tachHistory'])
    ->name('admin.tach-history');

Route::get('/admin/block-time-requests', [AdminController::class, 'blockTimeRequests'])
    ->name('admin.block-time-requests.index');

Route::patch(
    '/admin/block-time-requests/{blockTimeRequest}/approve',
    [AdminController::class, 'approveBlockTimeRequest']
)->name('admin.block-time-requests.approve');

Route::middleware(['auth'])->group(function () {
    Route::get('/staff-logbook/modal-data', [StaffLogbookController::class, 'modalData'])
        ->name('staff.logbook.modal.data');
});


// Show "verify  " page
Route::get('/email/verify', EmailVerificationPromptController::class)
    ->middleware('auth')
    ->name('verification.notice');

// Handle email verification link
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->name('verification.verify')
    ->middleware(['signed']);

// Resend verification link
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/calendar/schedules', [FlightController::class, 'calendarEvents'])
    ->name('calendar.events');
Route::get('/calendar/aircraft', [FlightController::class, 'aircraftResources'])
    ->name('calendar.aircraft');
Route::post('/calendar/create', [FlightController::class, 'createSchedule'])
    ->name('calendar.create');
Route::post('/calendar/update/{id}', [FlightController::class, 'updateFromCalendar'])
    ->name('calendar.update');
Route::get('/calendar', [FlightController::class, 'calendar'])
    ->name('calendar');

Route::post(
    '/dispatch/store',
    [DispatchController::class, 'store']
)->name('dispatch.store');


Route::post(
    '/dispatch/un-dispatch',
    [DispatchController::class, 'unDispatch']
)->name('dispatch.unDispatch');


Route::get('/dispatches', [DispatchController::class, 'index'])
    ->name('dispatches.index');

Route::prefix('dispatch')->name('dispatch.')->group(function () {

    Route::get('/{id}', [DispatchController::class, 'show'])
        ->name('show');

    Route::patch('/{id}/complete', [DispatchController::class, 'complete'])
        ->name('complete');

    Route::patch('/{id}/cancel', [DispatchController::class, 'cancel'])
        ->name('cancel');

    Route::delete('/{id}', [DispatchController::class, 'destroy'])
        ->name('destroy');
});

Route::get('/flightrental', function () {
    return redirect()->route('flights.index');
});

Route::get('/flights/calendar', [FlightController::class, 'calendar'])
    ->name('flights.calendar');

Route::get('/flights/{flight}/schedule/{schedule}', [FlightController::class, 'show'])
    ->name('flights.schedule.show');

Route::get('/flights/schedules', [FlightController::class, 'schedules'])
    ->name('flight.schedules');

Route::get('/flights/schedule/{id}/edit', [FlightController::class, 'editSchedule'])
    ->name('flights.schedule.edit');

Route::put('/flights/schedule/{id}/update', [FlightController::class, 'updateSchedule'])
    ->name('flights.schedule.update');

Route::delete('/flights/schedule/{id}', [FlightController::class, 'destroySchedule'])
    ->name('flights.schedule.destroy');

Route::get('/flights/available', [FlightController::class, 'available'])
    ->middleware('role:admin,student,instructor')->name('flights.available');

Route::get('/flights/schedule', [FlightController::class, 'schedule'])
    ->name('flights.schedule');

Route::get('/flights/{flight}/schedules', [FlightController::class, 'schedules'])
    ->name('flights.schedules');

Route::view('/flight-school', 'flights.flight-school')->name('flight.school');

Route::post('/flight-apply', [App\Http\Controllers\FlightController::class, 'apply'])
    ->name('flight.apply');

Route::post(
    '/flights/schedule/store',
    [FlightController::class, 'storeSchedule']
)->name('flights.schedule.store');

Route::resource('flights', FlightController::class);


Route::get('/', [DashboardController::class, 'index']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index']);
});


Route::middleware(['auth', 'role:user|instructor|student'])->group(function () {
    Route::post('/book-flight/{flight}', [BookingController::class, 'store'])
        ->name('book.flight');
});

Route::get('/booking/{booking}/download', [BookingController::class, 'downloadTicket'])
    ->middleware('auth')
    ->name('booking.download');


Route::middleware(['auth'])->group(function () {

    Route::get('/admin/booked-flights', [BookingController::class, 'adminIndex'])
        ->name('admin.bookings');

    Route::delete('/admin/bookings/{id}', [BookingController::class, 'destroy'])
        ->name('admin.booking.cancel');

    Route::get('/admin/bookings/{booking}', [BookingController::class, 'show'])
        ->name('admin.bookings.show');

});



Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.cancel');


Route::get('/maintenance', function () {
    return redirect()->route('maintenances.index');
});
Route::get('/maintenance/maintenance', [MaintenanceController::class, 'maintenance_show'])
    ->name('maintenances.maintenance_show');

// Admin maintenance index (list all records)
Route::get('/admin/maintenances', [MaintenanceController::class, 'maintenanceIndex'])
    ->name('maintenances.maintenanceIndex');


Route::resource('maintenances', MaintenanceController::class);

// Custom route first
Route::get('aircraftparts/parts-sale', [AircraftPartController::class, 'partsSale'])
    ->name('aircraft_parts.partsSale');

Route::get('/aircraft-parts', [AircraftPartController::class, 'list'])->name('aircraft_parts.list');


// Then the resource route
Route::resource('aircraftparts', AircraftPartController::class)
    ->names('aircraft_parts')
    ->parameters(['aircraftparts' => 'aircraftPart']);


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');


Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/orders/{order}/invoice', [App\Http\Controllers\OrderController::class, 'downloadInvoice'])
    ->name('orders.invoice')
    ->middleware('auth');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');


Route::resource('skydiving', SkydivingController::class);

Route::post('/skydiving/{id}/book', [SkydiveBookingController::class, 'book'])
    ->name('skydiving.book')
    ->middleware('auth'); // optional: force login

Route::resource('gliders', GliderController::class);


