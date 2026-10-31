<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display events archive.
     */
    public function index(Request $request): View
    {
        $church = Church::first();
        $query = Event::where('is_published', true)->with('contactPerson');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $events = $query->orderBy('event_date', 'asc')->paginate(9)->withQueryString();

        return view('public.events.index', [
            'church' => $church,
            'events' => $events,
            'searchQuery' => $request->input('q'),
        ]);
    }

    /**
     * Display single event details.
     */
    public function show(string $slug): View
    {
        $church = Church::first();
        $event = Event::where('slug', $slug)->where('is_published', true)->with('contactPerson')->firstOrFail();

        $upcomingEvents = Event::where('is_published', true)
            ->where('id', '!=', $event->id)
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        return view('public.events.show', [
            'church' => $church,
            'event' => $event,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
