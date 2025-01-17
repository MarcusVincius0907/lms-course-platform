<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
          content="@yield('meta_keywords',config('app.name'))">
    <meta name="description" content="@yield('meta_description', config('app.name'))">
    <meta name="author" content="@yield('meta_author', config('app.name'))">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="copyright"content="{{ env('APP_NAME') }}">
    <meta name="subject" content="{{ env('APP_NAME') }} {{ env('APP_VERSION') }}">
    <meta name="facebook-domain-verification" content="nuvqhsy75r0o311c1193p5zc1q6cbz" />
    <title>{{ config('app.name') }} - @yield('title')</title>
    <!-- Favicon -->
    <link rel="icon" sizes="16x16" href="{{ filePath(getSystemSetting('favicon_icon')->value) }}">
    <!-- Start css -->
    <link href="{{assetC('assets/plugins/select2/select2.css')}}" rel="stylesheet" type="text/css">
    <!-- Apex css -->
    <link href="{{assetC('assets/plugins/apexcharts/apexcharts.css')}}" rel="stylesheet">
    <!-- Summernote css -->
    <link href="{{assetC('assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <!-- Start css -->
    <!-- Switchery css -->
    <link href="{{assetC('assets/plugins/switchery/switchery.css')}}" rel="stylesheet">
    <!-- Slick css -->
    <link href="{{ assetC('assets/plugins/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ assetC('assets/plugins/slick/slick-theme.css') }}" rel="stylesheet">
    <link href="{{assetC('assets/plugins/pnotify/css/pnotify.custom.css')}}" rel="stylesheet" type="text/css">
    <link href="{{assetC('assets/plugins/colorpicker/bootstrap-colorpicker.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ assetC('css/dropify.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{assetC('asset_rumbok/css/icofont.css')}}">

    <link href="{{ assetC('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    @yield('css-link')

    <link href="{{ assetC('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ assetC('assets/css/flag-icon.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ assetC('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ assetC('assets/css/custom.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ assetC('assets/css/video-js.css')  }}">
    <!-- End css -->
    @notifyCss
    @yield('page-style')

    


</head>
<body class="vertical-layout">


<!-- Start Containerbar -->
<div id="containerbar">

    <!-- Start Leftbar -->
@include('layouts.leftsidebar')
<!-- End Leftbar -->

    <!-- Start Rightbar -->
    <div class="rightbar">

        <!-- Start Topbar -->
    @include('layouts.topbar')
    <!-- End Topbar -->

        <!-- Start Breadcrumbbar -->
        <div class="breadcrumbbar">
        </div>

    @include('layouts.error')
    <!-- End Breadcrumbbar -->

        <!-- Start Contentbar -->
        <div class="contentbar">

            @yield('content')

        </div>
        <!-- End Contentbar -->

        <!-- Start Footerbar -->
    @include('layouts.footer')
    <!-- End Footerbar -->

        <!--Modal or Delete Modal-->
        @include('layouts.modal')
        @include('layouts.delete')


        <div id="mySidenav" class="sidenav shadow rounded">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div id="master_media_section"></div>
        </div>

        <input type="hidden" value="{{ env('APP_URL') }}" id="domain_name">

    </div>
    <!-- End Rightbar -->
</div>
<!-- End Containerbar -->
<!-- Start js -->
<script src="{{ assetC('assets/js/jquery.js') }}"></script>
<script src="{{ assetC('assets/js/popper.js') }}"></script>
<script src="{{ assetC('assets/js/bootstrap.js') }}"></script>
<script src="{{ assetC('assets/js/modernizr.js') }}"></script>
<script src="{{ assetC('assets/js/detect.js') }}"></script>
<script src="{{ assetC('assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ assetC('assets/js/vertical-menu.js') }}"></script>
<!-- Switchery js -->
<script src="{{assetC('assets/plugins/switchery/switchery.js')}}"></script>
<script src="{{assetC('assets/js/custom/custom-switchery.js')}}"></script>
<!-- Apex js -->
<script src="{{ assetC('assets/plugins/apexcharts/apexcharts.js') }}"></script>
<script src="{{ assetC('assets/plugins/apexcharts/irregular-data-series.js') }}"></script>
<!-- Chart js -->
<script src="{{assetC('assets/plugins/chart.js/chart.js')}}"></script>
<script src="{{assetC('assets/plugins/chart.js/chart-bundle.js')}}"></script>
<script src="{{assetC('assets/js/custom/custom-chart-chartjs.js')}}"></script>
<!-- Slick js -->
<script src="{{ assetC('assets/plugins/slick/slick.js') }}"></script>
<!-- Select2 js -->
<script src="{{assetC('assets/plugins/select2/select2.js')}}"></script>
<!-- Summernote JS -->
<script src="{{assetC('assets/plugins/summernote/summernote-bs4.js')}}"></script>

<!-- Pnotify js -->
<script src="{{assetC('assets/plugins/pnotify/js/pnotify.custom.js')}}"></script>
<script src="{{assetC('assets/plugins/colorpicker/bootstrap-colorpicker.js')}}"></script>
<script src="{{assetC('assets/js/custom/custom-pnotify.js')}}"></script>


@yield('js-link')

<!-- Custom Dashboard js -->
<script src="{{ assetC('assets/js/custom/custom-dashboard.js') }}"></script>
<!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
<script src="{{ assetC('assets/js/videojs-ie8.min.js') }}"></script>
<script src="{{ assetC('assets/js/video.js') }}"></script>
<script src="{{ assetC('js/dropify.js') }}"></script>
<!-- Core js -->
<script src="{{ assetC('assets/js/core.js') }}"></script>
<script src="{{ assetC('assets/js/script.js') }}"></script>




<!-- End js -->

@include('sweetalert::alert')
@include('notify::messages')

@notifyJs
@yield('page-script')

 <script>
        $(document).ready(function(){

            "use strict"

        try {
            $.post('{{ route('media.slide') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#master_media_section').html(data);
            });
        } catch (error) {
            location.reload();
        }


        });

        $('.dropify').dropify();

        $('#datetimepicker1').datetimepicker();

    </script>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
