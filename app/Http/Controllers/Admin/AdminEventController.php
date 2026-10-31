<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::with('destination')->latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $destinations = Destination::active()->orderBy('name')->pluck('name', 'id');
        return view('admin.events.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $data = $this->validateEvent($request);
        $data['slug'] = Str::slug($request->title);
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('events', 'public');
        }
        Event::create($data);
        return redirect()->route('admin.events.index')->with('success', 'Event ditambahkan.');
    }

    public function edit(Event $event)
    {
        $destinations = Destination::active()->orderBy('name')->pluck('name', 'id');
        return view('admin.events.edit', compact('event', 'destinations'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $this->validateEvent($request);
        if ($request->hasFile('thumbnail')) {
            if ($event->thumbnail) Storage::disk('public')->delete($event->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('events', 'public');
        }
        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event diperbarui.');
    }

    public function destroy(Event $event)
    {
        if ($event->thumbnail) Storage::disk('public')->delete($event->thumbnail);
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event dihapus.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'destination_id' => 'nullable|exists:destinations,id',
            'title'          => 'required|string|max:200',
            'title_en'       => 'nullable|string|max:200',
            'description'    => 'required|string',
            'description_en' => 'nullable|string',
            'thumbnail'      => 'nullable|image|max:2048',
            'location'       => 'required|string|max:200',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'organizer'      => 'nullable|string|max:150',
            'ticket_info'    => 'nullable|string|max:200',
            'is_active'      => 'boolean',
        ]);
    }
}
