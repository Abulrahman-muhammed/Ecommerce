<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index(Request $request)
    {
        $tags = Tag::query()
            ->withCount('products')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                      ->orWhere('slug', 'like', $search);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating new tags.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store one or multiple tags at once.
     * Slugs are ALWAYS generated from names — never trusted from the request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'names'   => ['required', 'array', 'min:1'],
            'names.*' => ['required', 'string', 'max:100'],
        ], [
            'names.required'   => 'Please add at least one tag.',
            'names.min'        => 'Please add at least one tag.',
            'names.*.required' => 'Tag name cannot be empty.',
            'names.*.max'      => 'Each tag name must not exceed 100 characters.',
        ]);

        $created  = 0;
        $skipped  = [];

        foreach ($request->names as $name) {
            $name = trim($name);
            if (!$name) continue;

            // Skip if name already exists (case-insensitive)
            if (Tag::whereRaw('LOWER(name) = ?', [strtolower($name)])->exists()) {
                $skipped[] = $name;
                continue;
            }

            Tag::create([
                'name' => $name,
                'slug' => $this->uniqueSlug(Str::slug($name)),
            ]);

            $created++;
        }

        $message = $created . ' tag' . ($created !== 1 ? 's' : '') . ' created successfully.';

        if (!empty($skipped)) {
            $message .= ' Skipped duplicates: ' . implode(', ', $skipped) . '.';
        }

        return redirect()
            ->route('admin.tags.index')
            ->with('success', $message);
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag.
     * Slug is ALWAYS re-generated from the name.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('tags', 'name')->ignore($tag->id),
            ],
        ], [
            'name.required' => 'Tag name is required.',
            'name.unique'   => 'A tag with this name already exists.',
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => $this->uniqueSlug(Str::slug($request->name), $tag->id),
        ]);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag "' . $tag->name . '" updated successfully.');
    }

    /**
     * Soft-delete (move to trash).
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag "' . $tag->name . '" moved to trash.');
    }

    /**
     * Display trashed tags.
     */
    public function trashed(Request $request)
    {
        $tags = Tag::onlyTrashed()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                      ->orWhere('slug', 'like', $search);
                });
            })
            ->latest('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.tags.trashed', compact('tags'));
    }

    /**
     * Restore a soft-deleted tag.
     */
    public function restore($id)
    {
        $tag = Tag::onlyTrashed()->findOrFail($id);
        $tag->restore();

        return redirect()
            ->route('admin.tags.trashed')
            ->with('success', 'Tag "' . $tag->name . '" has been restored.');
    }

    /**
     * Permanently delete a single trashed tag.
     */
    public function forceDelete($id)
    {
        $tag  = Tag::onlyTrashed()->findOrFail($id);
        $name = $tag->name;
        $tag->forceDelete();

        return redirect()
            ->route('admin.tags.trashed')
            ->with('success', 'Tag "' . $name . '" permanently deleted.');
    }

    /**
     * Permanently delete ALL trashed tags.
     */
    public function emptyTrash()
    {
        $count = Tag::onlyTrashed()->count();
        Tag::onlyTrashed()->forceDelete();

        return redirect()
            ->route('admin.tags.trashed')
            ->with('success', $count . ' tag(s) permanently deleted from trash.');
    }

    /* ────────────────────────────────────────────────────
     |  HELPERS
     * ────────────────────────────────────────────────── */

    /**
     * Generate a unique slug, appending -2, -3 … if needed.
     *
     * @param  string   $base      e.g. "new-arrival"
     * @param  int|null $ignoreId  Ignore this tag's own row when updating
     */
    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug    = $base;
        $counter = 2;

        while (
            Tag::withTrashed()
               ->where('slug', $slug)
               ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
               ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}