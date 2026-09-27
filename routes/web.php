<?php

use App\Http\Controllers\BookingController;
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
Route::get('/checkout/{booking_code}', function (string $booking_code) {
    return "Checkout {$booking_code}";
})->name('checkout.step1');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
