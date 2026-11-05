<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    private function categories(): array
    {
        return [
            'academic' => 'Akademik',
            'personal' => 'Pengembangan Diri',
            'social' => 'Sosial dan Pertemanan',
            'organization' => 'Organisasi',
            'professional_support' => 'Bantuan Profesional',
            'general' => 'Umum',
        ];
    }

    public function index(Request $request): View
    {
        $resources = Resource::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'published') {
                    $query->where('is_published', true);
                } elseif ($request->status === 'draft') {
                    $query->where('is_published', false);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.resources.index', compact('resources'));
    }

    public function create(): View
    {
        $categories = $this->categories();

        return view('admin.resources.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'category' => [
                'required',
                Rule::in(array_keys($this->categories())),
            ],
            'description' => ['nullable', 'string', 'max:3000'],
            'resource_url' => ['required', 'url', 'max:255'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        DB::transaction(function () use ($validated) {
            $resource = Resource::create($validated);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'resource_created',
                'description' => "Menambahkan materi: {$resource->title}.",
            ]);
        });

        return redirect()
            ->route('admin.resources.index')
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit(Resource $resource): View
    {
        $categories = $this->categories();

        return view('admin.resources.edit', compact(
            'resource',
            'categories'
        ));
    }

    public function update(
        Request $request,
        Resource $resource
    ): RedirectResponse {

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'category' => [
                'required',
                Rule::in(array_keys($this->categories())),
            ],
            'description' => ['nullable', 'string', 'max:3000'],
            'resource_url' => ['required', 'url', 'max:255'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        DB::transaction(function () use ($validated, $resource) {
            $resource->update($validated);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'resource_updated',
                'description' => "Memperbarui materi: {$resource->title}.",
            ]);
        });

        return redirect()
            ->route('admin.resources.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        DB::transaction(function () use ($resource) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'resource_deleted',
                'description' => "Menghapus materi: {$resource->title}.",
            ]);

            $resource->delete();
        });

        return redirect()
            ->route('admin.resources.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}