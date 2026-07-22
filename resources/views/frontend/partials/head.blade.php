<!--=============== basic  ===============-->
    <meta charset="utf-8">
    <title>@yield('module')</title>
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/' . $web->favicon) }}" sizes="48x48">
    <link rel="icon" type="image/png" href="{{ asset('images/logo/' . $web->favicon) }}" sizes="48x48">

    <!-- CSS (Font, Vendor, Icon, Plugins & Style CSS files) -->
    <link rel="preload" href="@yield('images')" as="image">
    <link rel="alternate" hreflang="x-default" href="{{ route('web.home') }}">
    <link rel="alternate" hreflang="vi" href="{{ route('web.home') }}">
    <link rel="canonical" href="{{ request()->fullUrl() }}">

    <!-- Open Graph / Meta Facebook & Zalo & Viber -->
    <meta property="og:locale" content="vi_VN">
    <meta property="og:type" content="{{ $web->meta_name ?? 'website' }}">
    <meta property="og:site_name" content="{{ $web->meta_name ?? 'Base' }}">
    <meta property="og:title" content="@yield('module')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:url" content="{{ request()->fullUrl() }}">

    @if(View::hasSection('images') && trim(View::yieldContent('images')) != '')
        <meta property="og:image" content="@yield('images')">
        <meta property="og:image:secure_url" content="@yield('images')">
        <meta property="og:image:alt" content="@yield('module')">
    @else
        <meta property="og:image" content="{{ asset($web->logo ? 'images/logo/' . $web->logo : '') }}">
        <meta property="og:image:secure_url" content="{{ asset($web->logo ? 'images/logo/' . $web->logo : '') }}">
        <meta property="og:image:alt" content="{{ $web->name_vn ?? 'Base' }}">
    @endif
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('module')">
    <meta name="twitter:description" content="@yield('description')">
    @if(View::hasSection('images') && trim(View::yieldContent('images')) != '')
        <meta name="twitter:image" content="@yield('images')">
    @else
        <meta name="twitter:image" content="{{ asset($web->logo ? 'images/logo/' . $web->logo : '') }}">
    @endif

    {{-- Schema JSON-LD --}}
    @include('frontend.partials.schema')
    <!-- STYLESHEETS -->

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">

<!-- FontAwesome 6 Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Tailwind CSS Play CDN (V4 style configuration) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    emerald: {
                        50: '#f0fdf4',
                        100: '#dcfce7',
                        200: '#bbf7d0',
                        300: '#86efac',
                        400: '#4ade80',
                        500: '#22c55e',
                        600: '#16a34a',
                        700: '#15803d',
                        800: '#166534',
                        900: '#14532d',
                        950: '#064e3b', // Deep green accent
                    },
                    gold: {
                        50: '#fffbeb',
                        100: '#fef3c7',
                        200: '#fde68a',
                        300: '#fcd34d',
                        400: '#fbbf24',
                        500: '#f59e0b', // Amber/gold highlight
                        600: '#d97706',
                        700: '#b45309',
                        800: '#92400e',
                        900: '#78350f',
                    }
                },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    heading: ['Montserrat', 'sans-serif'],
                }
            }
        }
    }
</script>

<style>
    :root {
        --primary-color: #064e3b;
        --accent-color: #f59e0b;
        --font-heading: 'Montserrat', sans-serif;
        --font-body: 'Inter', sans-serif;
    }
    body {
        font-family: var(--font-body);
    }
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-heading);
    }
    .zoom-effect {
        overflow: hidden;
    }
    .zoom-effect img {
        transition: transform 0.5s ease;
    }
    .zoom-effect:hover img {
        transform: scale(1.06);
    }
</style>
