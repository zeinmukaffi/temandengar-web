<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(): View
    {
        $resources = Resource::where('is_published', true)
            ->latest()
            ->paginate(9);

        return view('public.resources.index', compact('resources'));
    }

    public function show(Resource $resource): View
    {
        abort_unless($resource->is_published, 404);

        return view('public.resources.show', compact('resource'));
    }
}