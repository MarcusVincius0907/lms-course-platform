@extends('frontend.app')
@section('content')
    <!-- ================================
      START BREADCRUMB AREA
  ================================= -->
    <section class="breadcrumb-area my-courses-bread">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-content my-courses-bread-content">
                        <div class="section-heading">
                            <h2 class="section__title">Meus Certificados</h2>
                        </div>
                    </div><!-- end breadcrumb-content -->
                    {{-- <div class="my-courses-tab">
                        <div class="section-tab section-tab-2">
                            <ul class="nav nav-tabs" role="tablist" id="review">
                                <li role="presentation" class="padding-r-3">
                                    <a href="{{route('my.courses')}}" class="active">
                                        @translate(All Courses)
                                    </a>
                                </li>

                                <li role="presentation" class="padding-r-3">
                                    <a href="{{route('my.wishlist')}}">
                                        @translate(Wishlist)
                                    </a>
                                </li>
                                @if(env('SUBSCRIPTION_ACTIVE') == "YES")
                                <li role="presentation">
                                    <a href="{{route('my.subscription')}}">
                                        @translate(Subscription Courses)
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </div> --}}
                </div><!-- end col-lg-12 -->
            </div><!-- end row -->
        </div><!-- end container -->
    </section><!-- end breadcrumb-area -->
    <!-- ================================
        END BREADCRUMB AREA
    ================================= -->

    <!-- ================================
        START FLASH MESSAGE
    ================================= -->


    <section class="my-courses-area padding-top-30px padding-bottom-90px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="my-course-content-wrap">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active show" id="#all-course">
                                <div class="my-course-content-body">
                                    <div class="my-course-container">
                                        <div class="row">
                                          <div class="col-lg-12">
                                            <div class="card-box-shared">
                                                
                                                <div class="card-box-shared-body">
                                                    <div class="statement-table purchase-table table-responsive mb-5">
                                                        @if($certificates && $certificates->count() > 0 )
                                                           <table class="table">
                                                               <thead>
                                                               <tr>
                                                                   <th scope="col">Index</th>
                                                                   <th scope="col">Curso</th>
                                                                   <th scope="col">Data de Conclusão</th>
                                                                   <th scope="col">Certificado</th>
                                                               </tr>
                                                               </thead>
                                                               <tbody>
                                                               @foreach ($certificates as $item)
                                                                <tr>
                                                                  <th scope="row">
                                                                      <div class="statement-info">
                                                                          <ul class="list-items">
                                                                              <li>{{ $loop->index+1 }}</li>
                                                                          </ul>
                                                                      </div>
                                                                  </th>
                                                                  
                                                                  
                                                                  <td>
                                                                      <div class="statement-info">
                                                                          <ul class="list-items">
                                                                              <li>
                                                                                <a href="{{route('course.single',$item->course->slug)}}" class="d-inline-block">
                                                                                  <img src="{{filePath($item->course->image)}}" alt="">
                                                                                </a>
                                                                                <a href="{{route('course.single',$item->course->slug)}}" class="d-inline-block primary-color">
                                                                                    {{ $item->course->title }}
                                                                                </a>
                                                                              </li>
                                                                          </ul>
                                                                      </div>
                                                                  </td>
                                                                  <td>
                                                                      <div class="statement-info">
                                                                          <ul class="list-items">
                                                                             <li>{{date('d/m/Y', strtotime($item->conclusion_date))}}</li>
                                                                          </ul>
                                                                      </div>
                                                                  </td>
                                                                  
                                                                  <td>
                                                                      <div class="statement-info">
                                                                          <div style="max-width: 200px">
                                                                              {{-- @if($order->payment_pagar->status == 'waiting_payment')
                                                                                  <button class="btn btn-success">Atualizar</button>
                                                                              @else    
                                                                                  <button disabled class="btn btn-success">Atualizar</button>
                                                                              @endif    --}} 
                                                                              <a href="{{route('certificate', $item->course->id)}}" target="_blank" class="btn btn-success d-flex align-items-center">
                                                                                  <span style="font-size: 20px; margin-right: 5px;"><i class="la la-upload"></i></span>
                                                                                  Emitir Certificado
                                                                              </a>
                                                                          </div>
                                                                      </div>
                                                                  </td>
                                                                </tr>
                                                               @endforeach
                   
                                                               </tbody>
                                                           </table>
                                                        @else
                                                           <div>Sem registros...</div>
                                                        @endif
                                                    </div>
                   
                                                </div>
                                            </div>
                                        </div><!-- end col-lg-12 -->
                                        </div>
                                    </div>
                                    {{-- <div class="page-navigation-wrap mt-4 text-center">
                                        {{ $enrolls->links('frontend.include.paginate') }}
                                    </div> --}}
                                </div>
                            </div><!-- end tab-pane -->

                            <div role="tabpanel" class="tab-pane fade" id="#wishlist">
                                <div class="my-wishlist-wrap">
                                    <div class="my-wishlist-card-body padding-top-35px">
                                        <div class="row">

                                        </div><!-- end row -->
                                    </div>

                                </div><!-- end my-wishlist-wrap -->
                            </div><!-- end tab-pane -->

                        </div>
                    </div>
                </div><!-- end col-lg-12 -->
            </div><!-- end row -->
        </div><!-- end container -->
    </section><!-- end my-courses-area -->
    <!-- ================================
           START MY COURSES
    ================================= -->
@endsection
