@extends('frontend.app')
@section('content')

<!-- ================================
      START CART AREA
  ================================= -->
@php
    $total_price = 0;
@endphp
<section class="cart-area padding-top-120px padding-bottom-60px" id="section-payment">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shopping-cart-wrap table-responsive">
                    <table class="table table-bordered ">
                        <thead class="cart-head">
                            <tr>
                                <td class="cart__title">@translate(Image)</td>
                                <td class="cart__title">@translate(Product details)</td>
                                <td class="cart__title">@translate(Prices)</td>
                                <td class="cart__title">@translate(Remove)</td>
                            </tr>
                        </thead>
                        <tbody class="cart-body">
                            
                            @foreach($carts as $item)
                            <tr>
                                <td><a href="{{route('course.single',$item->course->slug)}}" class="d-block"><img
                                            src="{{ filePath($item->course->image) }}"
                                            alt="{{$item->course->title}}"></a></td>
                                <td>
                                    <div class="cart-product-desc">
                                        <a href="{{route('course.single',$item->course->slug)}}"
                                            class="widget-title">{{$item->course->title}}</a>
                                        <p>
                                            By <a href="#!">{{$item->course->relationBetweenInstructorUser->name}}</a>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <!--if free-->
                                    @if($item->course->is_free)
                                    <span class="card__price">@translate(Free)</span>
                                    @else
                                    @if($item->course->is_discount)
                                    <span class="card__price">{{formatPriceBr($item->course->discount_price)}}</span>
                                    <span class="card__price"><del>{{formatPriceBr($item->course->price)}}</del></span>
                                    <input type="hidden" value="{{$total_price+=$item->course->discount_price}}">
                                    
                                    @else
                                    <input type="hidden" value="{{$total_price+=$item->course->price}}">
                                    <span class="card__price">{{formatPriceBr($item->course->price)}}</span>
                                    @endif
                                    @endif
                                </td>
                                <td>
                                    <a type="button" href="{{route('cart.remove',$item->id)}}" class="button-remove"><i
                                            class="fa fa-close"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- end shopping-cart-wrap -->
            </div><!-- end col-lg-12 -->
        </div><!-- end row -->
        <div class="cart-detail-wrap mt-4">
            <div class="row">
                @if(env('COUPON_ACTIVE') != "NO" &&
                couponRouteForBlade())
                <div class="col-lg-8 ml-auto">

                    <div class="contact-form-action mt-4">

                        @if(!Session::has('coupon'))

                        <h3 class="widget-title font-size-20">@translate(Have coupon code? Apply here)</h3>

                        <form action="{{ route('checkout.coupon.store') }}" class="needs-validation" novalidate
                            method="post">
                            @csrf

                            <div class="input-box">
                                <div class="form-group mb-0">
                                    <!-- Search bar -->
                                    <input class="form-control" type="text" name="code"
                                        placeholder="Enter Coupon Code Here">
                                    <input type="hidden" name="total" value="{{ onlyPrice($total_price) }}">
                                    <button type="submit" class="btn btn-primary mt-2">@translate(Apply Coupon)</button>
                                    <!-- Search bar END - -->
                                </div>
                            </div><!-- end input-box -->
                        </form>

                        @endif

                        <div class="mt-4">

                            @if(Session::has('success'))
                            <p class="alert alert-success">{{ Session::get('success') }}</p>
                            @endif


                            @if(Session::has('error'))
                            <p class="alert alert-danger">{{ Session::get('error') }}</p>
                            @endif

                        </div>


                        @php
                        if(Session::has('coupon')){
                        $coupon = session()->get('coupon')['name'];
                        }
                        @endphp


                        @if(Session::has('coupon'))
                        <!-- coupon -->
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                <figure>
                                    <figcaption>
                                        <h3>@translate(Coupon Code Applied): <span
                                                class="badge badge-success">{{ $coupon }}</span></h3>

                                        <br>

                                        <h3>@translate(Discounted Amount):
                                            <span class="badge badge-success"> {{ couponDiscount($coupon) }} </span>
                                        </h3>

                                    </figcaption>
                                    <form action="{{ route('checkout.coupon.destroy') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" name="coupon"
                                                value="{{ $coupon }}">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-danger">@translate(Try Another Coupon)
                                            </button>
                                        </div>
                                    </form>
                                </figure>
                            </div>
                        </div>
                        <!-- coupon END -->
                        @endif

                    </div>


                </div>
                @endif
                <div class="col-lg-8 ml-auto">
                    <div class="shopping-cart-detail-item">
                        <div class="shopping-cart-content pt-2">
                            {{-- <ul class="list-items">
                                <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                    <span class="primary-color">@translate(Total):</span>

                                    @if(Session::has('coupon'))

                                    <span class="primary-color-3"> <del>{{formatPriceBr($total_price)}}</del>
                                        {{formatPriceBr($total_price - couponDiscountPrice($coupon))}}</span>

                                    @else
                                    <span class="primary-color-3">{{formatPriceBr($total_price)}}</span>
                                    @endif

                                </li>
                            </ul> --}}
                            @if(onlyPrice($total_price) == 0)
                            <div class="btn-box mt-4">
                                <a href="{{route('free.payment')}}"
                                    class="theme-btn theme-btn-light">@translate(Checkout)</a>
                            </div>
                            @else
                            {{-- checkout --}}
                            <h3 class="widget-title mb-3">@translate(Select Payment Method)</h3>
                            <div class="card-box-shared" style="border:none;">
                                
                                <div class="card-box-shared-body p-0">

                                    {{-- Wallet --}}

                                    @if(env('WALLET_ACTIVE') == "YES")
                                    <div class="text-center mt-5">
                                        @if (payWithCoin())

                                        @if (checkWallerBalanceForPurchase(WalletPrice($total_price)))

                                        <form action="{{ route('wallet.payment') }}" method="post">
                                            @csrf
                                            <button class="btn btn-success w-75 p-3">
                                                <input type="hidden" name="amount"
                                                    value="{{ WalletPrice($total_price) }}">
                                                Pay with {{ walletName() }} ({{ WalletPrice($total_price) }})
                                            </button>
                                        </form>

                                        @else

                                        <p class="btn btn-success w-75 p-3">
                                            @translate(Not enough) {{ walletName() }} ({{ walletBalance() }})
                                            @translate(to purchase)
                                        </p>

                                        @endif

                                        @else
                                        <p class="btn btn-success w-75 p-3">
                                            @translate(Not enough) {{ walletName() }} ({{ walletBalance() }})
                                            @translate(to purchase)
                                        </p>
                                        @endif
                                    </div>
                                    @endif


                                    <div class="payment-method-wrap">
                                        <div class="checkout-item-list">


                                            <div class="accordion" id="paymentMethodExample">

                                                


                                                {{-- PAGAR PAYMENT --}}

                                                @if(env('PAGAR_ACTIVE') == "YES")
                                                {{--PAGAR--}}

                                                <style>
                                                   
                                                    
                                                    /* Style the tab */
                                                    

                                                    
                                                    
                                                    </style>

                                                <input type="hidden" id="pagarme-tax" value="{{ env('PAGAR_CREDIT_CARD_TAX')}}">
                                                <input type="hidden" id="pagarme-tax-inc" value="{{env('PAGAR_CREDIT_CARD_TAX_INCREASE')}}">
                                                <input type="hidden" name="payment_method"  value="credit_card">   
                                                <input type="hidden" id="couponUrl" value="{{route('coupon.apply')}}"> 
                                                <input type="hidden" id="installmentUrl" value="{{route('pagar.installment')}}"> 

                                                
                                                <div class="tab">
                                                    <button class="tablinks" id="btn-credit" onclick="changeTab('credit_card')">Cartão de Crédito</button>
                                                    <button class="tablinks" id="btn-pix" onclick="changeTab('pix')">Pix</button>
                                                    <button class="tablinks" id="btn-boleto" onclick=" changeTab('boleto')">Boleto</button>
                                                </div>
                                                
                                                

                                                <div class="containerFormTab">
                                                    <form action="{{ route('pagar.payment') }}" method="POST" id="formTabContainer">
                                                        @csrf

                                                        <div id="credit_card" class="tabcontent">
                                                            <input type="hidden" name="payment_method"  value="credit_card">

                                                            <div class="container p-0">
                                                                <div class="card px-4">
                                                                    <div class="row gx-3">
                                                                        <div class="col-12">
                                                                            <div class="d-flex flex-column">
                                                                                <p class="text mb-1">Nome do titular do cartão</p> 
                                                                                <input class="form-control mb-3" 
                                                                                type="text" 
                                                                                placeholder="Cardholder Name" 
                                                                                value="{{ Auth::user()->name }}" 
                                                                                name="pagar_cardholder_name"
                                                                                autocomplete="cc-name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="d-flex flex-column">
                                                                                <p class="text mb-1">Número do cartão</p> 
                                                                                <input class="form-control mb-3" 
                                                                                id="cr_no" 
                                                                                type="text" 
                                                                                name="pagar_card_no"
                                                                                placeholder="1234 5678 435678" 
                                                                                autocomplete="cc-number">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-6">
                                                                            <div class="d-flex flex-column">
                                                                                <p class="text mb-1">Validade</p> 
                                                                                <input
                                                                                id="input-validade"
                                                                                class="form-control mb-3" 
                                                                                type="text" 
                                                                                placeholder="MMAA" 
                                                                                autocomplete="cc-exp"
                                                                                maxlength="5"
                                                                                >
                                                                                <input id="input-validade-hidden" type="hidden" name="pagar_expiry">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-6">
                                                                            <div class="d-flex flex-column">
                                                                                <p class="text mb-1">Código de segurança</p> 
                                                                                <input class="form-control mb-3 pt-2 " 
                                                                                type="password" 
                                                                                name="pagar_cvv"
                                                                                maxlength="5"
                                                                                placeholder="***">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <p class="text mb-1">Parcelar</p> 
                                                                            {{-- <select name="installments" id="select-installment" class="form-control mb-3" aria-label="Default select example">
                                                                                <option value="1" selected>1x</option>
                                                                                <option value="2">2x</option>
                                                                                <option value="3">3x</option>
                                                                                <option value="4">4x</option>
                                                                                <option value="5">5x</option>
                                                                                <option value="6">6x</option>
                                                                                <option value="7">7x</option>
                                                                                <option value="8">8x</option>
                                                                                <option value="9">9x</option>
                                                                                <option value="10">10x</option>
                                                                                <option value="11">11x</option>
                                                                                <option value="12">12x</option>
                                                                            </select> --}}
                                                                                @php
                                                                                    $installments = (new App\Http\Controllers\PagarController)->calculateParcelamento(PagarPrice($total_price));
                                                                                @endphp
                                                                                <input type="hidden" id="installments-hidden" value="{{json_encode($installments)}}">
                                                                                <select name="installments" id="select-installment"  class="form-control mb-3" aria-label="Default select example">
                                                                                    {{-- @foreach($installments as $item)
                                                                                        <option value="{{$item->installment}}" {{$loop->first? 'selected' : '' }}>{{ $item->installment ."x" . " - " . formatPriceBr($item->installment_amount/100) }}</option>
                                                                                    @endforeach --}}
                                                                                </select>
                                                                            {{-- {{Form::select('installments', ['key1' => 'valor1', 'key2' => 'valor2'])}} --}}

                                                                        </div>
                                                                        
                                                                
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            
                                                            
                                                        </div>
                                                        
                                                        <div id="pix" class="tabcontent">
                                                            <input type="hidden" name="payment_method"  value="pix">
                                                            <div class="">
                                                                <div class="d-flex p-3">
                                                                    <div style="display: grid; align-items:center;">
                                                                        <img src="{{assetC('frontend/images/pix-logo.png')}}" style="max-width: 200px;" alt="">
                                                                    </div>
                                                                    <div class="ml-5" style="font-size: 0.8rem;
                                                                    font-weight: bold;
                                                                    line-height: 1rem;">
                                                                        <div class="pb-3">
                                                                            <i class="la la-check" style="color: #32bcad;font-weight: bold;"></i>
                                                                            <span>Copie ou faça a leitura do código QR Code através do site ou app do seu banco</span>
                                                                        </div>
                                                                        <div class="pb-3">
                                                                            <i class="la la-check" style="color: #32bcad;font-weight: bold;"></i>
                                                                            <span>O código é válido por 30 minutos</span>
                                                                        </div>
                                                                        <div class="pb-3">
                                                                            <i class="la la-check" style="color: #32bcad;font-weight: bold;"></i>
                                                                            <span>O pedido só é confirmado após o pagamento</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="boleto" class="tabcontent">
                                                            <input type="hidden" name="payment_method"  value="boleto">
                                                            <div class="container icons-boleto p-5">
                                                                <div>
                                                                    <svg id="icon-print-rounded" viewBox="0 0 64 64"><path fill="#666" d="M 32 0 A 32 32 0 0 0 0 32 A 32 32 0 0 0 32 64 A 32 32 0 0 0 64 32 A 32 32 0 0 0 32 0 z M 20 14 L 44 14 L 44 21 L 20 21 L 20 14 z M 16.5 23 L 47.5 23 C 49.4 23 51 24.6 51 26.5 L 51 41.5 C 51 43.4 49.4 45 47.5 45 L 46 45 L 46 30 L 18 30 L 18 45 L 16.5 45 C 14.6 45 13 43.4 13 41.5 L 13 26.5 C 13 24.6 14.6 23 16.5 23 z M 21 33 L 43 33 L 43 52 L 21 52 L 21 33 z M 23 36 L 23 37 L 41 37 L 41 36 L 23 36 z M 23 39 L 23 40 L 41 40 L 41 39 L 23 39 z M 23 42 L 23 43 L 41 43 L 41 42 L 23 42 z M 23 45 L 23 46 L 41 46 L 41 45 L 23 45 z M 23 48 L 23 49 L 41 49 L 41 48 L 23 48 z "></path></svg>
                                                                    <span>Você pode <strong> imprimir o boleto </strong> e pagar no banco</span>
    
                                                                </div>
                                                                <div>
                                                                    <svg id="icon-barcode-rounded" viewBox="0 0 64 64"><path fill="#666" d="M 32 0 A 32 32 0 0 0 0 32 A 32 32 0 0 0 32 64 A 32 32 0 0 0 64 32 A 32 32 0 0 0 32 0 z M 15.7 16 L 49.3 16 C 50.8 16 52 17.2 52 18.7 L 52 20 L 49 20 L 49 19.9 C 49 19.4 48.6 19 48.1 19 L 16.9 19 C 16.4 19 16 19.4 16 19.9 L 16 38.1 C 16 38.6 16.4 39 16.9 39 L 48.1 39 C 48.6 39 49 38.6 49 38.1 L 49 37 L 52 37 L 52 39.3 C 52 40.8 50.8 42 49.3 42 L 15.7 42 C 14.2 42 13 40.8 13 39.3 L 13 18.7 C 13 17.2 14.2 16 15.7 16 z M 36 22 L 57 22 L 57 35 L 36 35 L 36 22 z M 39 24 L 39 33 L 41 33 L 41 24 L 39 24 z M 43 24 L 43 33 L 44 33 L 44 24 L 43 24 z M 45 24 L 45 33 L 46 33 L 46 24 L 45 24 z M 48 24 L 48 33 L 50 33 L 50 24 L 48 24 z M 52 24 L 52 33 L 53 33 L 53 24 L 52 24 z M 54 24 L 54 33 L 55 33 L 55 24 L 54 24 z M 10 44 L 28 44 C 28 44.6 28.4 45 29 45 L 37 45 C 37.5 45 38 44.6 38 44 L 54 44 C 54.6 44 54.8 44.4 54.5 44.9 L 53.4 47.1 C 53.2 47.6 52.5 48 52 48 L 12 48 C 11.5 48 10.8 47.6 10.6 47.1 L 9.5 44.9 C 9.2 44.4 9.5 44 10 44 z M 50 45 C 49.4 45 49 45.4 49 46 C 49 46.6 49.4 47 50 47 C 50.6 47 51 46.6 51 46 C 51 45.4 50.6 45 50 45 z "></path></svg>
                                                                    <span>Ou pode <strong> pagar pela internet </strong> utilizando o código de barras do boleto</span>
    
                                                                </div>
                                                                <div>
                                                                    <svg id="icon-calendar-rounded" viewBox="0 0 64 64"><path fill="#666" d="M 32 0 C 14.3 0 0 14.3 0 32 C 0 49.7 14.3 64 32 64 C 49.7 64 64 49.7 64 32 C 64 14.3 49.7 0 32 0 z M 24 9 C 25.1 9 26 9.9 26 11 L 26 18 C 26 19.1 25.1 20 24 20 C 22.9 20 22 19.1 22 18 L 22 11 C 22 9.9 22.9 9 24 9 z M 40 9 C 41.1 9 42 9.9 42 11 L 42 18 C 42 19.1 41.1 20 40 20 C 38.9 20 38 19.1 38 18 L 38 11 C 38 9.9 38.9 9 40 9 z M 14 15 L 20 15 L 20 18.4 C 20 20.4 21.6 22 23.6 22 L 24.4 22 C 26.4 22 28 20.4 28 18.4 L 28 15 L 36 15 L 36 18.4 C 36 20.4 37.6 22 39.6 22 L 40.4 22 C 42.4 22 44 20.4 44 18.4 L 44 15 L 50 15 C 50.6 15 51 15.5 51 16 L 51 48 C 51 48.6 50.5 49 50 49 L 14 49 C 13.4 49 13 48.5 13 48 L 13 16 C 13 15.4 13.5 15 14 15 z M 16 24.3 L 16 45.3 L 48 45.3 L 48 24.3 L 16 24.3 z "></path></svg>
                                                                    <span>Lembrando que o prazo de validade do boleto é de <strong> 1 dia util </strong></span>
    
                                                                </div>
                                                            </div>
                                                        </div>

                                                       {{--  @php
                                                            $id_carts = collect();
                                                            foreach($carts as $item){
                                                                $id_carts->push($item->course->id);
                                                            }
                                                        @endphp --}}

                                                        <div class="p-3">
                                                            <div class="w-100">

                                                                <div class="bar"></div>

                                                                <div class="py-2 w-100 d-flex justify-content-between">

                                                                    <div class="w-100" style="max-width: 300px" >
                                                                        <div class="d-flex justify-content-between btn-collapse" style="cursor: pointer">
                                                                            <p class="text" style="font-weight: bold; font-size: 1.3em; color: #233d63;">Possui cupom? </p> 
                                                                            <div  style="font-size:1.5em;color: #233d63; "><i class="
                                                                                la la-angle-down"></i></div>
                                                                        </div>
                                                                        <div class="d-flex content-collapse pt-2">
                                                                            <input class="form-control mb-3  " 
                                                                            id="inputCodeCoupon"
                                                                            type="text" 
                                                                            name="coupon"
                                                                            placeholder="Ex: 123"
                                                                            style="height: 38px;">
                                                                            <div id="btnApply" onclick="applyCoupon()" class="ml-3 btn btn-secondary" style="height: 38px;">Aplicar</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-100 d-flex justify-content-center align-items-center">

                                                                        <div id="couponApplied" class="pl-2 " style="font-size: 1.2em; text-align:end;"></div>
                                                                    </div>
                                                                </div>

                                                                <div class="bar"></div>

                                                                <div class="py-2  d-flex justify-content-between w-100">
                                                                    
                                                                    <div>
                                                                        @if(Session::has('coupon'))
                                                                            <div class="total-price">
                                                                                Total: <strong> {{ formatPriceBr($total_price - couponDiscountPrice($coupon)) }}</strong>
                                                                            </div>   
                                                                        @else
                                                                            <div class="total-price">
                                                                                Total: <strong>{{ formatPriceBr($total_price) }}</strong>
                                                                            </div>    
                                                                        @endif
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-pagar">
                                                                            
                                                                            @if(Session::has('coupon'))
                                                                            <input type="hidden" name="amount" id="current-amout"
                                                                                value="{{ PagarPrice($total_price, couponDiscountPrice($coupon)) }}">
                                                                                Pagar 
                                                                            @else
                                                                            <input type="hidden" name="amount" id="current-amout"
                                                                                value="{{ PagarPrice($total_price) }}">
                                                                                Pagar 
                                                                            @endif
            
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>


                                                    </form>    
                                                </div>

                                                
                                                
                                                


                                                {{-- <div class="card">
                                                    <a href="javascript:void()" class="text-center" data-toggle="modal" data-target="#exampleModalLong">
                                                        <img src="{{ filePath('pagar.png') }}" height="25px" width="80px" alt="pagar">
                                                    </a>


                                                    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">

                                                                <div class="modal-body">

                                                                    

                                                                <form action="{{ route('pagar.payment') }}" method="POST">
                                                                    @csrf
                                                                        

                                                                    <div class="container p-0">
                                                                        <div class="card px-4">
                                                                            <div class="row gx-3">
                                                                                <div class="col-12">
                                                                                    <div class="d-flex flex-column">
                                                                                        <p class="text mb-1">Cardholder Name</p> 
                                                                                        <input class="form-control mb-3" 
                                                                                        type="text" 
                                                                                        placeholder="Cardholder Name" 
                                                                                        value="{{ Auth::user()->name }}" 
                                                                                        name="pagar_cardholder_name"
                                                                                        autocomplete="cc-name">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="d-flex flex-column">
                                                                                        <p class="text mb-1">Card Number</p> 
                                                                                        <input class="form-control mb-3" 
                                                                                        id="cr_no" 
                                                                                        type="text" 
                                                                                        name="pagar_card_no"
                                                                                        placeholder="1234 5678 435678" 
                                                                                        autocomplete="cc-number">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <div class="d-flex flex-column">
                                                                                        <p class="text mb-1">Expiry</p> 
                                                                                        <input class="form-control mb-3" 
                                                                                        type="text" 
                                                                                        placeholder="MM/YYYY" 
                                                                                        name="pagar_expiry"
                                                                                        autocomplete="cc-exp">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <div class="d-flex flex-column">
                                                                                        <p class="text mb-1">CVV/CVC</p> 
                                                                                        <input class="form-control mb-3 pt-2 " 
                                                                                        type="password" 
                                                                                        name="pagar_cvv"
                                                                                        placeholder="***">
                                                                                    </div>
                                                                                </div>
                                                                        
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            
                                                                            @if(Session::has('coupon'))
                                                                            <input type="hidden" name="amount"
                                                                                value="{{ PagarPrice($total_price, couponDiscountPrice($coupon)) }}">
                                                                                Pay {{ formatPriceBr($total_price - couponDiscountPrice($coupon)) }}
                                                                            @else
                                                                            <input type="hidden" name="amount"
                                                                                value="{{ PagarPrice($total_price) }}">
                                                                                Pay {{ formatPriceBr($total_price) }}
                                                                            @endif

                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                {{--PAGAR ends--}}
                                                @endif

                                                {{-- PAGAR PAYMENT::END --}}

                                                
                                            </div><!-- end accordion -->
                                        </div>
                                    </div><!-- end payment-method-wrap -->
                                </div><!-- end card-box-shared-body -->



                    {{-- <div class="m-5">

                        <h5>We accept -</h5>

                        @if (env('PAYPAL_CLIENT_ID') != NULL || env('PAYPAL_APP_SECRET') != NULL)
                        <img src="{{ filePath('paypal.png') }}" class="w-25 p-2" alt="#paypal">
                        @endif

                        @if (env('PAYTM_ACTIVE') != 'NO' || env('PAYTM_MERCHANT_ID') != NULL ||
                        env('PAYTM_MERCHANT_KEY') != NULL)
                        <img src="{{ filePath('paytm.png') }}" alt="#paytm" class="w-25 p-2">
                        @endif

                        @if (env('STRIPE_KEY') != NULL || env('STRIPE_SECRET') != NULL)
                        <img src="{{ filePath('stripe.png') }}" alt="#stripe" class="w-25 p-2">
                        @endif

                        @if (env('PAGAR_ACTIVE') == "YES")
                        <img src="{{ filePath('pagar.png') }}" alt="#pagar" class="w-25 p-2">
                        @endif

                    </div> --}}

                </div>
                @endif
                {{-- checkout::END --}}


                {{-- stripe --}}

            </div><!-- end shopping-cart-content -->
        </div><!-- end shopping-cart-detail-item -->
    </div><!-- end col-lg-4 -->
    </div><!-- end row -->
    </div>
    </div><!-- end container -->
</section><!-- end cart-area -->
<!-- ================================
        END CART AREA
    ================================= -->
@endsection
@section('js')
{{-- stripe --}}
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
 
    let tabCreditCard = $('#credit_card');
    let tabPix = $('#pix');
    let tabBoleto = $('#boleto');
    let formTabContainer = $('#formTabContainer');
    function changeTab(tab){


    let btnCredit = $('#btn-credit');
    let btnPix = $('#btn-pix');
    let btnBoleto = $('#btn-boleto');

    if(tab == 'credit_card'){

        btnCredit.addClass('active');
        btnPix.removeClass('active');
        btnBoleto.removeClass('active');

        tabPix.detach();
        tabBoleto.detach();
        tabCreditCard.detach()
        formTabContainer.prepend(tabCreditCard)
    }else if(tab == 'pix'){

        btnCredit.removeClass('active');
        btnPix.addClass('active');
        btnBoleto.removeClass('active');

        tabPix.detach();
        tabBoleto.detach();
        tabCreditCard.detach()
        formTabContainer.prepend(tabPix)
    }else if(tab == 'boleto'){

        btnCredit.removeClass('active');
        btnPix.removeClass('active');
        btnBoleto.addClass('active');

        tabPix.detach();
        tabBoleto.detach();
        tabCreditCard.detach()
        formTabContainer.prepend(tabBoleto)
    }

    }
</script>


<script>

    new Promise((res,rej) => {
        setTimeout(() => {
            document.querySelector('.tablinks').click();
            res(true);
        }, 1000);
    })

    let btn = document.querySelector('.btn-collapse');
    let colaps = document.querySelector('.content-collapse');
    const size = "56px";
    btn.onclick = () => {
        if(colaps.style.height != size)
            colaps.style.height = size;
        else
            colaps.style.height = "0px";
    }


    


    
</script>

<script type="text/javascript">
    "use strict"
    $(function () {
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function (e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'
                ].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('hide');

            $('.has-error').removeClass('has-error');
            $inputs.each(function (i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });

            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }

        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                // insert the token into the form so it gets submitted to the server
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }

    });
