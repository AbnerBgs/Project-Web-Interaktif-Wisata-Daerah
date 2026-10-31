<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Visitor\HomeController;
use App\Http\Controllers\Visitor\DestinationController;
use App\Http\Controllers\Visitor\MapController;
use App\Http\Controllers\Visitor\EventController;
use App\Http\Controllers\Visitor\ReviewController;
use App\Http\Controllers\Visitor\ItineraryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminDestinationController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Language Switch ────────────────────────────────────────────────────
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// ── Auth Routes ────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Visitor Routes ─────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/peta', [MapController::class, 'index'])->name('map');
Route::get('/destinasi', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinasi/{slug}', [DestinationController::class, 'show'])->name('destinations.show');
Route::post('/destinasi/{id}/ulasan', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/event', [EventController::class, 'index'])->name('events.index');
Route::get('/event/{slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/itinerary', [ItineraryController::class, 'index'])->name('itinerary.index');
Route::post('/itinerary/generate', [ItineraryController::class, 'generate'])->name('itinerary.generate');

// ── Admin Routes ───────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Destinasi CRUD
    Route::resource('destinations', AdminDestinationController::class);

    // Kategori CRUD
    Route::resource('categories', AdminCategoryController::class);

    // Event CRUD
    Route::resource('events', AdminEventController::class);

    // Review Moderation
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{id}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{id}/reject',  [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{id}',        [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // User Management
    Route::resource('users', AdminUserController::class)->except(['create', 'store']);
});
