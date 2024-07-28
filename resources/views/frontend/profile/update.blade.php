@extends('frontend.app')
@section('content')
  <!-- ================================
      START DASHBOARD AREA
  ================================= -->
  <section class="dashboard-area">

      @include('frontend.dashboard.sidebar')
      <div class="dashboard-content-wrap">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="card-box-shared">
                        <div class="card-box-shared-title">
                            <h3 class="widget-title">@translate(Settings info)</h3>
                        </div>
                        <div class="card-box-shared-body">
                            <div class="section-tab section-tab-2">
                                <ul class="nav nav-tabs" role="tablist" id="review">
                                    <li role="presentation">
                                        <a href="#profile" role="tab" data-toggle="tab" class="active" aria-selected="true">
                                            @translate(Profile)
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#password" role="tab" data-toggle="tab" aria-selected="false">
                                             @translate(Password)
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="dashboard-tab-content mt-5">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active show" id="profile">
                                      <form method="post" action="{{ route('student.update', Auth::user()->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="user-form">
                                            <div class="user-profile-action-wrap mb-5">
                                                <h3 class="widget-title font-size-18 padding-bottom-40px">@translate(Profile Settings)</h3>
                                                <div class="user-profile-action d-flex align-items-center">
                                                    <div class="user-pro-img">
                                                        <img src="{{ filePath($student->image) }}" alt="{{ $student->name }}" class="img-fluid radius-round border">
                                                    </div>
                                                    <div class="upload-btn-box course-photo-btn">
                                                        <input type="hidden" name="oldImage" value="{{ $student->image }}">
                                                        <input type="file" name="image" value="">
                                                    </div>
                                                </div><!-- end user-profile-action -->
                                            </div><!-- end user-profile-action-wrap -->
                                            <div class="contact-form-action">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="input-box">
                                                                <label class="label-text">@translate(Full Name)<span class="primary-color-2 ml-1">*</span></label>
                                                                <div class="form-group">
                                                                    <input class="form-control" style="padding-left:20px;" type="text" name="name" value="{{ $student->name }}">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div><!-- end col-lg-6 -->

                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="input-box">
                                                                <label class="label-text">@translate(Email Address)<span class="primary-color-2 ml-1">*</span></label>
                                                                <div class="form-group">
                                                                    <input class="form-control" style="padding-left:20px;" type="email" readonly name="email" value="{{ $student->email }}">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div><!-- end col-lg-6 -->
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="input-box">
                                                                <label class="label-text">@translate(Phone Number)</label>
                                                                <div class="form-group">
                                                                    <input class="phone-input form-control @error('phone') is-invalid @enderror" style="padding-left:20px;" type="text"  value="{{ $student->student->phone  ?? '' }}">
                                                                    <input class="phone-input-hidden" type="hidden" value="{{ $student->student->phone ?? '' }}" name="phone">       
                                                                    @error('phone')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div><!-- end col-lg-6 -->
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="input-box">
                                                                <label class="label-text">CPF</label>
                                                                <div class="form-group">
                                                                    <input class="cpf-input form-control @error('cpf') is-invalid @enderror" style="padding-left:20px;"  type="text" value="{{ $student->student->cpf ?? '' }}">
                                                                    <input class="cpf-input-hidden" type="hidden" value="{{ $student->student->cpf ?? '' }}" name="cpf">       
                                                                    @error('cpf')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div><!-- end col-lg-6 -->
                                                        
                                                        <div class="col-lg-12 border-top pt-4">

                                                            <div class="row">
            
                                                                <div class="col-lg-6">
                                                                    <div class="input-box">
                                                                        <label class="label-text">Rua<span
                                                                                class="primary-color-2 ml-1">*</span></label>
                                                                        <div class="form-group">
                                                                            <input
                                                                                class="form-control @error('street') is-invalid @enderror"
                                                                                style="padding-left:20px;"
                                                                                type="text" name="street"
                                                                                value="{{ $address['street'] ?? '' }}"
                                                                                placeholder="Rua" required>
                                                                            
                    
                                                                            @error('street')
                                                                            <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                      </span>
                                                                            @enderror
                    
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="input-box">
                                                                        <label class="label-text">Número<span
                                                                                class="primary-color-2 ml-1">*</span></label>
                                                                        <div class="form-group">
                                                                            <input
                                                                                class="form-control @error('street_number') is-invalid @enderror"
                                                                                style="padding-left:20px;"
                                                                                type="text" name="street_number"
                                                                                value="{{ $address['street_number'] ?? '' }}"
                                                                                placeholder="Número" required>
                                                                            
                    
                                                                            @error('street_number')
                                                                            <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                      </span>
                                                                            @enderror
                    
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
            
                                                            <div class="row">
            
                                                                <div class="col-lg-6">
                                                                    <div class="input-box">
                                                                        <label class="label-text">Bairro<span
                                                                                class="primary-color-2 ml-1">*</span></label>
                                                                        <div class="form-group">
                                                                            <input
                                                                                class="form-control @error('neighborhood') is-invalid @enderror"
                                                                                style="padding-left:20px;"
                                                                                type="text" name="neighborhood"
                                                                                value="{{ $address['neighborhood'] ?? '' }}"
                                                                                placeholder="Bairro" required>
                                                                            
                    
                                                                            @error('neighborhood')
                                                                            <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                      </span>
                                                                            @enderror
                    
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="input-box">
                                                                        <label class="label-text">Cidade<span
                                                                                class="primary-color-2 ml-1">*</span></label>
                                                                        <div class="form-group">
                                                                            <input
                                                                                class="form-control @error('city') is-invalid @enderror"
                                                                                style="padding-left:20px;"
                                                                                type="text" name="city"
                                                                                value="{{ $address['city'] ?? '' }}"
                                                                                placeholder="Cidade" required>
                                                                            
                    
                                                                            @error('city')
                                                                            <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                      </span>
                                                                            @enderror
                    
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
            
                                                            <div class="row">
            
                                                                <div class="col-lg-4">
                                                                    <div class="input-box">
                                                                        <label class="label-text">Estado<span
                                                                                class="primary-color-2 ml-1">*</span></label>
                                                                        <div class="form-group">
                                                                            <input
                                                                                class="form-control @error('state') is-invalid @enderror"
                                                                                style="padding-left:20px;"
                                                                                type="text" name="state"
                                                                                value="{{ $address['state'] ?? '' }}"
                                                                                placeholder="Estado" required>
                                                                            
                    
                                                                            @error('state')
                                                                            <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                      </span>
                                                                            @enderror
                    
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="input-box">
                                                                        <label class="label-text">País<span
                                                                                class="primary-color-2 ml-1">*</span></label>
                                                                        <div class="form-group">
                                                                            <input
                                                                                class="form-control @error('country') is-invalid @enderror"
                                                                                style="padding-left:20px;"
                                                                                type="text" name="country"
                                                                                value="{{ $address['country'] ?? '' }}"
                                                                                placeholder="País" required>
                                                                            
                    
                                                                            @error('country')
                                                                            <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                      </span>
                                                                            @enderror
                    
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="input-box">
                                                                        <label class="label-text">CEP<span
                                                                                class="primary-color-2 ml-1">*</span></label>
                                                                        <div class="form-group">
                                                                            <input
                                                                                class="form-control @error('zipcode') is-invalid @enderror"
                                                                                style="padding-left:20px;"
                                                                                type="text" name="zipcode"
                                                                                value="{{ $address['zipcode'] ?? '' }}"
                                                                                placeholder="CEP" required>
                                                                            
                    
                                                                            @error('zipcode')
                                                                            <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                      </span>
                                                                            @enderror
                    
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
            
                                                        </div>

                                                        


                                                        <div class="col-lg-12">
                                                            <div class="btn-box">
                                                                <button class="theme-btn" type="submit">@translate(Save Changes)</button>
                                                            </div>
                                                        </div><!-- end col-lg-12 -->
                                                    </div><!-- end row -->
                                                </form>
                                            </div>
                                        </div>
                                    </div><!-- end tab-pane-->

                                    <div role="tabpanel" class="tab-pane fade" id="password">
                                        <div class="user-form padding-bottom-60px">
                                            <div class="user-profile-action-wrap">
                                                <h3 class="widget-title font-size-18 padding-bottom-40px">@translate(Change Password)</h3>
                                            </div><!-- end user-profile-action-wrap -->
                                            <div class="contact-form-action">
                                              <form method="POST" action="{{ route('password.update') }}">
                                                  @csrf
                                                    <div class="row">
                                                        <div class="col-lg-4 col-sm-4">
                                                            <div class="input-box">
                                                                <label class="label-text">@translate(E-Mail Address)<span class="primary-color-2 ml-1">*</span></label>
                                                                <div class="form-group">
                                                                  <input id="email" type="email"
                                                                         class="form-control @error('email') is-invalid @enderror"
                                                                         name="email" value="{{ $email ?? old('email') }}" required
                                                                         autocomplete="email" autofocus placeholder="Email address">

                                                                    <span class="la la-lock input-icon"></span>

                                                                    @error('email')
                                                                    <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                                    @enderror

                                                                </div>
                                                            </div>
                                                        </div><!-- end col-lg-4 -->
                                                        <div class="col-lg-4 col-sm-4">
                                                            <div class="input-box">
                                                                <label class="label-text">@translate(New Password)<span class="primary-color-2 ml-1">*</span></label>
                                                                <div class="form-group">
                                                                  <input id="password" type="password"
                                                                         class="form-control @error('password') is-invalid @enderror"
                                                                         name="password" required autocomplete="new-password" placeholder="New password">

                                                                         <span class="la la-lock input-icon"></span>
                                                                  @error('password')
                                                                  <span class="invalid-feedback" role="alert">
                                                              <strong>{{ $message }}</strong>
                                                          </span>
                                                                  @enderror
                                                                </div>
                                                            </div>
                                                        </div><!-- end col-lg-4 -->
                                                        <div class="col-lg-4 col-sm-4">
                                                            <div class="input-box">
                                                                <label class="label-text">@translate(Confirm New Password)<span class="primary-color-2 ml-1">*</span></label>
                                                                <div class="form-group">
                                                                  <input id="password-confirm" type="password" class="form-control"
                                                                         name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                                                                    <span class="la la-lock input-icon"></span>

                                                                    @error('password_confirmation')
                                                                    <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                                    @enderror

                                                                </div>
                                                            </div>
                                                        </div><!-- end col-lg-4 -->
                                                        <div class="col-lg-12">
                                                            <div class="btn-box">
                                                                <button class="theme-btn" type="submit">@translate(Change password)</button>
                                                            </div>
                                                        </div><!-- end col-lg-12 -->
                                                    </div><!-- end row -->
                                                </form>
                                            </div>
                                        </div>
                                        <div class="section-block"></div>
                                        <div class="user-form padding-top-60px">
                                            <div class="user-profile-action-wrap padding-bottom-20px">
                                                <h3 class="widget-title font-size-18 padding-bottom-10px">@translate(Forgot Password then Recover Password)</h3>
                                                <p class="line-height-26">@translate(Enter the email of your account to reset password. Then you will receive a link to email)
                                                    <br> @translate(to reset the password.If you have any issue about reset password)</p>
                                            </div><!-- end user-profile-action-wrap -->
                                            <div class="contact-form-action">

                                              @if (session('status'))
                                                  <div class="alert alert-success" role="alert">
                                                      {{ session('status') }}
                                                  </div>
                                              @endif

                                                <form method="post" action="{{ route('password.email') }}">
                                                  @csrf
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="input-box">
                                                                <label class="label-text">@translate(Email Address)<span class="primary-color-2 ml-1">*</span></label>
                                                                <div class="form-group">
                                                                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="@translate(Enter email address)" required autocomplete="email">
                                                                    <span class="la la-lock input-icon"></span>
                                                                </div>
                                                            </div>
                                                        </div><!-- end col-lg-6 -->
                                                        <div class="col-lg-12">
                                                            <div class="btn-box">
                                                                <button class="theme-btn" type="submit">@translate(recover password)</button>
                                                            </div>
                                                        </div><!-- end col-lg-12 -->
                                                    </div><!-- end row -->
                                                </form>
                                            </div>
                                        </div>
                                    </div><!-- end tab-pane-->

                                </div><!-- end tab-content -->
                            </div><!-- end dashboard-tab-content -->
                        </div>
                    </div><!-- end card-box-shared -->
                </div><!-- end col-lg-12 -->
            </div><!-- end row -->
            @include('frontend.dashboard.footer')

        </div><!-- end container-fluid -->
    </div><!-- end dashboard-content-wrap -->

  </section><!-- end dashboard-area -->
  <!-- ================================
      END DASHBOARD AREA
  ================================= -->
  {{-- <script>
      let inputCPF2 = document.querySelector(".cpf-input");
        let inputCPFHidden2 = document.querySelector(".cpf-input-hidden");

        let inputPhone2 = document.querySelector(".phone-input");
        let inputPhoneHidden2 = document.querySelector(".phone-input-hidden");

        if(inputCPF2 && inputPhone2){

            
            inputPhone2.onkeyup = (e) => {
            if (inputPhone2.value.length <= 11)
                inputPhone2.value = formatPhone(e.target.value);
            else inputPhone2.value = inputPhone2.value.slice(0, 13);
            
            if (inputPhoneHidden2)
                inputPhoneHidden2.value = inputPhone2.value
                ? inputPhone2.value.replace(/\D/g, "")
                : "";
            };
            
            inputCP2.onkeyup = (e) => {
            if (inputCPF2.value.length <= 11) inputCPF2.value = formatCPF(e.target.value);
            else inputCPF2.value = inputCPF2.value.slice(0, 14);
            
            if (inputCPFHidden2)
                inputCPFHidden2.value = inputCPF.value
                ? inputCPF2.value.replace(/\D/g, "")
                : "";
            };
            
            function formatCPF(cpf) {
            //retira os caracteres indesejados...
            cpf = cpf.replace(/[^\d]/g, "");
            
            //realizar a formatação...
            return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
            }
            
            function formatPhone(phone) {
            var x = phone.replace(/\D/g, "").match(/(\d{2})(\d{4})(\d{4})/);
            phone = x[1] + " " + x[2] + "-" + x[3];
            return phone;
            }
        }
  </script> --}}
@endsection
