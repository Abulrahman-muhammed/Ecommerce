<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ImageUploadService;
use App\Enums\ProductImageUsage;
use App\Enums\CategoryStatusEnum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;

class ProductController extends Controller
{
    private const PAGINATION_COUNT = 10;
    public function __construct(protected ImageUploadService $imageService) {}

    public function index(Request $request)
    {
        $products = Product::with(['mainImage', 'category'])
            ->filter($request->only(['search', 'status', 'category_id', 'is_featured']))
            ->latest()
            ->paginate(self::PAGINATION_COUNT)
            ->withQueryString();

        return view('admin.products.index', [
            'products'   => $products,
            'categories' => $this->activeCategories(),
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => $this->activeCategories(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            $product = Product::create($this->prepareData($request));
            // ── Sync tags ──────────────────────────────────────────
            $product->tags()->sync($request->input('tags', []));  
            $this->uploadMainImage($request, $product);
            $this->uploadGalleryImages($request, $product);
        });

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['mainImage', 'gallery','tags']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['mainImage', 'gallery','tags']);
        return view('admin.products.edit', [
            'product'    => $product,
            'categories' => $this->activeCategories(),
            'tags'       => Tag::orderBy('name')->get(),
            'selectedTags' => $product->tags->pluck('id')->toArray(), 
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $product->update($this->prepareData($request));
            // ── Sync tags ──────────────────────────────────────────
            $product->tags()->sync($request->input('tags', []));
            if ($request->boolean('remove_main_image') || $request->hasFile('main_image')) {
                $this->deleteMainImage($product);
            }

            $this->uploadMainImage($request, $product);
            $this->removeGalleryImages($request, $product);
            $this->uploadGalleryImages($request, $product);
        });

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product moved to trash.');
    }

    public function trashed(Request $request)
    {
        $products = Product::onlyTrashed()
            ->with(['mainImage', 'category'])
            ->filter($request->only(['search', 'status', 'category_id', 'is_featured']))
            ->latest()
            ->paginate(self::PAGINATION_COUNT)
            ->withQueryString();

        return view('admin.products.trashed', [
            'products'   => $products,
            'categories' => $this->activeCategories(),
        ]);
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('admin.products.trashed')->with('success', 'Product restored.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->with('images')->findOrFail($id);

        foreach ($product->images as $image) {
            $this->imageService->delete($image->path);
        }

        $product->images()->delete();
        $product->tags()->detach();
        $product->forceDelete();

        return redirect()->route('admin.products.trashed')->with('success', 'Product permanently deleted.');
    }

    // ── Helpers ──────────────────────────────────────────────────────

    private function prepareData(Request $request): array
    {
        $data = $request->validated(); 
        $data['slug'] = Str::slug($data['name']).'-'.Str::random(6);
        return $data;
    }

    private function activeCategories()
    {
        return Category::active()->get();
    }

    private function uploadMainImage($request, $product)
    {
        if ($request->hasFile('main_image')) {
            $path = $this->imageService->upload($request->file('main_image'), 'products');
            $product->images()->create([
                'path'  => $path,
                'usage' => ProductImageUsage::PRODUCT_MAIN_IMAGE,
            ]);
        }
    }

    private function deleteMainImage($product)
    {
        if ($product->mainImage) {
            $this->imageService->delete($product->mainImage->path);
            $product->mainImage->delete();
        }
    }

    private function uploadGalleryImages($request, $product)
    {
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $this->imageService->upload($file, 'products');
                $product->images()->create([
                    'path'  => $path,
                    'usage' => ProductImageUsage::PRODUCT_GALLARY,
                ]);
            }
        }
    }

    private function removeGalleryImages($request, $product)
    {
        if ($request->filled('remove_gallery_ids')) {
            $images = $product->gallery()
                ->whereIn('id', $request->remove_gallery_ids)
                ->get();

            foreach ($images as $image) {
                $this->imageService->delete($image->path);
                $image->delete();
            }
        }
    }
}