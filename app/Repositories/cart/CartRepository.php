<?php

namespace App\Repositories\cart;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CartRepository implements CartRepositoryInterface
{
    //  Get Identifier User || Guest
    private function getIdentifier(): array
    {
        if (auth()->check()) {
            return ['user_id' => auth()->id()];
        }

        return ['cookie_id' => $this->getCookieId()];
    }

    //  Get Cart Items
    public function getCartItems(): Collection
    {
        return Cart::where($this->getIdentifier())
            ->with('product:id,name,price,slug')
            ->get();
    }

    //  Add To Cart
    public function addToCart(Product $product, int $quantity = 1): Cart
    {
        $identifier = $this->getIdentifier();

        $cartItem = Cart::where($identifier)
            ->where('product_id', $product->id)
            ->first();


        if ($cartItem) {

            $newQty = $cartItem->quantity + $quantity;

            if (!$this->checkQuantity($product, $newQty)) {
                throw new \Exception('Not enough stock');
            }

            $cartItem->update([
                'quantity' => $newQty
            ]);

            return $cartItem;
        }

        if (!$this->checkQuantity($product, $quantity)) {
            throw new \Exception('Not enough stock');
        }

        return Cart::create(array_merge($identifier, [
            'product_id' => $product->id,
            'quantity'   => $quantity
        ]));
    }

    //  Update Quantity
    public function updateQuantity(Product $product, int $quantity): int
    {
        if (!$this->checkQuantity($product, $quantity)) {
            throw new \Exception('Not enough stock');
        }

        return Cart::where($this->getIdentifier())
            ->where('product_id', $product->id)
            ->update([
                'quantity' => $quantity
            ]);
    }

    //  Remove Item
    public function removeItem(Product $product): void
    {
        Cart::where($this->getIdentifier())
            ->where('product_id', $product->id)
            ->delete();
    }

    //  Clear Cart
    public function clearCart(): void
    {
        Cart::where($this->getIdentifier())->delete();
    }

    //  Get Total Price
    public function getCartTotal(): float
    {
        return Cart::where($this->getIdentifier())
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->sum(\DB::raw('products.price * carts.quantity'));
    }

    //  Cookie ID
    protected function getCookieId(): string
    {
        $cookie_id = Cookie::get('cart_cookie_id');

        if (!$cookie_id) {
            $cookie_id = Str::uuid()->toString();
            Cookie::queue('cart_cookie_id', $cookie_id, 60 * 24 * 30);
        }

        return $cookie_id;
    }

    //  Merge Cart After Login
    public function mergeCartAfterLogin(): void
    {
        $cookieId = Cookie::get('cart_cookie_id');

        if (!$cookieId) return;

        $items = Cart::where('cookie_id', $cookieId)->get();

        foreach ($items as $item) {

            $existing = Cart::where('user_id', auth()->id())
                ->where('product_id', $item->product_id)
                ->first();

            if ($existing) {
                $existing->update([
                    'quantity' => $existing->quantity + $item->quantity
                ]);

                $item->delete();
            } else {
                $item->update([
                    'user_id' => auth()->id(),
                    'cookie_id' => null
                ]);
            }
        }

        Cookie::queue(Cookie::forget('cart_cookie_id'));
    }

    //  Check Stock
    private function checkQuantity(Product $product, int $quantity): bool
    {
        return $quantity <= $product->quantity;
    }
}