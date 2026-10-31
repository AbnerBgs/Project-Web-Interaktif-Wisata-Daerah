<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Destination;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, int $id)
    {
        $destination = Destination::active()->findOrFail($id);

        $data = $request->validate([
            'reviewer_name'  => 'required|string|max:100',
            'reviewer_email' => 'nullable|email|max:150',
            'rating'         => 'required|integer|between:1,5',
            'title'          => 'nullable|string|max:150',
            'comment'        => 'required|string|min:10|max:1000',
        ]);

        $data['destination_id'] = $destination->id;
        $data['user_id']        = auth()->id(); // null jika tamu
        $data['status']         = 'pending';   // Harus disetujui admin dulu

        Review::create($data);

        return back()->with('success', __('messages.review_pending'));
    }
}
