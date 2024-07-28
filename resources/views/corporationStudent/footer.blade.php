<section class="footer-area section-bg-2 padding-top-100px padding-bottom-40px {{ request()->is('student/*') ? 'student-dashboard' : '' }}">
  
  <div class="container">
      <div class="row">
          <div class="{{ request()->is('student/*') ? 'col-lg-3 offset-md-2' : 'col-lg-4' }} column-td-half">
              <div class="footer-widget">
                  <a href="{{route('homepage')}}">
                      <img style="background-color: white; padding: 10px; border-radius: 5px;" src="{{ filePath(getSystemSetting('footer_logo')->value) }}"
                           alt="{{getSystemSetting('type_name')->value}}" class="footer__logo img-fluid w-50">
                  </a>
                  <ul class="list-items footer-address">
                      <li>
                          <a href="tel:{{getSystemSetting('type_number')->value}}">{{getSystemSetting('type_number')->value}}</a>
                      </li>
                      <li><a href="mailto:{{getSystemSetting('type_mail')->value}}"
                             class="mail">{{getSystemSetting('type_mail')->value}}</a></li>
                      <li>{{getSystemSetting('type_address')->value}}</li>
                  </ul>
                  <h3 class="widget-title font-size-17 mt-4">@translate(We are on)</h3>
                  <ul class="social-profile">
                      @if(getSystemSetting('type_fb')->value != null)
                          <li><a class="d-flex justify-content-center align-items-center" href="{{getSystemSetting('type_fb')->value}}" target="_blank"><i
                                      class="fa fa-facebook"></i></a></li>
                      @endif
                      @if(getSystemSetting('type_tw')->value != null)
                          <li><a class="d-flex justify-content-center align-items-center" href="{{getSystemSetting('type_tw')->value}}" target="_blank"><i
                                      class="fa fa-instagram"></i></a></li>
                      @endif
                      @if(getSystemSetting('type_google')->value != null)
                          <li><a class="d-flex justify-content-center align-items-center" href="{{getSystemSetting('type_google')->value}}" target="_blank"><i
                                      class="fa fa-whatsapp"></i></a></li>
                      @endif
                  </ul>
              </div><!-- end footer-widget -->
          </div><!-- end col-lg-4 -->
          <!-- div class="{{ request()->is('student/*') ? 'col-lg-3' : 'col-lg-4' }} column-td-half">
              <div class="footer-widget">
                  <h3 class="widget-title">@translate(Company)</h3>
                  <span class="section-divider"></span>
                  <ul class="list-items">
                      @foreach(\App\Page::where('active',1)->get() as $item)
                          <li><a href="{{route('pages',$item->slug)}}">{{$item->title}}</a></li>
                      @endforeach
                  </ul>
              </div>
          </div>
          <div class="{{ request()->is('student/*') ? 'col-lg-3' : 'col-lg-4' }} column-td-half">
              <div class="footer-widget">
                  <h3 class="widget-title">@translate(Courses)</h3>
                  <span class="section-divider"></span>
                  <ul class="list-items">
                      @foreach(\App\Model\Category::Published()->where('top', 1)->get() as $item)
                          <li><a href="{{route('course.category',$item->slug)}}">{{$item->name}}</a></li>
                      @endforeach
                  </ul>
              </div>
          </div> -->
          <div class="col-lg-4 column-td-half">
              <div class="footer-widget">
                  <h3 class="widget-title">Links</h3>
                  <span class="section-divider"></span>
                  <ul class="list-items">
                      
                      <li><a href="https://www.youtube.com/watch?v=xpWMpJwvgn8">Quem somos</a></li>
                      {{-- <li><a href="https://caroci.com.br/#missao">Nossa missão</a></li> --}}
                      <li><a href="http://blog.caroci.com.br/">Blog</a></li>
                      <li><a href="https://loja.caroci.com.br/">Loja</a></li>
                      {{-- <li><a href=" https://caroci.com.br/#contact-form">Entre em contato</a></li> --}}
                      
                      
                  </ul>
              </div>
          </div>

      </div><!-- end row -->
      <div class="copyright-content">
          <div class="row align-items-center">
              <div class="col-lg-8">
                  <p class="copy__desc">&copy; {{date('Y')}} {{getSystemSetting('type_footer')->value}}</p>
              </div><!-- end col-lg-9 -->
              
          </div><!-- end row -->
      </div><!-- end copyright-content -->
  </div><!-- end container -->
</section><!-- end footer-area -->