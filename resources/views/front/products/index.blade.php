@extends('front.layouts.app')

@section('title', 'Products')

@section('content')

<div class="breadcrumbs">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="breadcrumbs-content">
                    <h1 class="page-title">Shop Grid</h1>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
                <ul class="breadcrumb-nav">
                    <li><a href="{{ route('home') }}"><i class="lni lni-home"></i> Home</a></li>
                    <li>Shop Grid</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="product-grids section">
    <div class="container">

        {{-- form واحدة بتغطي الـ sidebar والـ grid كلهم --}}
        <form method="GET" action="{{ route('front.products.index') }}" id="filter-form">

            {{-- hidden input للـ category لأن الكاتيجوري بيجي من links مش من form fields --}}
            @if(!empty($filters['category']))
                <input type="hidden" name="category" value="{{ $filters['category'] }}">
            @endif

            <div class="row">

                {{-- SIDEBAR --}}
                <div class="col-lg-3 col-12">
                    <div class="product-sidebar">

                        {{-- Search --}}
                        <div class="single-widget search">
                            <h3>Search Product</h3>
                            <form method="GET" action="{{ route('front.products.index') }}">
                                {{-- حفظ الفلاتر التانية --}}
                                @foreach(['category', 'sort', 'min_price', 'max_price'] as $k)
                                    @if(request($k))
                                        <input type="hidden" name="{{ $k }}" value="{{ request($k) }}">
                                    @endif
                                @endforeach
                                <input type="text"
                                       name="search"
                                       placeholder="Search Here..."
                                       value="{{ $filters['search'] ?? '' }}">
                                <button type="submit"><i class="lni lni-search-alt"></i></button>
                            </form>
                        </div>

                        {{-- Categories — links عادية بتحفظ باقي الفلاتر --}}
                        <div class="single-widget">
                            <h3>All Categories</h3>
                            <ul class="list">
                                <li>
                                    <a href="{{ route('front.products.index', request()->except('category', 'page')) }}"
                                       class="{{ empty($filters['category']) ? 'active' : '' }}">
                                        All
                                    </a>
                                </li>
                                @foreach ($categories as $cat)
                                    <li>
                                        <a href="{{ route('front.products.index', array_merge(request()->except('category', 'page'), ['category' => $cat->id])) }}"
                                           class="{{ ($filters['category'] ?? '') == $cat->id ? 'active' : '' }}">
                                            {{ $cat->name }}
                                        </a>
                                        <span>({{ $cat->products_count }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Price Range --}}
                        <div class="single-widget range">
                            <h3>Price Range</h3>
                            {{-- الـ search والـ sort محفوظين كـ hidden --}}
                            @foreach(['search', 'sort', 'category'] as $k)
                                @if(request($k))
                                    <input type="hidden" name="{{ $k }}" value="{{ request($k) }}">
                                @endif
                            @endforeach
                            <div class="row g-2 mt-1">
                                <div class="col-6">
                                    <input type="number"
                                           name="min_price"
                                           class="form-control form-control-sm"
                                           placeholder="Min $"
                                           value="{{ $filters['min_price'] ?? '' }}"
                                           min="0">
                                </div>
                                <div class="col-6">
                                    <input type="number"
                                           name="max_price"
                                           class="form-control form-control-sm"
                                           placeholder="Max $"
                                           value="{{ $filters['max_price'] ?? '' }}"
                                           min="0">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">
                                Apply Price
                            </button>
                        </div>

                    </div>
                </div>

                {{-- PRODUCT GRID --}}
                <div class="col-lg-9 col-12">
                    <div class="product-grids-head">

                        <div class="product-grid-topbar">
                            <div class="row align-items-center">
                                <div class="col-lg-7 col-md-8 col-12">
                                    <div class="product-sorting">
                                        <label for="sort">Sort by:</label>
                                        {{--
                                            الـ select جوه form منفصلة بتحفظ كل الفلاتر
                                            لأن onchange بيعمل submit فمحتاج hidden inputs
                                        --}}
                                        <form method="GET" action="{{ route('front.products.index') }}" id="sort-form" style="display:contents">
                                            @foreach(request()->except('sort', 'page') as $k => $v)
                                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                            @endforeach
                                            <select class="form-control"
                                                    id="sort"
                                                    name="sort"
                                                    onchange="this.form.submit()">
                                                <option value="latest"     {{ $sort === 'latest'     ? 'selected' : '' }}>Newest</option>
                                                <option value="price_asc"  {{ $sort === 'price_asc'  ? 'selected' : '' }}>Price: Low → High</option>
                                                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                                                <option value="rating"     {{ $sort === 'rating'     ? 'selected' : '' }}>Average Rating</option>
                                                <option value="name_asc"   {{ $sort === 'name_asc'   ? 'selected' : '' }}>A → Z</option>
                                                <option value="name_desc"  {{ $sort === 'name_desc'  ? 'selected' : '' }}>Z → A</option>
                                            </select>
                                        </form>
                                        <h3 class="total-show-product">
                                            Showing: <span>{{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane fade show active">
                                <div class="row">
                                    @forelse ($products as $product)
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <x-front.product-card :product="$product" />
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5">
                                            <i class="lni lni-search-alt fs-1 text-muted"></i>
                                            <h4 class="mt-3">No Products Found</h4>
                                            <a href="{{ route('front.products.index') }}" class="btn btn-primary mt-2">
                                                Clear Filters
                                            </a>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="pagination left">
                                            {{ $products->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>

    </div>
</section>

@endsection