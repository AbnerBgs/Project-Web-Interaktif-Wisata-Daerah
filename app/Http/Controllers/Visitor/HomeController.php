<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Category;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        // Destinasi populer untuk hero section
        $popularDestinations = Destination::active()
            ->where('is_popular', true)
            ->with('category')
            ->orderByDesc('view_count')
            ->limit(6)
            ->get();

        // Semua kategori aktif untuk filter cepat di beranda
        $categories = Category::where('is_active', true)->get();

        // Event & festival mendatang
        $upcomingEvents = Event::upcoming()
            ->orderBy('start_date')
            ->limit(4)
            ->get();

        // Statistik untuk banner
        $stats = [
            'destinations' => Destination::active()->count(),
            'categories'   => Category::where('is_active', true)->count(),
            'regions'      => Destination::active()->distinct('region')->count('region'),
        ];

        return view('visitor.home.index', compact(
            'popularDestinations', 'categories', 'upcomingEvents', 'stats'
        ));
    }
}
