<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DestinationApiController;
use App\Http\Controllers\Api\MapApiController;

/*
|--------------------------------------------------------------------------
| API Routes — digunakan oleh Leaflet map & AJAX requests frontend
|--------------------------------------------------------------------------
*/

// Semua data destinasi untuk inisialisasi marker di peta (GeoJSON format)
Route::get('/map/markers', [MapApiController::class, 'markers'])->name('api.map.markers');

// Destinasi terdekat berdasarkan koordinat pengunjung (Geolocation API)
Route::get('/destinations/nearby', [DestinationApiController::class, 'nearby'])->name('api.destinations.nearby');

// Filter destinasi (tanpa reload halaman — AJAX)
Route::get('/destinations/filter', [DestinationApiController::class, 'filter'])->name('api.destinations.filter');

// Search autocomplete
Route::get('/destinations/search', [DestinationApiController::class, 'search'])->name('api.destinations.search');
