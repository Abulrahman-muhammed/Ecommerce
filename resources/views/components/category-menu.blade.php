<div class="mega-category-menu">
    <span class="cat-button"><i class="lni lni-menu"></i>All Categories</span>
    <ul class="sub-category">
        @foreach ($parentCategories ?? [] as $category)
        <li><a href="product-grids.html">{{ $category->name }} <i class="lni lni-chevron-right"></i></a>
            <ul class="inner-sub-category">
                    @foreach ($category->children as $child)
                    <li><a href="#">{{ $child->name }} </a>
                    @endforeach
            </ul>
        </li>
        @endforeach
            
    </ul>
</div>