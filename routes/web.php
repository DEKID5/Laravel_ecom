<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect login route to Signin page
Route::get('/login', fn () => redirect()->route('Signin'))->name('login');

// Market route for all users
Route::get('/market', [ProductController::class, 'market'])->name('market.index');

// Product search & details
Route::get('/product/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');

// Authentication Routes
Route::get('/', [UserController::class, 'index'])->name('Signin');
Route::get('/signup', [UserController::class, 'sellerSignup'])->name('Signup');
Route::post('/signup/buyer', [UserController::class, 'signup'])->name('signup.buyer');
Route::post('/signup/seller', [UserController::class, 'signup'])->name('signup.seller');
Route::post('/signin', [UserController::class, 'signinUpdate'])->name('signin.post');
Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [UserController::class, 'sendResetLink'])->name('password.email');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Dashboard Redirect
    Route::get('/dashboard', function () {
        return Auth::user()->store_name
            ? redirect()->route('store.dashboard')
            : redirect()->route('market.index');
    })->name('dashboard');

    // Seller Dashboard
    Route::get('/store/dashboard', [ProductController::class, 'storeDashboard'])->name('store.dashboard');
    Route::get('/overview', [ProductController::class, 'overview'])->name('overview');
    Route::get('/store/dashboard', [DashboardController::class, 'index'])->name('store.dashboard');

    // Seller Product Management
    Route::get('/store/products', [ProductController::class, 'productPage'])->name('productpage');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/store-product', [ProductController::class, 'store'])->name('storeProduct');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('editProduct');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('updateProduct');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('deleteProduct');

    // Cart & Checkout Routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
        Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');

        // Checkout Routes
        Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.place');
    });

    // Order Confirmation Route
    Route::get('/order/confirmation/{orderId}', [CartController::class, 'showOrderConfirmation'])->name('order.confirmation');

    // Orders (Pending & Completed Only)
    Route::prefix('orders')->group(function () {
        Route::get('/pending', [CartController::class, 'showPendingOrders'])->name('orders.pending');
        Route::get('/completed', [CartController::class, 'showCompletedOrders'])->name('orders.completed');

        // Mark order as completed (POST)
        Route::post('/complete/{order}', [CartController::class, 'markOrderComplete'])->name('orders.complete');
        Route::post('/orders/request-user-confirm/{order}', [CartController::class, 'requestUserConfirm'])->name('orders.requestUserConfirm');
        Route::get('/orders/user-confirm/{order}', [CartController::class, 'userConfirmOrder'])->name('orders.userConfirm');
        Route::post('/{order}/confirm', [CartController::class, 'confirm'])->name('orders.confirm');
        Route::get('/orders/{order}', [CartController::class, 'showOrder'])->name('orders.show');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Messages & Customers
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
