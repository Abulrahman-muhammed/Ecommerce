<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Enums\ProductStatusEnum;
use Illuminate\Support\Facades\Cache;
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
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if ($product->status != ProductStatusEnum::ACTIVE) {
            abort(404);
        }
        $product->load(['category','mainImage', 'gallery','tags']);
        return view('front.products.show', compact('product'));
    }


}
