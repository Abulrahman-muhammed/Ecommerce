@extends('admin.layouts.master')

@section('title', 'Product — ' . $product->name)

@push('styles')
<style>
.prod-show {
    font-family: 'Public Sans', sans-serif;
}

/* ══════════════════════════════════════
   PAGE HEADER
══════════════════════════════════════ */
.page-header-card {
    background: linear-gradient(135deg, #696cff 0%, #9155fd 100%);
    border-radius: .5rem;
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(105,108,255,.35);
}
.page-header-card::before {
    content:''; position:absolute;
    top:-40px; right:-30px;
    width:160px; height:160px;
    border-radius:50%; background:rgba(255,255,255,.07);
}
.page-header-card::after {
    content:''; position:absolute;
    bottom:-50px; right:60px;
    width:100px; height:100px;
    border-radius:50%; background:rgba(255,255,255,.05);
}
.page-title { font-size:1.375rem; font-weight:600; color:#fff; margin:0 0 .25rem; }
.page-breadcrumb { font-size:.8125rem; color:rgba(255,255,255,.75); margin:0; }
.page-breadcrumb a { color:rgba(255,255,255,.85); text-decoration:none; }
.page-breadcrumb a:hover { color:#fff; }
.header-actions { display:flex; gap:.625rem; align-items:center; flex-wrap:wrap; position:relative; z-index:1; }

.btn-header-ghost {
    display:inline-flex; align-items:center; gap:.375rem;
    font-size:.8125rem; font-weight:500;
    color:rgba(255,255,255,.9);
    background:rgba(255,255,255,.15);
    border:1px solid rgba(255,255,255,.25);
    border-radius:.375rem; padding:.4375rem 1rem;
    text-decoration:none; transition:all .2s;
    backdrop-filter:blur(4px);
}
.btn-header-ghost:hover { background:rgba(255,255,255,.25); color:#fff; transform:translateY(-1px); }

.btn-header-primary {
    display:inline-flex; align-items:center; gap:.375rem;
    font-size:.8125rem; font-weight:600;
    color:#696cff; background:#fff; border:none;
    border-radius:.375rem; padding:.4375rem 1rem;
    text-decoration:none; transition:all .2s;
    box-shadow:0 2px 8px rgba(0,0,0,.12);
}
.btn-header-primary:hover { color:#5f61e6; box-shadow:0 4px 14px rgba(0,0,0,.18); transform:translateY(-2px); }

.btn-header-danger {
    display:inline-flex; align-items:center; gap:.375rem;
    font-size:.8125rem; font-weight:600;
    color:#fff; background:rgba(234,84,85,.85); border:none;
    border-radius:.375rem; padding:.4375rem 1rem;
    text-decoration:none; transition:all .2s;
    cursor:pointer;
}
.btn-header-danger:hover { background:#ea5455; color:#fff; transform:translateY(-1px); }

/* ══════════════════════════════════════
   STAT CARDS ROW
══════════════════════════════════════ */
.stat-card {
    background:#fff;
    border:1px solid rgba(75,70,92,.08);
    border-radius:.5rem;
    box-shadow:0 2px 6px rgba(75,70,92,.06);
    padding:1.25rem 1.5rem;
    display:flex;
    align-items:center;
    gap:1rem;
    transition:box-shadow .2s, transform .2s;
}
.stat-card:hover { box-shadow:0 4px 18px rgba(75,70,92,.12); transform:translateY(-2px); }

.stat-icon {
    width:50px; height:50px;
    border-radius:.5rem;
    display:flex; align-items:center; justify-content:center;
    font-size:1.375rem;
    flex-shrink:0;
}
.si-purple { background:rgba(105,108,255,.12); color:#696cff; }
.si-green  { background:rgba(40,199,111,.12);  color:#28c76f; }
.si-orange { background:rgba(255,159,67,.12);  color:#ff9f43; }
.si-cyan   { background:rgba(0,207,232,.12);   color:#00cfe8; }
.si-red    { background:rgba(234,84,85,.12);   color:#ea5455; }

.stat-label { font-size:.75rem; color:#a8aaae; font-weight:500; margin:0 0 .2rem; }
.stat-value { font-size:1.375rem; font-weight:700; color:#4b465c; margin:0; line-height:1.2; }
.stat-sub   { font-size:.75rem; color:#a8aaae; margin:.2rem 0 0; }

/* ══════════════════════════════════════
   SECTION CARD
══════════════════════════════════════ */
.sec-card {
    background:#fff;
    border:1px solid rgba(75,70,92,.08);
    border-radius:.5rem;
    box-shadow:0 2px 6px rgba(75,70,92,.06);
    overflow:hidden;
    margin-bottom:1.5rem;
}
.sec-card-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:.875rem 1.25rem;
    border-bottom:1px solid rgba(75,70,92,.07);
    gap:.75rem;
}
.sec-card-title {
    font-size:.875rem; font-weight:700;
    color:#4b465c;
    display:flex; align-items:center; gap:.5rem;
    margin:0;
}
.sec-card-title i { color:#696cff; font-size:1rem; }
.sec-card-body { padding:1.25rem; }

/* ══════════════════════════════════════
   GALLERY
══════════════════════════════════════ */
.gallery-main {
    width:100%; aspect-ratio:4/3;
    border-radius:.375rem;
    object-fit:cover;
    border:1px solid rgba(75,70,92,.1);
    cursor:zoom-in;
    transition:opacity .2s;
}
.gallery-main:hover { opacity:.92; }
.gallery-thumbs {
    display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.75rem;
}
.thumb-wrap {
    position:relative; width:64px; height:64px;
    border-radius:.375rem; overflow:hidden;
    border:2px solid transparent; cursor:pointer;
    flex-shrink:0;
    transition:border-color .2s, transform .2s;
}
.thumb-wrap img { width:100%; height:100%; object-fit:cover; display:block; }
.thumb-wrap:hover, .thumb-wrap.active { border-color:#696cff; transform:scale(1.05); }
.thumb-label {
    position:absolute; bottom:2px; left:50%; transform:translateX(-50%);
    background:#696cff; color:#fff; font-size:.5rem; font-weight:700;
    padding:1px 5px; border-radius:3px; white-space:nowrap;
    letter-spacing:.04em;
}
.no-image-box {
    width:100%; aspect-ratio:4/3;
    border-radius:.375rem;
    background:rgba(105,108,255,.06);
    border:2px dashed rgba(105,108,255,.2);
    display:flex; flex-direction:column;
    align-items:center; justify-content:center;
    color:#696cff; gap:.5rem;
}
.no-image-box i { font-size:2.5rem; opacity:.4; }
.no-image-box span { font-size:.8125rem; color:#a8aaae; }

/* ══════════════════════════════════════
   DETAIL ROWS
══════════════════════════════════════ */
.detail-row {
    display:flex; align-items:flex-start;
    padding:.625rem 0;
    border-bottom:1px solid rgba(75,70,92,.05);
    gap:.75rem;
}
.detail-row:last-child { border-bottom:none; padding-bottom:0; }
.detail-key {
    font-size:.75rem; font-weight:600;
    color:#a8aaae; min-width:110px; flex-shrink:0;
    padding-top:2px;
    text-transform:uppercase; letter-spacing:.04em;
}
.detail-val { font-size:.875rem; color:#4b465c; font-weight:500; flex:1; }

/* ══════════════════════════════════════
   DESCRIPTION
══════════════════════════════════════ */
.desc-box {
    background:#fafafa;
    border:1px solid rgba(75,70,92,.07);
    border-radius:.375rem;
    padding:1rem 1.125rem;
    font-size:.875rem;
    color:#4b465c;
    line-height:1.7;
}
.no-desc { color:#a8aaae; font-style:italic; font-size:.875rem; }

/* ══════════════════════════════════════
   BADGES
══════════════════════════════════════ */
.status-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    font-size:.6875rem; font-weight:700;
    letter-spacing:.05em; text-transform:uppercase;
    border-radius:.25rem; padding:4px 12px;
}
.status-badge .dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
.s-active   { background:rgba(40,199,111,.12);  color:#28c76f; }
.s-active .dot  { background:#28c76f; }
.s-inactive { background:rgba(168,170,174,.12); color:#a8aaae; }
.s-inactive .dot { background:#a8aaae; }
.s-draft    { background:rgba(255,159,67,.12);  color:#ff9f43; }
.s-draft .dot { background:#ff9f43; }
.s-pending  { background:rgba(0,207,232,.12);   color:#00cfe8; }
.s-pending .dot { background:#00cfe8; }
.s-archived { background:rgba(234,84,85,.12);   color:#ea5455; }
.s-archived .dot { background:#ea5455; }

.cat-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    background:rgba(105,108,255,.08); color:#696cff;
    border:1px solid rgba(105,108,255,.18);
    border-radius:.25rem; font-size:.75rem; font-weight:600; padding:3px 9px;
}
.featured-badge {
    display:inline-flex; align-items:center; gap:.25rem;
    background:rgba(255,159,67,.1); color:#ff9f43;
    border:1px solid rgba(255,159,67,.2);
    border-radius:.25rem; font-size:.6875rem; font-weight:700; padding:3px 10px;
}
.slug-chip {
    display:inline-flex; align-items:center; gap:.25rem;
    background:rgba(75,70,92,.06); border:1px solid rgba(75,70,92,.1);
    border-radius:.25rem; font-size:.78rem;
    font-family:'Courier New', monospace; color:#6d6d6d;
    padding:3px 10px; font-weight:500;
}

/* ── Tag chip (show page) ── */
.tag-pill {
    display:inline-flex; align-items:center; gap:.3rem;
    background:#696cff; color:#fff;
    border-radius:.25rem; font-size:.72rem; font-weight:600;
    padding:3px 10px; text-decoration:none;
    transition:background .15s, transform .15s;
}
.tag-pill:hover { background:#5f61e6; color:#fff; transform:translateY(-1px); }

/* ══════════════════════════════════════
   RATING DISPLAY
══════════════════════════════════════ */
.rating-big { display:flex; align-items:center; gap:.75rem; }
.rating-score { font-size:2rem; font-weight:800; color:#4b465c; line-height:1; }
.rating-stars-wrap .stars { display:flex; gap:2px; }
.rating-stars-wrap .star { font-size:.9rem; }
.star.filled { color:#ff9f43; }
.star.half   { color:#ff9f43; opacity:.5; }
.star.empty  { color:#e0e0e0; }
.rating-label { font-size:.75rem; color:#a8aaae; margin-top:2px; }

/* ══════════════════════════════════════
   STOCK BAR
══════════════════════════════════════ */
.stock-display { display:flex; align-items:center; gap:.75rem; }
.stock-big-num { font-size:1.5rem; font-weight:800; line-height:1; }
.stock-big-num.in  { color:#28c76f; }
.stock-big-num.low { color:#ff9f43; }
.stock-big-num.out { color:#ea5455; }
.stock-label { font-size:.75rem; color:#a8aaae; margin-top:3px; }
.stock-progress {
    flex:1; height:8px;
    background:rgba(75,70,92,.08);
    border-radius:50px; overflow:hidden;
}
.stock-progress-fill { height:100%; border-radius:50px; transition:width .6s ease; }
.fill-in  { background:linear-gradient(90deg,#28c76f,#48da89); }
.fill-low { background:linear-gradient(90deg,#ff9f43,#ffb976); }
.fill-out { background:linear-gradient(90deg,#ea5455,#f08182); }

/* ══════════════════════════════════════
   PRICE DISPLAY
══════════════════════════════════════ */
.price-display { display:flex; align-items:baseline; gap:.625rem; flex-wrap:wrap; }
.price-main-big { font-size:1.625rem; font-weight:800; color:#4b465c; }
.price-compare-big { font-size:1rem; color:#a8aaae; text-decoration:line-through; }
.discount-chip {
    background:rgba(40,199,111,.12); color:#28c76f;
    border-radius:50px; font-size:.72rem; font-weight:700;
    padding:2px 8px;
}

/* ══════════════════════════════════════
   TIMESTAMPS
══════════════════════════════════════ */
.ts-row { display:flex; align-items:center; gap:.5rem; font-size:.8125rem; color:#a8aaae; padding:.4rem 0; }
.ts-row i { font-size:.9375rem; color:#696cff; }
.ts-row strong { color:#4b465c; }

/* ══════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════ */
@keyframes fadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}
.anim { animation: fadeUp .35s ease both; }
.anim-1 { animation-delay:.05s; }
.anim-2 { animation-delay:.10s; }
.anim-3 { animation-delay:.15s; }
.anim-4 { animation-delay:.20s; }
.anim-5 { animation-delay:.25s; }
.anim-6 { animation-delay:.30s; }

/* ── SweetAlert ── */
html.swal2-shown,body.swal2-shown { padding-right:0 !important; overflow:auto !important; }
.swal2-container { z-index:9999 !important; }
.swal2-popup { font-family:'Public Sans',sans-serif !important; border-radius:.5rem !important; }
.swal2-title { font-size:1.125rem !important; font-weight:600 !important; color:#4b465c !important; }
.swal2-html-container { font-size:.875rem !important; color:#a8aaae !important; }
</style>
@endpush

@section('content')
@php
    $qty = $product->quantity;
    if ($qty <= 0)       { $stockClass='out'; $fillClass='fill-out'; $stockLabel='Out of Stock'; }
    elseif ($qty <= 10)  { $stockClass='low'; $fillClass='fill-low'; $stockLabel='Low Stock';    }
    else                 { $stockClass='in';  $fillClass='fill-in';  $stockLabel='In Stock';     }
    $fillPct = min(100, ($qty / max($qty, 100)) * 100);

    $discountPct = null;
    if ($product->compare_price && $product->price && $product->compare_price > $product->price)
        $discountPct = round((1 - $product->price / $product->compare_price) * 100);

    $colorMap = ['success'=>'s-active','danger'=>'s-archived','warning'=>'s-draft','info'=>'s-pending','secondary'=>'s-inactive'];
    $statusCls = $colorMap[$product->status->color()] ?? 's-inactive';

    $mainImg     = $product->mainImage;
    $galleryImgs = $product->gallery ?? collect();
    $totalImages = ($mainImg ? 1 : 0) + $galleryImgs->count();
@endphp

<div class="container-xxl flex-grow-1 container-p-y prod-show">

    {{-- ── Page Header ── --}}
    <div class="page-header-card anim">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="page-title">
                    <i class="ri ri-shopping-bag-3-line me-2"></i>{{ $product->name }}
                </h4>
                <p class="page-breadcrumb mb-0">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    <a href="{{ route('admin.products.index') }}">Products</a>
                    <i class="ri ri-arrow-right-s-line mx-1"></i>
                    Product Details
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-header-ghost">
                    <i class="ri ri-arrow-left-line"></i> Back
                </a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-header-primary">
                    <i class="ri ri-pencil-line"></i> Edit
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}"
                      method="POST" class="delete-form m-0 p-0">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-header-danger delete-btn">
                        <i class="ri ri-delete-bin-line"></i> Trash
                    </button>
                </form>
            </div>
        </div>
    </div>

    <x-alert/>

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3 anim anim-1">
            <div class="stat-card">
                <div class="stat-icon si-purple">
                    <i class="ri ri-price-tag-3-line"></i>
                </div>
                <div>
                    <p class="stat-label">Price</p>
                    <p class="stat-value">{{ $product->price ? '$'.number_format($product->price,2) : '—' }}</p>
                    @if($discountPct)
                        <p class="stat-sub" style="color:#28c76f">{{ $discountPct }}% off</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 anim anim-2">
            <div class="stat-card">
                <div class="stat-icon {{ $stockClass === 'in' ? 'si-green' : ($stockClass === 'low' ? 'si-orange' : 'si-red') }}">
                    <i class="ri ri-archive-stack-line"></i>
                </div>
                <div>
                    <p class="stat-label">Stock</p>
                    <p class="stat-value">{{ $qty }}</p>
                    <p class="stat-sub">{{ $stockLabel }}</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 anim anim-3">
            <div class="stat-card">
                <div class="stat-icon si-orange">
                    <i class="ri ri-star-line"></i>
                </div>
                <div>
                    <p class="stat-label">Rating</p>
                    <p class="stat-value">{{ number_format($product->rating,1) }}<span style="font-size:.875rem;color:#a8aaae;font-weight:400"> /5</span></p>
                    <p class="stat-sub">
                        @for($i=1;$i<=5;$i++)
                            <i class="ri ri-star-fill" style="font-size:.65rem;color:{{ $i<=round($product->rating) ? '#ff9f43' : '#e0e0e0' }}"></i>
                        @endfor
                    </p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 anim anim-4">
            <div class="stat-card">
                <div class="stat-icon si-cyan">
                    <i class="ri ri-checkbox-circle-line"></i>
                </div>
                <div>
                    <p class="stat-label">Status</p>
                    <p class="stat-value" style="font-size:1rem;margin-top:.25rem">
                        <span class="status-badge {{ $statusCls }}">
                            <span class="dot"></span>{{ $product->status->label() }}
                        </span>
                    </p>
                    @if($product->is_featured)
                        <p class="stat-sub" style="margin-top:.3rem">
                            <span class="featured-badge">
                                <i class="ri ri-star-fill" style="font-size:.65rem"></i> Featured
                            </span>
                        </p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ── Main Grid ── --}}
    <div class="row g-4">

        {{-- LEFT: Images + Timestamps --}}
        <div class="col-lg-4 anim anim-2">

            <div class="sec-card">
                <div class="sec-card-header">
                    <h6 class="sec-card-title"><i class="ri ri-image-2-line"></i> Product Images</h6>
                    <span style="font-size:.75rem;color:#a8aaae">{{ $totalImages }} image(s)</span>
                </div>
                <div class="sec-card-body">
                    @php
                        $allImgs = collect();
                        if ($mainImg) $allImgs->push($mainImg);
                        foreach ($galleryImgs as $gi) $allImgs->push($gi);
                    @endphp

                    @if($allImgs->count())
                        <img id="main-gallery-img"
                             src="{{ asset('storage/' . $allImgs->first()->path) }}"
                             alt="{{ $product->name }}"
                             class="gallery-main">
                        <div class="gallery-thumbs">
                            @foreach($allImgs as $i => $img)
                                <div class="thumb-wrap {{ $i === 0 ? 'active' : '' }}"
                                     onclick="switchImg(this, '{{ asset('storage/' . $img->path) }}')">
                                    <img src="{{ asset('storage/' . $img->path) }}" alt="img {{ $i+1 }}">
                                    @if($mainImg && $img->id === $mainImg->id)
                                        <span class="thumb-label">Main</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-image-box">
                            <i class="ri ri-image-add-line"></i>
                            <span>No images uploaded</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="sec-card anim anim-3">
                <div class="sec-card-header">
                    <h6 class="sec-card-title"><i class="ri ri-time-line"></i> Timestamps</h6>
                </div>
                <div class="sec-card-body" style="padding:.875rem 1.25rem">
                    <div class="ts-row">
                        <i class="ri ri-add-circle-line"></i>
                        <span>Created <strong>{{ $product->created_at->format('d M Y, h:i A') }}</strong></span>
                    </div>
                    <div class="ts-row">
                        <i class="ri ri-edit-circle-line"></i>
                        <span>Updated <strong>{{ $product->updated_at->format('d M Y, h:i A') }}</strong></span>
                    </div>
                    <div class="ts-row">
                        <i class="ri ri-timer-line"></i>
                        <span style="color:#a8aaae">{{ $product->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT: Details --}}
        <div class="col-lg-8">

            {{-- Product Information --}}
            <div class="sec-card anim anim-3">
                <div class="sec-card-header">
                    <h6 class="sec-card-title"><i class="ri ri-information-line"></i> Product Information</h6>
                </div>
                <div class="sec-card-body">

                    <div class="detail-row">
                        <span class="detail-key">Name</span>
                        <span class="detail-val" style="font-weight:700;font-size:.9375rem;color:#4b465c">{{ $product->name }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-key">Slug</span>
                        <span class="detail-val">
                            <span class="slug-chip"><i class="ri ri-link-m"></i>{{ $product->slug }}</span>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-key">Category</span>
                        <span class="detail-val">
                            @if($product->category)
                                <span class="cat-badge">
                                    <i class="ri ri-price-tag-3-line"></i>
                                    {{ $product->category->name }}
                                </span>
                            @else
                                <span style="color:#a8aaae">— Uncategorized</span>
                            @endif
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-key">Status</span>
                        <span class="detail-val">
                            <span class="status-badge {{ $statusCls }}">
                                <span class="dot"></span>{{ $product->status->label() }}
                            </span>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-key">Featured</span>
                        <span class="detail-val">
                            @if($product->is_featured)
                                <span class="featured-badge">
                                    <i class="ri ri-star-fill" style="font-size:.65rem"></i> Yes, Featured
                                </span>
                            @else
                                <span style="color:#a8aaae">No</span>
                            @endif
                        </span>
                    </div>

                    {{-- ── Tags ── --}}
                    <div class="detail-row">
                        <span class="detail-key">Tags</span>
                        <span class="detail-val">
                            @if($product->tags->isEmpty())
                                <span style="color:#a8aaae">— No tags</span>
                            @else
                                <div style="display:flex;flex-wrap:wrap;gap:.375rem">
                                    @foreach($product->tags as $tag)
                                        <a href="{{ route('admin.tags.edit', $tag->id) }}" class="tag-pill">
                                            <i class="ri ri-price-tag-3-line" style="font-size:.7rem"></i>
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </span>
                    </div>

                </div>
            </div>

            {{-- Pricing --}}
            <div class="sec-card anim anim-4">
                <div class="sec-card-header">
                    <h6 class="sec-card-title"><i class="ri ri-money-dollar-circle-line"></i> Pricing</h6>
                </div>
                <div class="sec-card-body">
                    @if($product->price)
                        <div class="price-display mb-3">
                            <span class="price-main-big">${{ number_format($product->price, 2) }}</span>
                            @if($product->compare_price)
                                <span class="price-compare-big">${{ number_format($product->compare_price, 2) }}</span>
                            @endif
                            @if($discountPct)
                                <span class="discount-chip">{{ $discountPct }}% OFF</span>
                            @endif
                        </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="detail-row" style="padding-top:0">
                                <span class="detail-key">Sale Price</span>
                                <span class="detail-val" style="font-weight:700">
                                    {{ $product->price ? '$'.number_format($product->price,2) : '—' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="detail-row" style="padding-top:0">
                                <span class="detail-key">Compare At</span>
                                <span class="detail-val" style="text-decoration:{{ $product->compare_price ? 'line-through' : 'none' }};color:#a8aaae">
                                    {{ $product->compare_price ? '$'.number_format($product->compare_price,2) : '—' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stock & Rating --}}
            <div class="row g-4 anim anim-5">

                <div class="col-md-6">
                    <div class="sec-card">
                        <div class="sec-card-header">
                            <h6 class="sec-card-title"><i class="ri ri-archive-stack-line"></i> Stock</h6>
                        </div>
                        <div class="sec-card-body">
                            <div class="stock-display mb-3">
                                <div>
                                    <div class="stock-big-num {{ $stockClass }}">{{ $qty }}</div>
                                    <div class="stock-label">{{ $stockLabel }}</div>
                                </div>
                                <div class="stock-progress">
                                    <div class="stock-progress-fill {{ $fillClass }}"
                                         style="width:{{ $fillPct }}%"></div>
                                </div>
                            </div>
                            @if($qty <= 10 && $qty > 0)
                                <div style="background:rgba(255,159,67,.08);border:1px solid rgba(255,159,67,.2);border-radius:.375rem;padding:.625rem .875rem;font-size:.8125rem;color:#ff9f43;">
                                    <i class="ri ri-alert-line me-1"></i> Low stock — consider restocking soon.
                                </div>
                            @elseif($qty == 0)
                                <div style="background:rgba(234,84,85,.08);border:1px solid rgba(234,84,85,.2);border-radius:.375rem;padding:.625rem .875rem;font-size:.8125rem;color:#ea5455;">
                                    <i class="ri ri-close-circle-line me-1"></i> Out of stock — product unavailable.
                                </div>
                            @else
                                <div style="background:rgba(40,199,111,.08);border:1px solid rgba(40,199,111,.2);border-radius:.375rem;padding:.625rem .875rem;font-size:.8125rem;color:#28c76f;">
                                    <i class="ri ri-checkbox-circle-line me-1"></i> Stock level is healthy.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="sec-card">
                        <div class="sec-card-header">
                            <h6 class="sec-card-title"><i class="ri ri-star-line"></i> Rating</h6>
                        </div>
                        <div class="sec-card-body">
                            <div class="rating-big mb-3">
                                <span class="rating-score">{{ number_format($product->rating,1) }}</span>
                                <div class="rating-stars-wrap">
                                    <div class="stars">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="ri ri-star-fill star {{ $i<=round($product->rating) ? 'filled' : 'empty' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="rating-label">out of 5.0</div>
                                </div>
                            </div>
                            @foreach([5,4,3,2,1] as $star)
                                @php $w = $star <= round($product->rating) ? min(100, ($product->rating/5)*100+($star-2)*5) : max(0, ($product->rating/5)*100-(5-$star)*15); $w = max(0,min(100,$w)); @endphp
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span style="font-size:.7rem;color:#a8aaae;width:8px">{{ $star }}</span>
                                    <i class="ri ri-star-fill" style="font-size:.65rem;color:#ff9f43"></i>
                                    <div style="flex:1;height:6px;background:rgba(75,70,92,.08);border-radius:50px;overflow:hidden">
                                        <div style="height:100%;width:{{ $w }}%;background:#ff9f43;border-radius:50px;transition:width .6s ease"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- Description --}}
            <div class="sec-card mt-4 anim anim-6">
                <div class="sec-card-header">
                    <h6 class="sec-card-title"><i class="ri ri-file-text-line"></i> Description</h6>
                </div>
                <div class="sec-card-body">
                    @if($product->description)
                        <div class="desc-box">{{ $product->description }}</div>
                    @else
                        <p class="no-desc">No description provided.</p>
                    @endif
                </div>
            </div>

        </div>{{-- /col-lg-8 --}}
    </div>{{-- /row --}}

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function switchImg(wrap, url) {
    document.getElementById('main-gallery-img').src = url;
    document.querySelectorAll('.thumb-wrap').forEach(t => t.classList.remove('active'));
    wrap.classList.add('active');
}

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('.delete-form');
        Swal.fire({
            title: 'Move to Trash?',
            html: `<p style="color:#a8aaae;font-size:.875rem;margin:0">
                       This product will be moved to the
                       <strong style="color:#696cff">Trash</strong>.
                       You can restore it anytime.
                   </p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri ri-delete-bin-line me-1"></i> Yes, Move to Trash',
            cancelButtonText: 'Cancel',
            backdrop: true,
            allowOutsideClick: false,
            scrollbarPadding: false,
            didOpen: () => { document.body.style.paddingRight = '0px'; },
            customClass: {
                confirmButton: 'btn btn-danger me-2 waves-effect waves-light',
                cancelButton:  'btn btn-label-secondary waves-effect'
            },
            buttonsStyling: false
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});
</script>
@endpush