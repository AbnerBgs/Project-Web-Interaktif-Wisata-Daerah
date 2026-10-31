<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Fitur Itinerary Otomatis — "1 Hari di [Region X]"
 * Algoritma: pilih ≤5 destinasi dari region yang dipilih, distribusikan per slot waktu.
 */
class ItineraryController extends Controller
{
    // Slot waktu satu hari wisata
    private const TIME_SLOTS = [
        ['slot' => 'Pagi (07:00-09:00)',     'slot_en' => 'Morning (07:00-09:00)'],
        ['slot' => 'Pagi (09:30-11:30)',     'slot_en' => 'Late Morning (09:30-11:30)'],
        ['slot' => 'Siang (12:00-14:00)',    'slot_en' => 'Noon (12:00-14:00)'],
        ['slot' => 'Sore (14:30-16:30)',     'slot_en' => 'Afternoon (14:30-16:30)'],
        ['slot' => 'Sore (17:00-18:00)',     'slot_en' => 'Late Afternoon (17:00-18:00)'],
    ];

    public function index()
    {
        $regions    = Destination::active()->distinct()->orderBy('region')->pluck('region');
        $categories = Category::where('is_active', true)->get();
        return view('visitor.home.itinerary', compact('regions', 'categories'));
    }

    /**
     * POST /itinerary/generate
     * Generate itinerary berdasarkan region dan kategori pilihan pengguna.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'region'       => 'required|string',
            'categories'   => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $query = Destination::active()
            ->byRegion($request->region)
            ->with(['category', 'images'])
            ->orderByDesc('rating_avg');

        // Filter berdasarkan kategori pilihan (jika ada)
        if (!empty($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        }

        // Ambil maksimal 5 destinasi terbaik
        $destinations = $query->limit(5)->get();

        if ($destinations->isEmpty()) {
            return back()->with('error', __('messages.itinerary_empty'));
        }

        // Pasangkan setiap destinasi dengan slot waktu
        $itinerary = $destinations->map(function ($dest, $index) {
            $slot = self::TIME_SLOTS[$index] ?? self::TIME_SLOTS[count(self::TIME_SLOTS) - 1];
            return [
                'destination' => $dest,
                'time_slot'   => app()->getLocale() === 'en' ? $slot['slot_en'] : $slot['slot'],
                'order'       => $index + 1,
            ];
        });

        // Hitung jarak antar destinasi untuk estimasi perjalanan
        $totalDistance = 0;
        for ($i = 0; $i < $destinations->count() - 1; $i++) {
            $totalDistance += $destinations[$i]->distanceTo(
                $destinations[$i + 1]->latitude,
                $destinations[$i + 1]->longitude
            );
        }

        return view('visitor.home.itinerary-result', compact(
            'itinerary', 'totalDistance', 'destinations'
        ))->with('region', $request->region);
    }
}
