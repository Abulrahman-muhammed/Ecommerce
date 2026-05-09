<div class="single-product">
    <div class="product-image">
        <img src="{{ $product->main_image_url }}"
            alt="{{ $product->name }}">

        @if ($product->compare_price > $product->price)
            <span class="sale-tag">
                -{{ $product->discount_percentage }}%
            </span>
        @endif

        <div class="button">
            {{-- <a href="{{ route('front.cart.store', $product->id) }}" class="btn">
                <i class="lni lni-cart"></i> Add to Cart
            </a> --}}
            {{-- form to add to cart --}}
            <form action="{{ route('front.cart.store', $product->id) }}" method="POST">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn">
                    <i class="lni lni-cart"></i> Add to Cart
                </button>
            </form>
        </div>
    </div>

    <div class="product-info">
        <span class="category">{{ $product->category?->name }}</span>
        <h4 class="title">
            <a href="{{ route('front.products.show', $product->slug) }}">{{ $product->name }}</a>
        </h4>

        {{-- Dynamic Rating Stars --}}
        <ul class="review">


            @for ($i = 0; $i < $product->stars['full']; $i++)
                <li><i class="lni lni-star-filled"></i></li>
            @endfor

            @if ($product->stars['half'])
                <li><i class="lni lni-star-half"></i></li>
            @endif

            @for ($i = 0; $i < $product->stars['empty']; $i++)
                <li><i class="lni lni-star"></i></li>
            @endfor
            <li><span>{{ number_format($product->rating, 1) }} Review(s)</span></li>
        </ul>

        <div class="price">
            <span>${{ number_format($product->price, 2) }}</span>
            @if ($product->compare_price > $product->price)
                <span class="discount-price">${{ number_format($product->compare_price, 2) }}</span>
            @endif
        </div>
    </div>
</div>
