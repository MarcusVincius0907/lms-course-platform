@extends('frontend.app')
@section('content')
<!-- ================================
       START SIGN UP AREA
================================= -->
<section class="sign-up section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card-box-shared">
                    <div class="card-box-shared-title text-center">
                        <h3 class="widget-title font-size-35">@translate(Create an Account and) <br> @translate(Start Teaching)!</h3>
                    </div>
                    <div class="card-box-shared-body mt-5">
                        <div class="contact-form-action">
                            <form method="post" action="{{ route('instructor.create') }}">
                                @csrf
                                <div class="row">
                                    {{--Radio button--}}
                                    <label class="label-text">@translate(Select A Package)<span class="primary-color-2 ml-1">*</span></label>
                                    <input type="hidden" id="input-package" name="package_id" value="1">
                                    <div class="row">
                                        
                                        @foreach ($packages as $item)

                                            <div class="col-lg-4 column-td-half instructor-register" onclick="">
                                                <label class="h-100">
                                                    <input type="radio" required name="package_id" value="{{$item->id}}" class="card-input-element">

                                                    <div class="post-card h-100 text-center">
                                                        <div class="post-card-content">
                                                            <img data-original="{{filePath($item->image)}}" alt="" class="img-fluid"/>
                                                            <h2 class="widget-title mt-4 mb-2">
                                                                {{$item->title}}
                                                            </h2>
                                                            <div class="contentPackInfo">
                                                                @foreach (json_decode($item->items) as $i )
                                                                <div ><i class="fa fa-check"></i> {{$i}} </div>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                        <div class="comissao"> Nossa comissão {{$item->commission}}% </div>
                                                    </div>
                                                </label>
                                            </div>
                                            
                                        @endforeach
                                        
                                    </div>

                                    <div class="col-lg-12 pt-5">
                                        <div class="input-box">
                                            <label class="label-text">@translate(Full Name)<span class="primary-color-2 ml-1">*</span></label>
                                            <div class="form-group">
                                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" placeholder="@translate(Full Name)" required value="{{ old('name') }}">
                                                <span class="la la-user input-icon"></span>

                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                        </div>
                                    </div><!-- end col-md-12 -->

                                    <div class="col-lg-12">
                                        <div class="input-box">
                                            <label class="label-text">@translate(Email Address)<span class="primary-color-2 ml-1">*</span></label>
                                            <div class="form-group">
                                                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="@translate(Email Address)" required value="{{ old('email') }}">
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
                                            <label class="label-text">@translate(Password)<span class="primary-color-2 ml-1">*</span></label>
                                            <div class="form-group">
                                                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="@translate(Password)" required>
                                                <small id="emailHelp" class="form-text text-muted">São no mínimo 8 caracteres.</small>
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
                                        <div class="input-box">
                                            <label class="label-text">@translate(Confirm Password)<span class="primary-color-2 ml-1">*</span></label>
                                            <div class="form-group">
                                                <input class="form-control @error('confirm_password') is-invalid @enderror" type="password" name="confirm_password" placeholder="@translate(Confirm Password)" required>
                                                <span class="la la-lock input-icon"></span>

                                                @error('confirm_password')
                                                <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                        </div>
                                    </div><!-- end col-md-12 -->

                                    <div class="col-lg-12 ">
                                        <div class="btn-box">
                                            <button class="theme-btn" type="submit">@translate(Register account)</button>
                                        </div>
                                    </div><!-- end col-md-12 -->
                                    <div class="col-lg-12">
                                        <p class="mt-4">@translate(Already have an account)? <a href="{{ route('login') }}" class="primary-color-2">@translate(Log in)</a></p>
                                    </div><!-- end col-md-12 -->
                                </div><!-- end row -->
                            </form>
                        </div><!-- end contact-form -->
                    </div>
                </div>
            </div><!-- end col-md-7 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section><!-- end sign-up -->
<!-- ================================
       START SIGN UP AREA
================================= -->
@endsection
