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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// User management for admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [App\Http\Controllers\UserController::class, 'list'])->name('users.list');
    Route::post('/users/{user}/approve', [App\Http\Controllers\UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/disapprove', [App\Http\Controllers\UserController::class, 'disapprove'])->name('users.disapprove');
});


// Show "verify email" page
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

Route::get('/flightrental', function () {
    return redirect()->route('flights.index');
});

Route::get('/flights/calendar', [FlightController::class, 'calendar'])
    ->name('flights.calendar');

Route::get('/calendar', [FlightController::class,'calendar'])
    ->name('calendar');

// Route::get('/calendar/aircraft', [FlightController::class,'aircraftResources']);
// Route::get('/calendar/schedules', [FlightController::class,'calendarEvents']);
Route::get('/calendar/aircraft', [FlightController::class,'aircraftResources'])->name('calendar.aircraft');
Route::get('/calendar/schedules', [FlightController::class,'calendarEvents'])->name('calendar.events');


Route::post('/calendar/update/{id}', [FlightController::class,'updateFromCalendar']);


Route::get('/flights/schedules', [FlightController::class, 'schedules'])
    ->name('flights.schedules');

Route::get('/flights/schedule/{id}/edit', [FlightController::class, 'editSchedule'])
    ->name('flights.schedule.edit');

Route::post('/flights/schedule/{id}/update', [FlightController::class, 'updateSchedule'])
    ->name('flights.schedule.update');

Route::post('/flights/schedule/{id}/cancel', [FlightController::class, 'cancelSchedule'])
    ->name('flights.schedule.cancel');

Route::get('/flights/available', [FlightController::class, 'available'])
    ->name('flights.available');

Route::get('/flights/schedule', [FlightController::class, 'schedule'])
    ->name('flights.schedule');

Route::post('/flights/schedule/store', 
    [FlightController::class, 'storeSchedule']
)->name('flights.schedule.store');

Route::resource('flights', FlightController::class);


Route::get('/', [DashboardController::class, 'index']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

// Route::middleware(['auth', 'role:instructor'])->group(function () {
//     Route::get('/instructor/dashboard', [InstructorController::class, 'index']);
// });

Route::middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'dashboard'])
        ->name('instructor.dashboard');
});


Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index']);
});


Route::middleware(['auth', 'role:user|instructor'])->group(function () {
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

Route::resource('instructors', InstructorController::class)->middleware('auth');
Route::get('/instructors', [InstructorController::class, 'index'])->name('instructors.index')->middleware('auth');
Route::get('/instructors/create', [InstructorController::class, 'create'])->name('instructors.create')->middleware('auth');


Route::get('/instructors/{id}', [InstructorController::class, 'show'])->name('instructors.show');



Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');


Route::resource('skydiving', SkydivingController::class);

Route::post('/skydiving/{id}/book', [SkydiveBookingController::class, 'book'])
    ->name('skydiving.book')
    ->middleware('auth'); // optional: force login

Route::resource('gliders', GliderController::class);


