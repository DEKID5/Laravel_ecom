<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Authentication Routes
Route::get('/', [UserController::class, 'index'])->name('Signin');
Route::get('/signup', [UserController::class, 'sellerSignup'])->name('Signup');
Route::post('/signup/buyer', [UserController::class, 'signup'])->name('signup.buyer');
Route::post('/signup/seller', [UserController::class, 'signup'])->name('signup.seller');
Route::post('/signin', [UserController::class, 'signinUpdate'])->name('signin.post');

// Password Reset Routes
Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [UserController::class, 'sendResetLink'])->name('password.email');

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {
    
    // Redirect user to respective dashboard based on role
    Route::get('/dashboard', function () {
        return Auth::user()->store_name 
            ? redirect()->route('store.dashboard') 
            : redirect()->route('market');
    })->name('dashboard');

    // Buyer Market Page
    Route::get('/market', [UserController::class, 'customerMarket'])->name('market');

    // Seller Dashboard Page
    Route::get('/store/dashboard', [UserController::class, 'storeDashboard'])->name('store.dashboard');

    // Logout Route
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});


