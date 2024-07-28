@extends('corporationStudent.index')


<style>
    .c-bg-blue{
        background: #003865;
    }
    .c-max-w-200px{
        max-width: 200px;
    }
    .c-404{
        font-size: 50px;
        color: #c70000;
        display: flex;
        justify-content: center;
    }
    .c-h-screen{
        height: 100vh;
    }
</style>

<section class="login-area section--padding c-bg-blue c-h-screen">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card-box-shared">
                    <div class="card-box-shared-title text-center">
                        <h3 class="widget-title font-size-35">Corporação não encontrada!</h3>
                    </div>

                    {{-- Flash message after successful registration --}}
                    @if (Session::has('message'))
                        <div class="alert alert-info text-center">{{ Session::get('message') }}</div>
                    @endif



                    {{-- Login form --}}
                    <div class="card-box-shared-body">
                        <div class="c-404">
                            404
                        </div>
                    </div>
                </div>
            </div><!-- end col-lg-7 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section>
