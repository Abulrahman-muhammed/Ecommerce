<div class="navbar-cart">
    <div class="cart-items">
        <a href="javascript:void(0)" class="main-btn">
            <i class="lni lni-cart"></i>
            <span class="total-items">{{ $items->count() }}</span>
        </a>
        <!-- Shopping Item -->
        <div class="shopping-item">
            <div class="dropdown-cart-header">
                <span> {{ $items->count() }} Items</span>
                <a href="{{ route('front.cart.index') }}">View Cart</a>
            </div>
            <ul class="shopping-list">
                @forelse($items as $item)
                    <li>
                        <form action="{{ route('front.cart.destroy', $item->product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="remove">
                                <i class="lni lni-close"></i>
                            </button>
                        </form>

                        <div class="cart-img-head">
                            <a class="cart-img" href="{{ route('front.products.show', $item->product) }}">
                                <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}">
                            </a>
                        </div>

                        <div class="content">
                            <h4>
                                <a href="{{ route('front.products.show', $item->product) }}">
                                    {{ $item->product->name }}
                                </a>
                            </h4>

                            <p class="quantity">
                                {{ $item->quantity }}x -
                                <span class="amount">
                                    ${{ number_format($item->product->price * $item->quantity, 2) }}
                                </span>
                            </p>
                        </div>
                    </li>
                @empty
                    <li class="text-center p-3">
                        Cart is empty
                    </li>
                @endforelse
            </ul>
            <div class="bottom">
                <div class="total">
                    <span>Total</span>
                    <span class="total-amount">${{ number_format($total, 2) }}</span>
                </div>
                <div class="button">
                    <a href="{{ route('front.cart.index') }}" class="btn animate">Checkout</a>
                </div>
            </div>
        </div>
        <!--/ End Shopping Item -->
    </div>
</div>
