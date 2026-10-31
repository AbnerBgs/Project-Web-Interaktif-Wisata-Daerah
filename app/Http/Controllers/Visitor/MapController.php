<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;

class MapController extends Controller
{
    public function index()
    {
        // Data untuk dropdown filter pada peta
        $categories = Category::where('is_active', true)->get();
        $regions    = Destination::active()->distinct()->orderBy('region')->pluck('region');

        return view('visitor.map.index', compact('categories', 'regions'));
    }
}
