<!doctype html>
<html class="no-js" lang="en">

<head>
    @include('frontend.partials.head')
</head>

<body>
    <!-- Start Header Area -->
    @include('frontend.partials.header')
    <!-- end Header Area -->
    <main>
        @yield('content')
    </main>
    <!-- Scroll to top start -->
    <div class="scroll-top not-visible">
        <i class="fa fa-angle-up"></i>
    </div>
    <!-- Scroll to Top End -->

    <!-- footer area start -->
    @include('frontend.partials.footer')
    <!-- footer area end -->

    <!-- Quick view modal start -->
    @include('frontend.partials.quick_view')
    <!-- Quick view modal end -->

    <!-- offcanvas mini cart start -->
    @include('frontend.partials.cart_mini')
    <!-- offcanvas mini cart end -->

    <!-- JS ============================================ -->
    @include('frontend.partials.js')
    @include('sweetalert::alert')
    @stack('scripts')
</body>

</html>
