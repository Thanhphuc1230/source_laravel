<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('frontend.partials.head')
    @yield('styles')
</head>
<body class="bg-[#FAFAFA] text-slate-800 antialiased font-sans flex flex-col min-h-screen selection:bg-gold-500 selection:text-white">

    <!-- Header Navbar -->
    @include('frontend.partials.header')

    <!-- Main Content Container -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Area -->
    @include('frontend.partials.footer')

    <!-- Toast Notification Container (Fixed top-right) -->
    <div id="toast-container" class="fixed top-24 right-4 z-50 flex flex-col space-y-3 pointer-events-none" style="width: 320px;"></div>

    @include('frontend.partials.contact_buttons')

    <!-- Scripts Area -->
    @include('frontend.partials.script')

    <script>
        window.addToCartUrl = "{{ url('/add-to-cart') }}";
    </script>
    <script src="{{ asset('js/cart.js') }}"></script>
</body>
</html>
