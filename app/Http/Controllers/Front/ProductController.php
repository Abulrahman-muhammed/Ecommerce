<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Enums\ProductStatusEnum;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $filters = $request->only(['search', 'category', 'min_price', 'max_price']);
    $sort    = $request->input('sort', 'latest');

    $products = Product::with(['category', 'mainImage'])   
        ->withCount('tags')                                 
        ->active()
        ->frontFilter($filters)
        ->sorted($sort)
        ->paginate(12)
        ->withQueryString();                            

    $categories = Category::withCount([
            'products' => fn ($q) => $q->active(),
        ])
        ->having('products_count', '>', 0)
        ->orderBy('name')
        ->get();

    return view('front.products.index', compact('products', 'categories', 'filters', 'sort'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if ($product->status != ProductStatusEnum::ACTIVE) {
            abort(404);
        }
        $product->load(['mainImage', 'gallery','tags']);
        $product->load(['mainImage', 'gallery','tags']);
        return view('front.products.show', compact('product'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
