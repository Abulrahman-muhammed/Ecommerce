<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Enums\CategoryStatusEnum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageUploadService;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
class CategoryController extends Controller
{
    // inject image upload service
    public function __construct(
        private ImageUploadService $imageUploadService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent','images')
        ->withoutTrashed()
        ->filter(request()->only(['search' , 'status']))
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
    */
    public function create()
    {
        $categories = Category::where('parent_id', null)->where('status', CategoryStatusEnum::ACTIVE)->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // use transaction
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']);
            $category = Category::create($data);

            // image upload
        if ($request->hasFile('image')) {
            $path = $this->imageUploadService->upload($request->file('image'), 'categories');
            $category->images()->create([
                'path' => $path,
                'usage' => 'category image',
            ]);
        }
        DB::commit();
        // post redirect get pattern
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Category creation failed')->withInput();
    }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::whereNull('parent_id')
            ->where('status', CategoryStatusEnum::ACTIVE)
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('categories', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        // use transaction
        DB::beginTransaction();
        try {
        $category = Category::findOrFail($id);
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);

        // image upload => if image is uploaded, delete old image and upload new image
        if ($request->hasFile('image')) {
            // delete old image
            if ($category->images->first()) {
                $this->imageUploadService->delete($category->images->first()->path);
            }
            // upload new image
            $path = $this->imageUploadService->upload($request->file('image'), 'categories');
            $category->images()->updateOrCreate([
                'usage' => 'category image',
            ],[
                'path' => $path,
            ]);
        }
        DB::commit();
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Category update failed')->withInput();
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // use transaction
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }

    // show trashed categories
    public function trashed()
    {
        $categories = Category::with('parent','images')
        ->onlyTrashed()
        ->filter(request()->only(['search' , 'status']))
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('admin.categories.trashed', compact('categories'));
    }
    // restore deleted category
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('admin.categories.index')->with('success', 'Category restored successfully');
    }

    // force delete category
    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        // delete image if exists
        if ($category->images->first()) {
            $this->imageUploadService->delete($category->images->first()->path);
            $category->images()->delete();
        }
        $category->forceDelete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted permanently');
    }
}
