<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminVenueController;
use App\Http\Controllers\Admin\AdminPromoController;
use App\Http\Controllers\Admin\AdminUserController;

// ===== PUBLIK =====
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/venues/{id}', [VenueController::class, 'show'])->name('venue.show');
Route::get('/promos', [PromoController::class, 'index'])->name('promos');

Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== CUSTOMER =====
Route::middleware('auth')->group(function () {
    Route::get('/history', [BookingController::class, 'history'])->name('history');
    Route::get('/history/{id}', [BookingController::class, 'detail'])->name('history.detail');
    Route::get('/booking/{venue_id}', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
    Route::get('/booking/{id}/success', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/ratings', [VenueController::class, 'storeRating'])->name('rating.store');
});

// ===== ADMIN =====
Route::middleware(['auth', 'role'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');

    Route::resource('/venues', AdminVenueController::class);
    Route::resource('/promos', AdminPromoController::class);
    Route::resource('/users', AdminUserController::class);
});