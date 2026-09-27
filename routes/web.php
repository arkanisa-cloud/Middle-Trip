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

// Booking & Checkout Routes
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/checkout/{booking_code}', [CheckoutController::class, 'step1'])->name('checkout.step1');
Route::post('/checkout/{booking_code}/pay-dp', [CheckoutController::class, 'payDp'])->name('checkout.pay_dp');
Route::get('/checkout/{booking_code}/status', [CheckoutController::class, 'status'])->name('checkout.status');
Route::post('/checkout/{booking_code}/settle', [CheckoutController::class, 'settle'])->name('checkout.settle');
Route::get('/checkout/{booking_code}/success', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
