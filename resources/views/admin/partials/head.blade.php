<head>

    <meta charset="utf-8" />
    <title>@yield('module') | @yield('action')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin" name="description" />
    <meta content="Admin" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Layout config Js -->
    <script src="{{ asset('admin/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('admin/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Remix Icon (used across admin views) -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Remix Icon fallback: ensure class selectors use the remix font (helps if other CSS overrides) -->
    <style>
        /* Force remixicon classes to use RemixIcon font-family when available */
        .ri, [class^="ri-"], [class*=" ri-"] {
            font-family: 'RemixIcon' !important;
            speak: none;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
    {{-- ckeditor --}}
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

    {{-- Chart js --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/boost.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</head>
