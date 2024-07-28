@extends('frontend.app')
@section('content')
  <!-- ================================
      START DASHBOARD AREA
  ================================= -->
  <section class="dashboard-area">

      @include('frontend.dashboard.sidebar')
      <div class="dashboard-content-wrap">
             <div class="container-fluid">

                 <div class="row mt-5">
                     <div class="col-lg-12">
                         <div class="card-box-shared">
                             <div class="card-box-shared-title">
                                 <h3 class="widget-title">Histórico de Compras</h3>
                             </div>
                             <div class="card-box-shared-body">
                                 <div class="statement-table purchase-table table-responsive mb-5">
                                     @if($orders)
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th scope="col">@translate(S/L)</th>
                                                <th scope="col">Item(s)</th>
                                                <th scope="col">@translate(Amount)</th>
                                                <th scope="col">Data</th>
                                                <th scope="col">Pagamento</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Detalhes</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($orders as $order)
                                                    @if($order->items && $order->items->count() > 0)
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
                                                                            @if($order->items->count() > 1)
                                                                                <div>
                                                                                    <span>Cursos:</span>
                                                                                    @foreach ($order->items as $item )
                                                                                    <ul>
                                                                                        <li>
                                                                                            {{ $loop->index+1 }}. 
                                                                                            <a href="{{route('course.single',$item->course->slug)}}" class="d-inline-block primary-color">
                                                                                                {{ $item->course->title }}
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                        
                                                                                    @endforeach
                                                                                </div>
                                                                            @else
                                                                                <a href="{{route('course.single',$order->items[0]->course->slug)}}" class="d-inline-block">
                                                                                    <img src="{{filePath($order->items[0]->course->image)}}" alt="">
                                                                                </a>
                                                                                <a href="{{route('course.single',$order->items[0]->course->slug)}}" class="d-inline-block primary-color">
                                                                                    {{ $order->items[0]->course->title }}
                                                                                </a>
                                                                            @endif
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="statement-info">
                                                                    <ul class="list-items">
                                                                        <li>{{ formatPriceBr($order->payments_pagar->amount) }}</li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="statement-info">
                                                                    <ul class="list-items">
                                                                        <li>{{ $order->created_at->format('d/m/Y') }}</li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="statement-info">
                                                                    <ul class="list-items">
                                                                        @if($order->payments_pagar->payment_method == 'credit_card')
                                                                            <li><span class="">Cartão de crédito</span></li>
                                                                        @else
                                                                            <li><span class="">{{ $order->payments_pagar->payment_method }}</span></li>
                                                                        @endif   
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="statement-info">
                                                                    <ul class="list-items">
                                                                        @if($order->payments_pagar->status == 'paid')
                                                                        <li><span class="badge text-white p-1" style="background-color: #5cb55c">Pago</span></li>
                                                                        @elseif($order->payments_pagar->status == 'waiting_payment')
                                                                        <li><span class="badge bg-warning text-white p-1">Pendente</span></li>
                                                                        @elseif ($order->payments_pagar->status == 'expired')   
                                                                            <li><span class="badge bg-danger text-white p-1">Recusado</span></li>
                                                                        @endif   
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="statement-info">
                                                                    <div>
                                                                        {{-- @if($order->payment_pagar->status == 'waiting_payment')
                                                                            <button class="btn btn-success">Atualizar</button>
                                                                        @else    
                                                                            <button disabled class="btn btn-success">Atualizar</button>
                                                                        @endif    --}} 
                                                                        <a href="{{route('student.purchase.history.detail',$order->id)}}" class="btn btn-success d-flex align-items-center">
                                                                            <span style="font-size: 20px; margin-right: 5px;"><i class="la la-plus"></i></span>
                                                                            Detalhes
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
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
                 </div><!-- end row -->
                 @include('frontend.dashboard.footer')

             </div><!-- end container-fluid -->
         </div><!-- end dashboard-content-wrap -->

  </section><!-- end dashboard-area -->
  <!-- ================================
      END DASHBOARD AREA
  ================================= -->
@endsection
