<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="facebook-domain-verification" content="nuvqhsy75r0o311c1193p5zc1q6cbz" />
        <title>{{ env("APP_NAME") }}</title>


        <link href="{{ assetC('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ assetC('assets/css/font-awesome.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ assetC('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body class="">
        <div class="container">
            <div class="col-8 offset-2 ">
                <div class="mt-5">
                    <div class="card mt-lg-5 p-3">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </body>
    <!-- Start js -->
    <script src="{{ assetC('assets/js/jquery.js') }}"></script>
    <script src="{{ assetC('assets/js/popper.js') }}"></script>
    <script src="{{ assetC('assets/js/bootstrap.js') }}"></script>
    <!-- Core js -->
    <script src="{{ assetC('assets/js/core.js') }}"></script>
    <script src="{{ assetC('assets/js/script.js') }}"></script>
</html>
