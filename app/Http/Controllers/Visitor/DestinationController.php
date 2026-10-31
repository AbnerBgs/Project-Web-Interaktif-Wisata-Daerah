<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Category;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Daftar destinasi dengan filter & pencarian.
     */
    public function index(Request $request)
    {
        $query = Destination::active()->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('region')) {
            $query->byRegion($request->region);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $destinations = $query->orderByDesc('rating_avg')->paginate(12)->withQueryString();
        $categories   = Category::where('is_active', true)->get();
        $regions      = Destination::active()->distinct()->orderBy('region')->pluck('region');

        return view('visitor.destinations.index', compact('destinations', 'categories', 'regions'));
    }

    /**
     * Halaman detail destinasi — tambah view_count tiap kunjungan.
     */
    public function show(string $slug)
    {
        $destination = Destination::active()
            ->with(['category', 'images', 'facilities', 'approvedReviews.user', 'events'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Tambah view count
        $destination->increment('view_count');

        // Destinasi serupa dari kategori yang sama
        $related = Destination::active()
            ->where('category_id', $destination->category_id)
            ->where('id', '!=', $destination->id)
            ->limit(4)
            ->get();

        // Hitung distribusi rating untuk progress bar
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $destination->approvedReviews->where('rating', $i)->count();
        }

        return view('visitor.destinations.show', compact(
            'destination', 'related', 'ratingDistribution'
        ));
    }
}
