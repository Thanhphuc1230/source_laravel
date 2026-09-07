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
    <link rel="shortcut icon" type="image/x-icon" href="{{ $web->favicon }}" sizes="48x48">
    <link rel="icon" type="image/png" href="{{ $web->favicon }}" sizes="48x48">

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
        <meta property="og:image" content="{{ $web->logo ?? '' }}">
        <meta property="og:image:secure_url" content="{{ $web->logo ?? '' }}">
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
        <meta name="twitter:image" content="{{ $web->logo ?? '' }}">
    @endif

    {{-- Schema JSON-LD --}}
    <!-- STYLESHEETS -->
    <link rel="stylesheet" href="{{ asset('css/image-flip.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/theme-style.css') }}">

<!-- Google Fonts (Playfair Display & Montserrat & Plus Jakarta Sans) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- FontAwesome 6 Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Tailwind CSS Play CDN (Luxury Dark & Gold configuration) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    dark: {
                        950: '#0A0A0A',
                        900: '#0F0F0F', // Deep luxury background
                        850: '#141414',
                        800: '#181818', // Card background
                        700: '#222222', // Hover surface
                        600: '#2A2A2A', // Border color
                    },
                    gold: {
                        50: '#fffdf5',
                        100: '#fef9e7',
                        200: '#fcf0c3',
                        300: '#f9e494',
                        400: '#f3d258',
                        500: '#D4AF37', // Royal Gold Metallic
                        600: '#c59d29',
                        700: '#a37b1c',
                        800: '#84601b',
                        900: '#6f4f1a',
                        950: '#422c09',
                    }
                },
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    serif: ['"Playfair Display"', 'Georgia', 'serif'],
                    heading: ['"Playfair Display"', 'Montserrat', 'serif'],
                }
            }
        }
    }
</script>
