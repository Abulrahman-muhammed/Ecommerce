<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\cart\CartRepositoryInterface;
use App\Models\Product;
class CartController extends Controller
{
    public function __construct(private CartRepositoryInterface $cartRepo)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = $this->cartRepo->getCartItems();
        $total = $this->cartRepo->getCartTotal();

        return view('front.cart.index', compact('items', 'total'));
    }

    public function store(Product $product)
    {
        $this->cartRepo->addToCart($product);

        return back();
    }

    public function update(Request $request, Product $product)
    {
        $this->cartRepo->updateQuantity($product, $request->quantity);

        return back();
    }

    public function destroy(Product $product)
    {
        $this->cartRepo->removeItem($product);

        return back();
    }

    public function clear()
    {
        $this->cartRepo->clearCart();

        return back();
    }
}
