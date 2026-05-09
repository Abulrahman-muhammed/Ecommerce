<!DOCTYPE html>
<html class="no-js" lang="zxx">

    @include('front.partials.head')

<body>


    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    <!-- Start Header Area -->
    @include('front.partials.header')
    <!-- End Header Area -->
    @yield('content')

    <!-- Start Footer Area -->
    @include('front.partials.footer')
    <!--/ End Footer Area -->

    <!-- ========================= scroll-top ========================= -->
    <a href="#" class="scroll-top">
        <i class="lni lni-chevron-up"></i>
    </a>

    <!-- ========================= JS here ========================= -->
    @include('front.partials.scripts')
</body>

</html>