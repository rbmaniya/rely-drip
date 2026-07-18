<?php

use App\Http\Controllers\Storefront\Account\AddressController;
use App\Http\Controllers\Storefront\Account\DashboardController;
use App\Http\Controllers\Storefront\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Storefront\Account\ProfileController;
use App\Http\Controllers\Storefront\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Storefront\Auth\NewPasswordController;
use App\Http\Controllers\Storefront\Auth\PasswordResetLinkController;
use App\Http\Controllers\Storefront\Auth\RegisteredUserController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CategoryController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\ContactController;
use App\Http\Controllers\Storefront\CustomOrderController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\OrderController;
use App\Http\Controllers\Storefront\PageController;
use App\Http\Controllers\Storefront\ProductController;
use App\Http\Controllers\Storefront\ReviewController;
use App\Http\Controllers\Storefront\WishlistController;
use Illuminate\Support\Facades\Route;

Route::as('storefront.')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('about', [PageController::class, 'about'])->name('about');
    Route::get('culture', [PageController::class, 'culture'])->name('culture');
    Route::get('lookbook', [PageController::class, 'lookbook'])->name('lookbook');

    Route::get('custom-order', [CustomOrderController::class, 'index'])->name('custom-order.index');
    Route::post('custom-order', [CustomOrderController::class, 'store'])->name('custom-order.store');

    Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');

    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{slug}', [ProductController::class, 'show'])->name('products.show');
    Route::post('products/{product}/reviews', [ReviewController::class, 'store'])
        ->middleware('auth:customer')
        ->name('reviews.store');

    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('cart/{variation}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{variation}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');

    Route::middleware('guest:customer')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth:customer')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
        Route::delete('wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

        Route::prefix('account')->as('account.')->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

            Route::get('addresses', [AddressController::class, 'index'])->name('addresses.index');
            Route::post('addresses', [AddressController::class, 'store'])->name('addresses.store');
            Route::put('addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
            Route::delete('addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
            Route::patch('addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');

            Route::get('orders', [AccountOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}', [AccountOrderController::class, 'show'])->name('orders.show');
            Route::get('orders/{order}/invoice', [AccountOrderController::class, 'invoice'])->name('orders.invoice');
        });
    });
});
