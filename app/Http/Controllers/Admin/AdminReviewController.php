<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

/**
 * Admin: Moderasi ulasan pengunjung (Approve / Reject).
 */
class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['destination', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        $reviews = $query->paginate(20)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Setujui ulasan — update status, recalculate rating destinasi.
     */
    public function approve(int $id)
    {
        $review = Review::findOrFail($id);
        $review->update([
            'status'       => 'approved',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        // Recalculate rating rata-rata destinasi
        $review->destination->recalculateRating();

        return back()->with('success', 'Ulasan disetujui.');
    }

    /**
     * Tolak ulasan.
     */
    public function reject(Request $request, int $id)
    {
        $review = Review::findOrFail($id);
        $review->update([
            'status'       => 'rejected',
            'admin_note'   => $request->admin_note,
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        // Recalculate karena bisa jadi review ini sebelumnya approved
        $review->destination->recalculateRating();

        return back()->with('success', 'Ulasan ditolak.');
    }

    public function destroy(int $id)
    {
        $review = Review::findOrFail($id);
        $destination = $review->destination;
        $review->delete();
        $destination->recalculateRating();
        return back()->with('success', 'Ulasan dihapus.');
    }
}
