<!DOCTYPE html>
<html lang="vi">
<head>
    @include('frontend.components.head')
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
