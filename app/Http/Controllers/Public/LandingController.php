<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\ContactPerson;
use App\Models\DailyVerse;
use App\Models\Event;
use App\Models\Pastor;
use App\Models\Sermon;
use App\Models\ServiceCategory;
use Illuminate\Contracts\View\View;

class LandingController extends Controller
{
    /**
     * Display the cinematic landing page.
     */
    public function index(): View
    {
        $church = Church::with('aboutContent')->first();
        $about = $church?->aboutContent;
        $primaryPastor = Pastor::where('is_primary', true)->first() ?? Pastor::first();
        $pakdheArdi = ContactPerson::where('name', 'like', '%Ardi%')->first() ?? ContactPerson::first();

        // Get daily verse: today's verse or fallback to Kejadian 1:1 default verse
        $dailyVerse = DailyVerse::where('verse_date', now()->toDateString())->first()
            ?? DailyVerse::where('is_default', true)->first()
            ?? DailyVerse::first();

        // 6 Ibadah service categories with their services and contact persons
        $ibadahCategories = ServiceCategory::where('group', 'ibadah')
            ->where('is_active', true)
            ->with(['services.contactPerson'])
            ->orderBy('sort_order')
            ->get();

        // 3 Beranda community categories
        $berandaCategories = ServiceCategory::where('group', 'beranda')
            ->where('is_active', true)
            ->with(['services.contactPerson'])
            ->orderBy('sort_order')
            ->get();

        // Sermons for carousel
        $sermons = Sermon::where('is_published', true)
            ->with(['speaker', 'category'])
            ->orderBy('preached_at', 'desc')
            ->take(6)
            ->get();

        // Upcoming events for carousel
        $events = Event::where('is_published', true)
            ->with('contactPerson')
            ->orderBy('event_date', 'asc')
            ->take(6)
            ->get();

        return view('public.landing', [
            'church' => $church,
            'about' => $about,
            'primaryPastor' => $primaryPastor,
            'pakdheArdi' => $pakdheArdi,
            'dailyVerse' => $dailyVerse,
            'ibadahCategories' => $ibadahCategories,
            'berandaCategories' => $berandaCategories,
            'sermons' => $sermons,
            'events' => $events,
        ]);
    }
}
