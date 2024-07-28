@extends('corporationStudent.index')


<style>
    
    .c-max-w-200px{
        max-width: 200px;
    }

    .c-max-w-120px{
        max-width: 120px;
    }
    .c-primary-color{
        color: {{$corp->colors . '!important;'}}
    }
    .c-bg-primary-color{
        background: {{$corp->colors . '!important;'}}  
    }

    .c-h-screen{
        height: 100vh;
    }

    .c-border-btn{
        border: 2px solid {{$corp->colors . '!important;'}}
    }

    .custom-checkbox input[type=checkbox]:checked+label:before {
        background-color: {{$corp->colors . '!important;'}}
    }

    .custom-checkbox input[type=checkbox]:checked+label:before {
        border-color: {{$corp->colors . '!important;'}}
    }

    .c-bg-white{
        background-color: white !important;
    }

</style>

<section class="login-area section--padding  c-bg-primary-color c-h-screen">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card-box-shared">
                    <div class="card-box-shared-title text-center">
                        <img src="{{filePath($corp->logo)}}" class="c-max-w-200px"  alt="">
                    </div>

                    {{-- Flash message after successful registration --}}
                    @if (Session::has('message'))
                        <div class="alert alert-info text-center">{{ Session::get('message') }}</div>
                    @endif



                    {{-- Login form --}}
                    <div class="card-box-shared-body">
                        <div class="contact-form-action">
                            <form method="post" action="{{ route('corporationStudent.auth', ['corporation_path'=>$corp->path]) }}">
                                @csrf
                                <div class="row">
                                   
                                    <div class="col-lg-12">
                                        <div class="input-box">
                                            <label class="label-text c-primary-color">@translate(Email)<span class="c-primary-color ml-1">*</span></label>
                                            <div class="form-group">
                                                <input class=" c-primary-color form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" placeholder="@translate(Email)" required value="{{ old('email') }}">
                                                <span class="la la-envelope input-icon"></span>

                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                                @enderror

                                            </div>
                                        </div>
                                    </div><!-- end col-md-12 -->
                                    <div class="col-lg-12">
                                        <div class="input-box">
                                            <label class="label-text c-primary-color">@translate(Password)<span class="c-primary-color ml-1">*</span></label>
                                            <div class="form-group">
                                                <input id="pass" class=" form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="@translate(Password)" required>
                                                <span class="la la-lock input-icon"></span>

                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                              </span>
                                                @enderror

                                            </div>
                                        </div>
                                    </div><!-- end col-md-12 -->
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="custom-checkbox d-flex justify-content-between">
                                                <input type="checkbox" id="chb1" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="c-primary-color" for="chb1">@translate(Remember Me)</label>
                                                <!-- <a href="{{route('corporationStudent.reset', ['corporation_path'=>$corp->path])}}" class="c-primary-color"> @translate(Forgot my password)?</a> -->
                                            </div>
                                        </div>
                                    </div><!-- end col-md-12 -->
                                    <div class="col-lg-12 ">
                                        <div class="btn-box">
                                            <button class="theme-btn c-bg-white c-border-btn c-primary-color" id="loginBtn" type="submit">@translate(login account)</button>
                                        </div>
                                
                                        
                                    </div><!-- end col-md-12 -->
                                    <div class="col-lg-12 mt-5">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <img
                                                            src="{{ filePath(getSystemSetting('footer_logo')->value) }}"
                                                            alt="{{ getSystemSetting('type_name')->value }}"  class="c-max-w-200px"/>
                                                    </div>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <img class="c-max-w-120px" src="{{assetC('frontend/images/partners/abed.jpg')}}" />
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    
                                </div><!-- end row -->
                            </form>
                        </div><!-- end contact-form -->
                    </div>
                </div>
            </div><!-- end col-lg-7 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section>
