<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\V1\ProductResource;  
use App\Http\Resources\V1\CategoryResource;
use  App\Helpers\ApiResponse;
use App\Enums\ProductStatusEnum;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'category',
            'min_price',
            'max_price',
        ]);

        $sort = $request->input(
            'sort',
            'latest'
        );

        $products = Product::query()

            ->with([
                'category',
                'mainImage',
            ])

            ->withCount('tags')

            ->active()

            ->frontFilter($filters)

            ->sorted($sort)

            ->paginate(12)

            ->withQueryString();

        $categories = Category::query()

            ->withCount([
                'products' => fn ($q)
                    => $q->active(),
            ])

            ->having('products_count', '>', 0)

            ->orderBy('name')

            ->get();

        return ApiResponse::sendResponse(

            200,

            'Products fetched successfully.',

            [

                'products' => [

                    'meta' => [

                        'count' =>
                            $products->total(),
                    ],

                    'data' => ProductResource::collection(
                        $products
                    ),

                    'pagination' => [

                        'current_page' =>
                            $products->currentPage(),

                        'last_page' =>
                            $products->lastPage(),

                        'per_page' =>
                            $products->perPage(),

                        'total' =>
                            $products->total(),
                    ],
                ],

                'categories' =>
                    CategoryResource::collection(
                        $categories
                    ),

                'filters' => $filters,

                'sort' => $sort,
            ]
        );
    }
    public function show(Product $product)
    {
        if ($product->status != ProductStatusEnum::ACTIVE) {
            return ApiResponse::sendResponse(404, 'Product not found.', []);
        }
        $product->load(['category', 'mainImage', 'gallery','tags']);
        return ApiResponse::sendResponse(
                200,
                'Product fetched successfully.',
                [
                    'product' => new ProductResource($product)
                ]
            );
    }
}
