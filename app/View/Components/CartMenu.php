<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Repositories\cart\CartRepositoryInterface;
class CartMenu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct( private CartRepositoryInterface $cartRepo )
    {
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
    $items = $this->cartRepo->getCartItems();
    $total = $this->cartRepo->getCartTotal();

        return view('components.cart-menu' , [ 'items' => $items, 'total' => $total]);
    }
}
