@extends('front.layouts.app')

@section('title', 'Cart')

@push('styles')
<style>
    /* ── Breadcrumb ── */
    .breadcrumbs {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 28px 0;
        border-bottom: 2px solid #e8b86d;
    }
    .breadcrumbs .page-title {
        color: #fff;
        font-size: 1.6rem;
        font-weight: 700;
        letter-spacing: .5px;
        margin: 0;
    }
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        list-style: none;
        padding: 0; margin: 0;
        gap: 6px;
    }
    .breadcrumb-nav li { color: #aab; font-size: .9rem; }
    .breadcrumb-nav li a { color: #e8b86d; text-decoration: none; transition: opacity .2s; }
    .breadcrumb-nav li a:hover { opacity: .75; }
    .breadcrumb-nav li + li::before { content: '/'; margin-right: 6px; color: #555; }

    /* ── Section ── */
    .shopping-cart.section { padding: 60px 0; background: #f7f8fc; }

    /* ── Cart Head ── */
    .cart-list-head {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,.07);
        margin-bottom: 32px;
    }
    .cart-list-title {
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        padding: 16px 24px;
    }
    .cart-list-title p {
        color: #e8b86d;
        font-weight: 600;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0;
    }

    /* ── Cart Row ── */
    .cart-single-list {
        padding: 20px 24px;
        border-bottom: 1px solid #f0f0f5;
        transition: background .2s;
    }
    .cart-single-list:last-child { border-bottom: none; }
    .cart-single-list:hover { background: #fafbff; }
    .cart-single-list img {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #eee;
        transition: transform .2s;
    }
    .cart-single-list img:hover { transform: scale(1.06); }

    /* Product name */
    .product-name a {
        color: #1a1a2e;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: color .2s;
    }
    .product-name a:hover { color: #e8b86d; }

    /* Qty form */
    .count-input form { display: flex; align-items: center; gap: 8px; }
    .count-input input[type="number"] {
        width: 68px;
        padding: 7px 10px;
        border: 1.5px solid #e0e0ee;
        border-radius: 8px;
        font-size: .9rem;
        color: #333;
        background: #f7f8fc;
        outline: none;
        transition: border-color .2s;
    }
    .count-input input[type="number"]:focus { border-color: #e8b86d; background: #fff; }
    .count-input button[type="submit"] {
        padding: 7px 14px;
        background: #1a1a2e;
        color: #e8b86d;
        border: none;
        border-radius: 8px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, transform .15s;
    }
    .count-input button[type="submit"]:hover { background: #e8b86d; color: #1a1a2e; transform: translateY(-1px); }

    /* Price */
    .cart-single-list .row p {
        color: #1a1a2e;
        font-weight: 600;
        font-size: .95rem;
        margin: 0;
    }

    /* Remove button */
    .remove-item {
        background: #fff0f0;
        border: 1.5px solid #ffd0d0;
        color: #e05555;
        width: 36px; height: 36px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .2s, transform .15s;
        font-size: 1rem;
    }
    .remove-item:hover { background: #e05555; color: #fff; border-color: #e05555; transform: scale(1.08); }

    /* Empty cart */
    .cart-single-list h5 { color: #888; font-weight: 500; padding: 20px 0; }

    /* ── Total Box ── */
    .total-amount {
        background: #fff;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 24px rgba(0,0,0,.07);
    }

    /* Coupon */
    .coupon form { display: flex; gap: 10px; flex-wrap: wrap; }
    .coupon input {
        flex: 1;
        min-width: 200px;
        padding: 12px 16px;
        border: 1.5px solid #e0e0ee;
        border-radius: 10px;
        font-size: .9rem;
        outline: none;
        transition: border-color .2s;
    }
    .coupon input:focus { border-color: #e8b86d; }
    .coupon .btn {
        padding: 12px 22px;
        background: #1a1a2e;
        color: #e8b86d;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: .9rem;
        cursor: pointer;
        transition: background .2s, transform .15s;
        white-space: nowrap;
    }
    .coupon .btn:hover { background: #e8b86d; color: #1a1a2e; transform: translateY(-1px); }

    /* Summary list */
    .total-amount .right ul { list-style: none; padding: 0; margin: 0 0 20px; }
    .total-amount .right ul li {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #eee;
        color: #555;
        font-size: .95rem;
    }
    .total-amount .right ul li span { font-weight: 600; color: #1a1a2e; }
    .total-amount .right ul li.last {
        border-bottom: none;
        font-weight: 700;
        font-size: 1.05rem;
        color: #1a1a2e;
        margin-top: 4px;
        padding-top: 14px;
        border-top: 2px solid #1a1a2e;
    }
    .total-amount .right ul li.last span { color: #e8b86d; font-size: 1.1rem; }

    /* Checkout buttons */
    .total-amount .right .button { display: flex; gap: 10px; flex-wrap: wrap; }
    .total-amount .right .button .btn {
        flex: 1;
        text-align: center;
        padding: 13px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: .9rem;
        text-decoration: none;
        transition: all .2s;
        border: 2px solid #1a1a2e;
        white-space: nowrap;
    }
    .total-amount .right .button .btn:not(.btn-alt) {
        background: #1a1a2e;
        color: #e8b86d;
    }
    .total-amount .right .button .btn:not(.btn-alt):hover {
        background: #e8b86d;
        color: #1a1a2e;
    }
    .total-amount .right .button .btn-alt {
        background: transparent;
        color: #1a1a2e;
    }
    .total-amount .right .button .btn-alt:hover {
        background: #1a1a2e;
        color: #e8b86d;
    }
</style>
@endpush

@section('content')
    <!-- Start Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="breadcrumbs-content">
                        <h1 class="page-title">Cart</h1>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <ul class="breadcrumb-nav">
                        <li><a href="index.html"><i class="lni lni-home"></i> Home</a></li>
                        <li><a href="index.html">Shop</a></li>
                        <li>Cart</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Shopping Cart -->
    <div class="shopping-cart section">
        <div class="container">
            <div class="cart-list-head">
                <!-- Cart List Title -->
                <div class="cart-list-title">
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-12"></div>
                        <div class="col-lg-4 col-md-3 col-12">
                            <p>Product Name</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-12">
                            <p>Quantity</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-12">
                            <p>Subtotal</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-12">
                            <p>Discount</p>
                        </div>
                        <div class="col-lg-1 col-md-2 col-12">
                            <p>Remove</p>
                        </div>
                    </div>
                </div>
                <!-- End Cart List Title -->

                @forelse ($items as $item)
                    <div class="cart-single-list">
                        <div class="row align-items-center">
                            <div class="col-lg-1 col-md-1 col-12">
                                <a href="{{ route('front.products.show', $item->product->slug) }}">
                                    <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}">
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-3 col-12">
                                <h5 class="product-name">
                                    <a href="{{ route('front.products.show', $item->product->slug) }}">
                                        {{ $item->product->name }}
                                    </a>
                                </h5>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <div class="count-input">
                                    <form action="{{ route('front.cart.update', $item->product->id) }}" method="POST">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1">
                                        <button type="submit">Update</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p>${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p>—</p>
                            </div>
                            <div class="col-lg-1 col-md-2 col-12">
                                <form action="{{ route('front.cart.destroy', $item->product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="remove-item">
                                        <i class="lni lni-close"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="cart-single-list">
                        <div class="row align-items-center">
                            <div class="col-12 text-center">
                                <h5>Your cart is empty.</h5>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="total-amount">
                        <div class="row">
                            <div class="col-lg-8 col-md-6 col-12">
                                <div class="left">
                                    <div class="coupon">
                                        <form action="#" target="_blank">
                                            <input name="Coupon" placeholder="Enter Your Coupon">
                                            <div class="button">
                                                <button class="btn">Apply Coupon</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="right">
                                    <ul>
                                        <li>Cart Subtotal<span>${{ number_format($total, 2) }}</span></li>
                                        <li>Shipping<span>Free</span></li>
                                        <li>You Save<span>$29.00</span></li>
                                        <li class="last">You Pay<span>$2531.00</span></li>
                                    </ul>
                                    <div class="button">
                                        <a href="{{ route('front.checkout.index') }}" class="btn">Checkout</a>
                                        <a href="{{ route('home') }}" class="btn btn-alt">Continue shopping</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection