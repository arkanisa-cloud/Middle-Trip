<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ExpeditionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/katalog', '/ekspedisi');
Route::get('/ekspedisi', [ExpeditionController::class, 'index'])->name('ekspedisi.index');
Route::get('/ekspedisi/{slug}', [ExpeditionController::class, 'show'])->name('ekspedisi.show');
Route::get('/api/mountains/quick-search', [ExpeditionController::class, 'searchApi'])->name('api.mountains.search');
Route::view('/kontak', 'customer.contact')->name('contact');

// Booking & Checkout Routes
Route::post('/bookings', [BookingController::class, 'store'])->middleware('auth')->name('bookings.store');
Route::get('/checkout/{booking_code}', [CheckoutController::class, 'step1'])->name('checkout.step1');
Route::post('/checkout/{booking_code}/pay-dp', [CheckoutController::class, 'payDp'])->name('checkout.pay_dp');
Route::post('/checkout/{booking_code}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
Route::get('/checkout/{booking_code}/status', [CheckoutController::class, 'status'])->name('checkout.status');
Route::post('/checkout/{booking_code}/settle', [CheckoutController::class, 'settle'])->name('checkout.settle');
Route::get('/checkout/{booking_code}/private', [CheckoutController::class, 'private'])->name('checkout.private');
Route::post('/checkout/{booking_code}/pay-private', [CheckoutController::class, 'payPrivate'])->name('checkout.pay_private');
Route::get('/checkout/{booking_code}/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/bookings/{booking_code}/print', [\App\Http\Controllers\BookingInvoiceController::class, 'show'])->name('bookings.print');
Route::post('/midtrans/notification', [\App\Http\Controllers\MidtransNotificationController::class, 'handle'])->name('midtrans.notification');

Route::get('/dashboard', function () {
    if (auth()->user()?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpeditionScheduleController;
use App\Http\Controllers\Admin\MeetingPointController;
use App\Http\Controllers\Admin\MountainController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('mountains', MountainController::class);
    Route::resource('meeting-points', MeetingPointController::class)->except(['create', 'show', 'edit']);
    Route::resource('addons', AddonController::class)->except(['create', 'show', 'edit']);

    // Expeditions
    Route::resource('expeditions', ExpeditionScheduleController::class);
    Route::post('expeditions/{expedition}/price-lock', [ExpeditionScheduleController::class, 'triggerPriceLock'])->name('expeditions.price_lock');

    // Bookings & SIMAKSI
    Route::get('bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{booking}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.update_status');

    // Admin Profile & Account Settings
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__.'/auth.php';
