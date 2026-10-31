<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::upcoming()->with('destination')->orderBy('start_date')->paginate(9);
        return view('visitor.events.index', compact('events'));
    }

    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)->where('is_active', true)->with('destination')->firstOrFail();
        return view('visitor.events.show', compact('event'));
    }
}
