<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

/**
 * API Controller: menyediakan data marker GeoJSON untuk Leaflet Map.
 */
class MapApiController extends Controller
{
    /**
     * GET /api/map/markers
     * Mengembalikan semua destinasi aktif dalam format GeoJSON
     * agar dapat langsung diproses oleh Leaflet.js.
     *
     * Query params:
     *   - category_id : filter per kategori
     *   - region      : filter per wilayah
     */
    public function markers(Request $request)
    {
        $query = Destination::with('category')
            ->active()
            ->select([
                'id', 'name', 'name_en', 'slug', 'category_id',
                'latitude', 'longitude', 'thumbnail',
                'rating_avg', 'review_count', 'region', 'address',
            ]);

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Filter wilayah
        if ($request->filled('region')) {
            $query->byRegion($request->region);
        }

        $destinations = $query->get();

        // Bangun format GeoJSON FeatureCollection
        $features = $destinations->map(function ($dest) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type'        => 'Point',
                    'coordinates' => [$dest->longitude, $dest->latitude], // GeoJSON: [lng, lat]
                ],
                'properties' => [
                    'id'           => $dest->id,
                    'name'         => app()->getLocale() === 'en' && $dest->name_en ? $dest->name_en : $dest->name,
                    'slug'         => $dest->slug,
                    'category'     => $dest->category?->name,
                    'category_color' => $dest->category?->color ?? '#3b82f6',
                    'thumbnail'    => $dest->thumbnail ? asset('storage/' . $dest->thumbnail) : asset('images/placeholder.jpg'),
                    'rating'       => $dest->rating_avg,
                    'reviews'      => $dest->review_count,
                    'region'       => $dest->region,
                    'detail_url'   => route('destinations.show', $dest->slug),
                ],
            ];
        });

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
