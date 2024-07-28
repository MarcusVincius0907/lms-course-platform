<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="author" content="Rumon Prince Sohan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="copyright"content="{{ env('APP_NAME') }}">
    <meta name="subject" content="{{ env('APP_NAME') }} {{ env('APP_VERSION') }}">
    <meta name="description" content="@yield('meta_description', config('app.name'))">
    <meta name="author" content="@yield('meta_author', config('app.name'))">
    <meta name="facebook-domain-verification" content="nuvqhsy75r0o311c1193p5zc1q6cbz" />

    <title>{{getSystemSetting('type_name')->value}}</title>


    <!-- Favicon -->
    <link rel="icon" sizes="16x16" href="{{ filePath(getSystemSetting('favicon_icon')->value) }}">
    <link href="{{ assetC('css/font.css') }}">
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ assetC('frontend/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ assetC('frontend/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ assetC('frontend/css/line-awesome.css') }}">
    <link rel="stylesheet" href="{{ assetC('frontend/css/animate.css') }}">
    <link rel="stylesheet" href="{{ assetC('frontend/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ assetC('frontend/css/owl.theme.default.css') }}">
    <link rel="stylesheet" href="{{ assetC('frontend/css/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ assetC('assets/plugins/datatables/dataTables.bootstrap4.css') }}">

    <link rel="stylesheet" href="{{ assetC('frontend/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ assetC('frontend/css/fancybox.css') }}">
    <link rel="stylesheet" href="{{ assetC('frontend/css/tooltipster.bundle.css') }}">
    <link rel="stylesheet" href="{{ assetC('frontend/css/style.css') }}">
    <link href="{{ assetC('css/frontend.css') }}">

    <!-- end inject -->
</head>

<body>

<!-- start cssload-loader -->
<div class="preloader">
    <div class="loader">
        <svg class="spinner" viewBox="0 0 50 50">
            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
        </svg>
    </div>
</div>
<!-- end cssload-loader -->

<!--======================================
        START HEADER AREA
    ======================================-->
<header class="header-menu-area">
    <div class="header-menu-fluid">

        @include('frontend.include.top')
        @include('frontend.include.navbar')
    </div><!-- end header-menu-fluid -->
</header>


<!-- end header-menu-area -->
<!--======================================
        END HEADER AREA
======================================-->


@yield('content')


@include('frontend.include.footer')



<!-- start scroll top -->
<div id="scroll-top">
    <i class="fa fa-angle-up" title="@translate(Go top)"></i>
</div>
<!-- end scroll top -->

<!-- template js files -->
<script src="{{ assetC('frontend/js/jquery.js') }}"></script>
<script src="{{ assetC('frontend/js/popper.js') }}"></script>
<script src="{{ assetC('frontend/js/bootstrap.js') }}"></script>
<script src="{{ assetC('frontend/js/bootstrap-select.js') }}"></script>
<script src="{{ assetC('frontend/js/owl.carousel.js') }}"></script>
<script src="{{ assetC('frontend/js/magnific-popup.js') }}"></script>
<script src="{{ assetC('frontend/js/isotope.js') }}"></script>
<script src="{{ assetC('frontend/js/waypoint.js') }}"></script>
<script src="{{ assetC('frontend/js/jquery.counterup.js') }}"></script>
<script src="{{ assetC('frontend/js/particles.js') }}"></script>
<script src="{{ assetC('frontend/js/particlesRun.js') }}"></script>
<script src="{{ assetC('frontend/js/fancybox.js') }}"></script>
<script src="{{ assetC('frontend/js/wow.js') }}"></script>
<script src="{{ assetC('frontend/js/date-time-picker.js') }}"></script>
<script src="{{ assetC('frontend/js/jquery.filer.js') }}"></script>
<script src="{{ assetC('frontend/js/emojionearea.js') }}"></script>
<script src="{{ assetC('frontend/js/smooth-scrolling.js') }}"></script>
<script src="{{ assetC('frontend/js/tooltipster.bundle.js') }}"></script>
<script src="{{ assetC('frontend/js/main.js') }}"></script>
<script src="{{ assetC('frontend/js/main.js') }}"></script>
<script src="{{ assetC('assets/plugins/datatables/dataTables.bootstrap4.js') }}"></script>
<script src="{{ assetC('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ assetC('js/jquery.lazyload.min.js') }}"></script>
<script src="{{ assetC('frontend/js/custom.js') }}"></script>
<script src="{{ assetC('js/frontend.js') }}"></script>
<script src="{{ assetC('js/notify.js') }}"></script>

@include('layouts.modal')

@include('sweetalert::alert')
@yield('js')

</body>

</html>
