<!--=============== basic  ===============-->
<meta charset="utf-8">
<title>@yield('module')</title>
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="@yield('description')">
<meta name="keywords" content="@yield('keywords')">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="{{ $website ? asset('images/logo/' . $website->favicon) : '' }}"
    sizes="48x48">
<link rel="icon" type="image/png" href="{{ $website ? asset('images/logo/' . $website->favicon) : '' }}"
    sizes="48x48">

<!-- CSS (Font, Vendor, Icon, Plugins & Style CSS files) -->
<link rel="preload" href="@yield('images')" as="image">
<link rel="alternate" hreflang="x-default" href="{{ route('web.home') }}">
<link rel="alternate" hreflang="vi" href="{{ route('web.home') }}">
<link rel="canonical" href="{{ request()->fullUrl() }}">

<!-- fonts -->
<meta property="og:locale" content="vi_VN">
<meta property="og:type" content="{{ $website ? $website->meta_name : '' }}">
<meta property="og:site_name" content="{{ $website ? $website->meta_name : '' }}">
<meta property="og:image" content="@yield('images')">
<meta property="og:image:alt" content="@yield('images')">
<meta property="og:title" content="@yield('module')">
<meta property="og:description" content="@yield('description')">
<meta property="og:image" content="{{ $website ? asset('images/logo/' . $website->favicon) : '' }}">
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:image:width" content="300">
<meta property="og:image:height" content="300">
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Alpine.js for interactivity -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

@stack('styles')
