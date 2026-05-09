<?php

namespace App\Repositories\cart;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    public function getCartItems(): Collection;
    public function addToCart(Product $product, int $quantity = 1): Cart;
    public function updateQuantity(Product $product, int $quantity): int;
    public function removeItem(Product $product): void;
    public function clearCart(): void;
    public function getCartTotal(): float;
    public function mergeCartAfterLogin(): void;
}