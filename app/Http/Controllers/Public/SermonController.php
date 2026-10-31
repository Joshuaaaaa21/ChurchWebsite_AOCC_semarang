<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\Sermon;
use App\Models\ServiceCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SermonController extends Controller
{
    /**
     * Display sermon archive with search and filter.
     */
    public function index(Request $request): View
    {
        $church = Church::first();
        $categories = ServiceCategory::where('group', 'ibadah')->orderBy('sort_order')->get();

        $query = Sermon::where('is_published', true)->with(['speaker', 'category']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('scripture_ref', 'like', "%{$search}%")
                    ->orWhere('speaker_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('kategori'));
            });
        }

        $sermons = $query->orderBy('preached_at', 'desc')->paginate(9)->withQueryString();

        return view('public.sermons.index', [
            'church' => $church,
            'sermons' => $sermons,
            'categories' => $categories,
            'selectedCategory' => $request->input('kategori'),
            'searchQuery' => $request->input('q'),
        ]);
    }

    /**
     * Display single sermon details.
     */
    public function show(string $slug): View
    {
        $church = Church::first();
        $sermon = Sermon::where('slug', $slug)->where('is_published', true)->with(['speaker', 'category'])->firstOrFail();

        $relatedSermons = Sermon::where('is_published', true)
            ->where('id', '!=', $sermon->id)
            ->where('service_category_id', $sermon->service_category_id)
            ->take(3)
            ->get();

        if ($relatedSermons->isEmpty()) {
            $relatedSermons = Sermon::where('is_published', true)
                ->where('id', '!=', $sermon->id)
                ->take(3)
                ->get();
        }

        return view('public.sermons.show', [
            'church' => $church,
            'sermon' => $sermon,
            'relatedSermons' => $relatedSermons,
        ]);
    }
}
