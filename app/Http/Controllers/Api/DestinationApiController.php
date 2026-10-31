<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

/**
 * API Controller: filter, search, dan nearby destinations untuk AJAX frontend.
 */
class DestinationApiController extends Controller
{
    /**
     * GET /api/destinations/nearby?lat=X&lng=Y&radius=10&limit=6
     * Mengembalikan destinasi terdekat dari posisi pengunjung.
     * Menggunakan formula Haversine di database (raw SQL) untuk efisiensi.
     */
    public function nearby(Request $request)
    {
        $request->validate([
            'lat'    => 'required|numeric|between:-90,90',
            'lng'    => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:100',
            'limit'  => 'nullable|integer|min:1|max:20',
        ]);

        $lat    = (float) $request->lat;
        $lng    = (float) $request->lng;
        $radius = (float) ($request->radius ?? 20); // km, default 20
        $limit  = (int)   ($request->limit  ?? 6);

        /*
         * Formula Haversine dalam SQL:
         * distance = 6371 * acos(cos(radians(lat)) * cos(radians(dest_lat))
         *              * cos(radians(dest_lng) - radians(lng))
         *              + sin(radians(lat)) * sin(radians(dest_lat)))
         */
        $destinations = Destination::active()
            ->with('category')
            ->selectRaw("
                id, name, name_en, slug, category_id, latitude, longitude,
                thumbnail, rating_avg, review_count, region, address,
                ( 6371 * acos(
                    cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
            ->havingRaw('distance <= ?', [$radius])
            ->orderBy('distance')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $destinations->map(fn($d) => [
                'id'        => $d->id,
                'name'      => $d->localized_name,
                'slug'      => $d->slug,
                'thumbnail' => $d->thumbnail ? asset('storage/' . $d->thumbnail) : asset('images/placeholder.jpg'),
                'rating'    => $d->rating_avg,
                'reviews'   => $d->review_count,
                'region'    => $d->region,
                'category'  => $d->category?->name,
                'distance'  => round($d->distance, 1) . ' km',
                'url'       => route('destinations.show', $d->slug),
                'lat'       => $d->latitude,
                'lng'       => $d->longitude,
            ]),
        ]);
    }

    /**
     * GET /api/destinations/filter?category_id=X&region=Y&search=Z
     * Filter destinasi tanpa reload halaman (AJAX).
     */
    public function filter(Request $request)
    {
        $query = Destination::active()->with('category')
            ->select('id','name','name_en','slug','category_id','latitude','longitude',
                     'thumbnail','rating_avg','review_count','region','address','ticket_price');

        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }
        if ($request->filled('region')) {
            $query->byRegion($request->region);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $results = $query->orderByDesc('rating_avg')->paginate(12);

        return response()->json([
            'success'    => true,
            'data'       => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'last_page'    => $results->lastPage(),
                'total'        => $results->total(),
            ],
        ]);
    }

    /**
     * GET /api/destinations/search?q=keyword
     * Autocomplete pencarian destinasi.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $results = Destination::active()
            ->search($keyword)
            ->select('id', 'name', 'slug', 'region')
            ->limit(8)
            ->get()
            ->map(fn($d) => [
                'id'     => $d->id,
                'name'   => $d->name,
                'region' => $d->region,
                'url'    => route('destinations.show', $d->slug),
            ]);

        return response()->json($results);
    }
}
