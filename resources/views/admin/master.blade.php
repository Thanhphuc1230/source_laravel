<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

@include('admin.partials.head')

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('admin.partials.header')

        <!-- ========== App Menu ========== -->
        @include('admin.partials.navbar')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            @yield('content')
            <!-- End Page-content -->

            @include('admin.partials.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    @include('admin.partials.js')
    @stack('scripts')

    <script>
        document.getElementById('create-sitemap-btn').addEventListener('click', function() {
            fetch('{{ route('admin.sitemap.generate') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => alert('Có lỗi xảy ra!'));
        });
    </script>
    @include('sweetalert::alert')
</body>

</html>
