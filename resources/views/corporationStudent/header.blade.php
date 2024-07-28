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
    <link rel="stylesheet" href="{{ assetC('corporation/css/style.css') }}">
<style>
  .c-max-w-200px{
      max-width: 200px;
  }
  
  .bg-secondary-corp{
    background-color: {{$corp->colors . '30 !important; '}}
  }

  .text-corp{
    color: {{$corp->colors . '!important; '}}
  }

  .header-corp{
    padding: 15px 30px 15px 30px;
    display: flex;
    justify-content: space-between;
    
  }

  .logout-container{
    display: flex;
    justify-content: end;
    align-items: flex-end;
  }

  
  
  body{
    background: #efefef !important;
  }
  
</style>

<header class="bg-secondary-corp header-corp">
  
  <a href="{{route('corporationStudent.home', ['corporation_path'=>$corp->path])}}">
    <img src="{{filePath($corp->logo)}}" class="c-max-w-200px"  alt="">
  </a>
  
  <!-- <div class="logout-container">
    <a href="{{ route('logout') }}" class="text-corp"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="la la-power-off text-corp "></i>
        @translate(Logout)
    </a>

    <form id="logout-form"
          action="{{ route('logout') }}" method="POST"
          class="d-none">
        @csrf
    </form>
  </div> -->
  
</header>


@yield('headerCorp')