</script>




<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
    "use strict"
    paypal.Button.render({
        // Configure environment
        // env: 'sandbox',
        env: '{{ env('
        PAYPAL_ENVIRONMENT ') }}',
        client: {
            // sandbox: '{{ env('PAYPAL_CLIENT_ID') }}'
            production: '{{ env('
            PAYPAL_CLIENT_ID ') }}'
        },
        //Todo::must be  env data in client
        // Customize button (optional)
        locale: 'en_US',
        style: {
            size: 'responsive',
            color: 'gold',
            shape: 'pill',
            label: 'checkout',
        },

        // Enable Pay Now checkout flow (optional)
        commit: true,

        // Set up a payment
        payment: function (data, actions) {
            return actions.payment.create({
                transactions: [{
                    amount: {

                        @if(Session::has('coupon'))
                        total: '{{ $total_price  - couponDiscountPrice($coupon) }}',
                        @else
                        total: '{{ $total_price }}',
                        @endif
                        currency: 'USD'
                    }
                }]
            });
        },
        // Execute the payment
        onAuthorize: function (data, actions) {
            return actions.payment.execute().then(function () {
                // Show a confirmation message to the buyer
                /*append data in input form*/
                $('#orderID').val(data.orderID);
                $('#payerID').val(data.payerID);
                $('#paymentID').val(data.paymentID)
                $('#paymentToken').val(data.paymentToken)
                $('#paypal-form').submit();
            });
        }
    }, '#paypal-button');
</script>

{{-- PAYTM START --}}

@if(env('PAYTM_MERCHANT_ID') != "" && env('PAYTM_MERCHANT_KEY') != "" && env('PAYTM_ACTIVE') != "NO" &&
paytmRouteForBlade())

<script>
    function paytmPay() {
        $('#payTmForm').submit();
    }
</script>

@endif

{{-- PAYTM END --}}


<script>
    $(document).ready(function () {

        //For Card Number formatted input
        var cardNum = document.getElementById('cr_no');
        cardNum.onkeyup = function (e) {
            if (this.value == this.lastValue) return;
            var caretPosition = this.selectionStart;
            var sanitizedValue = this.value.replace(/[^0-9]/gi, '');
            var parts = [];

            for (var i = 0, len = sanitizedValue.length; i < len; i += 4) {
                parts.push(sanitizedValue.substring(i, i + 4));
            }
            for (var i = caretPosition - 1; i >= 0; i--) {
                var c = this.value[i];
                if (c < '0' || c > '9') {
                    caretPosition--;
                }
            }
            caretPosition += Math.floor(caretPosition / 4);

            this.value = this.lastValue = parts.join(' ');
            this.selectionStart = this.selectionEnd = caretPosition;
        }
    })
</script>

@endsection