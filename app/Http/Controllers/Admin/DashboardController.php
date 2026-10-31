<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Review;
use App\Models\Event;
use App\Models\User;

/**
 * Admin Dashboard: statistik ringkas & destinasi terpopuler.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_destinations' => Destination::count(),
            'active_destinations'=> Destination::active()->count(),
            'pending_reviews'    => Review::pending()->count(),
            'total_reviews'      => Review::count(),
            'total_users'        => User::where('role', 'user')->count(),
            'total_events'       => Event::count(),
        ];

        // Top 5 destinasi terpopuler berdasarkan view count
        $popularDestinations = Destination::with('category')
            ->orderByDesc('view_count')
            ->limit(5)
            ->get();

        // Top 5 destinasi dengan rating tertinggi
        $topRatedDestinations = Destination::with('category')
            ->where('review_count', '>', 0)
            ->orderByDesc('rating_avg')
            ->orderByDesc('review_count')
            ->limit(5)
            ->get();

        // Review terbaru menunggu moderasi
        $pendingReviews = Review::pending()
            ->with(['destination', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        // Distribusi destinasi per kategori untuk chart
        $categoryDistribution = Destination::active()
            ->join('categories', 'destinations.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(*) as total')
            ->groupBy('categories.name')
            ->pluck('total', 'categories.name');

        return view('admin.dashboard.index', compact(
            'stats', 'popularDestinations', 'topRatedDestinations',
            'pendingReviews', 'categoryDistribution'
        ));
    }
}
