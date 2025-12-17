<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Nupex - Premium Research Peptides')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased bg-white" x-data="{ mobileMenuOpen: false, searchOpen: false }">
    
    <!-- Announcement Bar -->
    @include('frontend.components.announcement-bar')
    
    <!-- Header -->
    @include('frontend.components.header')
    
    <!-- Mobile Menu -->
    @include('frontend.components.mobile-menu')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('frontend.components.footer')
    
    <!-- Chat Button -->
    @include('frontend.components.chat-button')
    
    @stack('scripts')
</body>
</html>
