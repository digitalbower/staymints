<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SeoManagementController;
use App\Http\Controllers\User\HomeController as UserHomeController;
use App\Http\Controllers\User\NewsletterController;
use App\Http\Controllers\User\PackageController as UserPackageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserHomeController::class, 'index'])->name('home.index');
Route::get('/about', [UserHomeController::class, 'about'])->name('home.about');
Route::get('/contact', [UserHomeController::class, 'contact'])->name('home.contact');
Route::get('/login', [UserHomeController::class, 'login'])->name('home.login');
Route::get('/preview', [UserHomeController::class, 'preview'])->name('home.preview');
Route::get('/packages', [UserHomeController::class, 'packages'])->name('home.packages');
Route::post('/subscribe', [NewsletterController::class, 'subscribe'])->name('subscribe');
Route::prefix('user')->name('user.')->group(function () {
    Route::prefix('package')->name('package.')->group(function () {
        Route::get('/search', [UserHomeController::class, 'packageSearch'])->name('search');
        Route::get('/filter', [UserHomeController::class, 'filterSearch'])->name('filter');
        Route::get('/show/{id}', [UserPackageController::class, 'packageDetail'])->name('show');
        Route::post('/booking', [UserPackageController::class, 'booking'])->name('booking');
        Route::post('/booking-confirmation', [UserPackageController::class, 'bookingConfirmation'])->name('booking_confirmation');
        Route::post('/review', [UserPackageController::class, 'review'])->name('review');
        Route::post('/wishlist/{packageId}', [UserPackageController::class, 'wishlist'])->name('wishlist');

    });

});
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'adminLogin'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Admin Panel Routes (Requires Admin Middleware)
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('index');
        Route::resource('users', AdminUserController::class);
        Route::resource('footers', FooterController::class);
        Route::resource('packages', PackageController::class);
        Route::post('/packages/change-status', [PackageController::class, 'changeStatus'])->name('packages.status');
        Route::resource('seo', SeoManagementController::class);
        Route::resource('categories', CategoryController::class);
        Route::post('/categories/change-status', [CategoryController::class, 'changeStatus'])->name('categories.status');
    });
});