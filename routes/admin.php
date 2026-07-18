<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomOrderRequestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->as('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])
            ->middleware('admin.ability:dashboard.view')
            ->name('dashboard');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Categories
        Route::get('categories', [CategoryController::class, 'index'])->middleware('admin.ability:categories.view')->name('categories.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->middleware('admin.ability:categories.create')->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->middleware('admin.ability:categories.create')->name('categories.store');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->middleware('admin.ability:categories.edit')->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->middleware('admin.ability:categories.edit')->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->middleware('admin.ability:categories.delete')->name('categories.destroy');

        // Products
        Route::get('products', [ProductController::class, 'index'])->middleware('admin.ability:products.view')->name('products.index');
        Route::get('products/create', [ProductController::class, 'create'])->middleware('admin.ability:products.create')->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->middleware('admin.ability:products.create')->name('products.store');
        Route::post('products/{product}/duplicate', [ProductController::class, 'duplicate'])->middleware('admin.ability:products.create')->name('products.duplicate');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->middleware('admin.ability:products.edit')->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->middleware('admin.ability:products.edit')->name('products.update');
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->middleware('admin.ability:products.edit')->name('products.toggle-status');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->middleware('admin.ability:products.delete')->name('products.destroy');

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->middleware('admin.ability:orders.view')->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->middleware('admin.ability:orders.view')->name('orders.show');
        Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->middleware('admin.ability:orders.view')->name('orders.invoice');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->middleware('admin.ability:orders.edit')->name('orders.update-status');

        // Customers
        Route::get('customers', [CustomerController::class, 'index'])->middleware('admin.ability:customers.view')->name('customers.index');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->middleware('admin.ability:customers.view')->name('customers.show');
        Route::patch('customers/{customer}/block', [CustomerController::class, 'block'])->middleware('admin.ability:customers.edit')->name('customers.block');
        Route::patch('customers/{customer}/unblock', [CustomerController::class, 'unblock'])->middleware('admin.ability:customers.edit')->name('customers.unblock');

        // Reviews
        Route::get('reviews', [ReviewController::class, 'index'])->middleware('admin.ability:reviews.view')->name('reviews.index');
        Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->middleware('admin.ability:reviews.edit')->name('reviews.approve');
        Route::patch('reviews/{review}/reject', [ReviewController::class, 'reject'])->middleware('admin.ability:reviews.edit')->name('reviews.reject');
        Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->middleware('admin.ability:reviews.delete')->name('reviews.destroy');

        // Contact messages
        Route::get('contact-messages', [ContactMessageController::class, 'index'])->middleware('admin.ability:contact_messages.view')->name('contact-messages.index');
        Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->middleware('admin.ability:contact_messages.delete')->name('contact-messages.destroy');

        // Custom order requests
        Route::get('custom-order-requests', [CustomOrderRequestController::class, 'index'])->middleware('admin.ability:custom_orders.view')->name('custom-order-requests.index');
        Route::delete('custom-order-requests/{customOrderRequest}', [CustomOrderRequestController::class, 'destroy'])->middleware('admin.ability:custom_orders.delete')->name('custom-order-requests.destroy');

        // Website settings
        Route::get('settings', [WebsiteSettingController::class, 'edit'])->middleware('admin.ability:website_settings.view')->name('settings.edit');
        Route::put('settings', [WebsiteSettingController::class, 'update'])->middleware('admin.ability:website_settings.edit')->name('settings.update');

        // Employees — owner-level management, not itself an assignable employee permission.
        Route::middleware('admin.ability:employees')->group(function () {
            Route::resource('employees', EmployeeController::class)->except(['show']);
            Route::patch('employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
        });
    });
});
