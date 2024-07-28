@extends('corporationStudent.index')


<style>
.c-max-w-200px{
    max-width: 200px;
}

.bg-secondary-corp{
  background-color: {{$corp->colors . '30 !important; '}}
}
.header-corp{
  padding: 30px 10px;
  display: flex;
  justify-content: center;
}

body{
  background: #efefef !important;
}

</style>



{{-- <header class="bg-secondary-corp header-corp">

  <img src="{{filePath($corp->logo)}}" class="c-max-w-200px"  alt="">
 
  
</header> --}}

@include('corporationStudent.header')

<section class="my-courses-area padding-top-30px padding-bottom-90px">
  <div class="container">
    <div class="row mb-5 mt-3">
      <div class="col-lg-12 ">
        <h1>Meus Cursos</h1>
      </div>
    </div>
      <div class="row">
          <div class="col-lg-12">
              <div class="my-course-content-wrap">
                  <div class="tab-content">
                      <div role="tabpanel" class="tab-pane fade active show" id="#all-course">
                          <div class="my-course-content-body">
                              <div class="my-course-container">
                                  <div class="row">
                                      @foreach($enrolls as $item)
                                          <div class="col-lg-4 column-td-half">
                                              <div class="card-item">
                                                  <div class="card-image">
                                                      <a href="{{route('corporationStudent.lesson_details',['corporation_path'=>$corp->path, 'slug' => $item->enrollCourse->slug])}}"
                                                         class="card__img">
                                                          <img data-original="{{filePath($item->enrollCourse->image)}}"
                                                               alt="{{$item->enrollCourse->title}}">
                                                      </a>
                                                  </div><!-- end card-image -->
                                                  <div class="card-content p-4">
                                                      <h3 class="card__title mt-0">
                                                          <div>{{Str::limit($item->enrollCourse->title,58)}}</div>
                                                      </h3>
                                                      <p class="card__author">
                                                          <div>{{$item->enrollCourse->relationBetweenInstructorUser->name}}</div>
                                                      </p>
                                                      <div class="course-complete-bar-2 mt-2">
                                                          <div class="progress-item mb-0">
                                                              <p class="skillbar-title">@translate(Complete):</p>
                                                              <div class="skillbar-box mt-1">
                                                                  <div class="skillbar">
                                                                      <div class="skillbar-bar skillbar-bar-1"
                                                                           style="width: {{\App\Http\Controllers\FrontendController::seenCourse($item->id,$item->enrollCourse->id) }}%;"></div>
                                                                  </div> <!-- End Skill Bar -->
                                                              </div>
                                                              <div
                                                                  class="skill-bar-percent">{{\App\Http\Controllers\FrontendController::seenCourse($item->id,$item->enrollCourse->id)}}
                                                                  %
                                                              </div>
                                                          </div>
                                                      </div><!-- end course-complete-bar-2 -->
                                                      <div class="text-center mt-3">
                                                          <div class="row">
                                                              
                                                              <div class="col-md-6">
                                                                  <a href="{{ route('corporationStudent.lesson_details',['corporation_path'=>$corp->path, 'slug' => $item->enrollCourse->slug]) }}"
                                                                     class="btn btn-success mt-2">@translate(Start lesson)</a>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div><!-- end card-content -->
                                              </div><!-- end card-item -->
                                          </div><!-- end col-lg-4 -->
                                      @endforeach
                                      
                                  </div>
                              </div>
                              {{-- <div class="page-navigation-wrap mt-4 text-center">
                                  {{ $enrolls->links('frontend.include.paginate') }}
                              </div> --}}
                          </div>
                      </div><!-- end tab-pane -->

                     

                  </div>
              </div>
          </div><!-- end col-lg-12 -->
      </div><!-- end row -->
  </div><!-- end container -->
</section>

@include('corporationStudent.footer')