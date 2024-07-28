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
<!-- ================================
       START RECOVER AREA
================================= -->
<section class="recover-area section--padding  c-bg-primary-color c-h-screen">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card-box-shared">
                    <div class="card-box-shared-title">
                        <h3 class="widget-title font-size-35 pb-2 c-primary-color">@translate(Reset Password)!</h3>
                        <p class="line-height-26">
                            @translate(Enter the email of your account to reset password.Then you will receive a link to email to reset the  password.If you have any issue about reset password) <a href="mail:{{getSystemSetting('type_mail')->value}}" class="c-primary-color">@translate(contact us)</a>
                        </p>


                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                @translate(A new password reset link sent to your email)
                            </div>
                        @endif


                    </div>
                    <div class="card-box-shared-body">
                        <div class="contact-form-action">
                            <form id="formResetPassword" method="post" action="{{ route('password.email') }}" >
                              @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-box">
                                            <label class="label-text c-primary-color">@translate(Email Address)<span class="primary-color-2 ml-1 c-primary-color">*</span></label>
                                            <div class="form-group">
                                                <input id="inputResetPassword" class="form-control @error('email') is-invalid @enderror" type="email" value="{{ old('email') }}" name="email" placeholder="Digite seu email" required autocomplete="email"
                                                autofocus>
                                                <span class="la la-envelope input-icon"></span>
                                            </div>
                                        </div>
                                    </div><!-- end col-lg-12 -->
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <button class="theme-btn c-bg-white c-border-btn c-primary-color" id="loginBtn" type="submit">@translate(reset password)</button>
                                        </div>
                                    </div><!-- end col-lg-12 -->
                                    <div class="col-lg-6">
                                        <p><a href="{{ route('corporationStudent.login', ['corporation_path' => $corp->path]) }}" class="c-primary-color">@translate(Login)</a></p>
                                    </div><!-- end col-lg-6 -->

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
</section><!-- end recover-area -->
<!-- ================================
       END RECOVER AREA
================================= -->
