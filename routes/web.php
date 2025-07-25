<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SeoManagementController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\Auth\GoogleController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\User\HomeController as UserHomeController;
use App\Http\Controllers\User\NewsletterController;
use App\Http\Controllers\User\PackageController as UserPackageController;
use Illuminate\Support\Facades\Route;

Route::name('home.')->group(function () {
    Route::get('/', [UserHomeController::class, 'index'])->name('index');
    Route::get('/about', [UserHomeController::class, 'about'])->name('about');
    Route::get('/contact', [ContactController::class, 'contact'])->name('contact');
    Route::get('/privacy', [ContactController::class, 'privacyPolicy'])->name('privacy');
    Route::get('/terms', [ContactController::class, 'termsAndCondition'])->name('terms');
    Route::post('/contact-us', [ContactController::class, 'contactSubmit'])->name('contact_us');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/preview', [AuthController::class, 'preview'])->name('preview');
    Route::post('/preview-submit', [AuthController::class, 'previewSubmit'])->name('preview.submit');
});
Route::post('/subscribe', [NewsletterController::class, 'subscribe'])->name('subscribe');
Route::post('/register/generate-otp', [AuthController::class, 'generateOtp'])->name('generate.otp');
Route::post('/register/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/login/generate-otp', [AuthController::class, 'loginGenerateOtp'])->name('login.generate.otp');
Route::post('/login/verify-otp', [AuthController::class, 'verifyLoginOtp'])->name('login.verify.otp');
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.redirect.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::name('user.')->group(function () {
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [UserPackageController::class, 'packages'])->name('index');
        Route::get('/search', [UserPackageController::class, 'packageSearch'])->name('search');
        Route::get('/filter', [UserPackageController::class, 'filterSearch'])->name('filter');
        Route::post('/booking', [UserPackageController::class, 'booking'])->name('booking');
        Route::post('/booking-confirmation', [UserPackageController::class, 'bookingConfirmation'])->name('booking_confirmation');
        Route::post('/review', [UserPackageController::class, 'review'])->name('review');
        Route::post('/get-quote', [UserPackageController::class, 'getQuote'])->name('get_quote');
    });
    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/profile',[ProfileController::class,'profile'])->name('profile');
        Route::post('/packages/wishlist/{packageId}', [UserPackageController::class, 'wishlist'])->name('packages.wishlist');
    });
    Route::get('/{slug}', [UserPackageController::class, 'packageDetail'])->name('packages.show');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'adminLogin'])->name('login.post');
    // Admin Panel Routes (Requires Admin Middleware)
    Route::middleware(['admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [HomeController::class, 'index'])->name('index');
        Route::resource('users', AdminUserController::class);
        Route::resource('footers', FooterController::class);
        Route::resource('packages', PackageController::class);
        Route::post('/packages/change-status', [PackageController::class, 'changeStatus'])->name('packages.status');
        Route::resource('seo', SeoManagementController::class);
        Route::resource('categories', CategoryController::class);
        Route::post('/categories/change-status', [CategoryController::class, 'changeStatus'])->name('categories.status');
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('delete-review/{id}', [ReviewController::class, 'deleteReview'])->name('reviews.delete');
        Route::post('admin-reply', [ReviewController::class, 'adminReply'])->name('reviews.reply');
    });
});