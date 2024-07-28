@extends('frontend.app')
@section('content')

<!-- ================================
      START CART AREA
  ================================= -->
@php
    $total_price = 0;
@endphp
<section class="cart-area padding-top-120px padding-bottom-60px">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-center mb-5">

                    <h1 class="widget-title">Seu pagamento ainda não foi confirmado!</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="shopping-cart-wrap table-responsive">
                    <table class="table table-bordered ">
                        <thead class="cart-head">
                            <tr>
                                <td class="cart__title">@translate(Image)</td>
                                <td class="cart__title">@translate(Product details)</td>
                                <td class="cart__title">@translate(Prices)</td>
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
                                    <span class="card__price">{{formatPrice($item->course->discount_price)}}</span>
                                    <span class="card__price"><del>{{formatPrice($item->course->price)}}</del></span>
                                    <input type="hidden" value="{{$total_price+=$item->course->discount_price}}">
                                    
                                    @else
                                    <input type="hidden" value="{{$total_price+=$item->course->price}}">
                                    <span class="card__price">{{formatPrice($item->course->price)}}</span>
                                    @endif
                                    @endif
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
                <div class="col-lg-12">
                    <div class="card text-center">
                        <h5 class="card-header">Finalize seu pagamento pagamento para que você possa ter acesso ao curso</h5>
                        <div class="card-body">
                            @if($payment_info->payment_method == 'boleto')
                                <div class="d-flex justify-content-center flex-column align-items-center icons-boleto">
                                    <div class="w-100 d-flex justify-content-center align-items-center">
                                        <svg id="icon-print-rounded" viewBox="0 0 64 64"><path fill="#666" d="M 32 0 A 32 32 0 0 0 0 32 A 32 32 0 0 0 32 64 A 32 32 0 0 0 64 32 A 32 32 0 0 0 32 0 z M 20 14 L 44 14 L 44 21 L 20 21 L 20 14 z M 16.5 23 L 47.5 23 C 49.4 23 51 24.6 51 26.5 L 51 41.5 C 51 43.4 49.4 45 47.5 45 L 46 45 L 46 30 L 18 30 L 18 45 L 16.5 45 C 14.6 45 13 43.4 13 41.5 L 13 26.5 C 13 24.6 14.6 23 16.5 23 z M 21 33 L 43 33 L 43 52 L 21 52 L 21 33 z M 23 36 L 23 37 L 41 37 L 41 36 L 23 36 z M 23 39 L 23 40 L 41 40 L 41 39 L 23 39 z M 23 42 L 23 43 L 41 43 L 41 42 L 23 42 z M 23 45 L 23 46 L 41 46 L 41 45 L 23 45 z M 23 48 L 23 49 L 41 49 L 41 48 L 23 48 z "></path></svg>
                                        <span>Você pode <strong> imprimir o boleto </strong> e pagar no banco</span>

                                    </div>
                                    <div class="w-100 d-flex justify-content-center align-items-center">
                                        <svg id="icon-barcode-rounded" viewBox="0 0 64 64"><path fill="#666" d="M 32 0 A 32 32 0 0 0 0 32 A 32 32 0 0 0 32 64 A 32 32 0 0 0 64 32 A 32 32 0 0 0 32 0 z M 15.7 16 L 49.3 16 C 50.8 16 52 17.2 52 18.7 L 52 20 L 49 20 L 49 19.9 C 49 19.4 48.6 19 48.1 19 L 16.9 19 C 16.4 19 16 19.4 16 19.9 L 16 38.1 C 16 38.6 16.4 39 16.9 39 L 48.1 39 C 48.6 39 49 38.6 49 38.1 L 49 37 L 52 37 L 52 39.3 C 52 40.8 50.8 42 49.3 42 L 15.7 42 C 14.2 42 13 40.8 13 39.3 L 13 18.7 C 13 17.2 14.2 16 15.7 16 z M 36 22 L 57 22 L 57 35 L 36 35 L 36 22 z M 39 24 L 39 33 L 41 33 L 41 24 L 39 24 z M 43 24 L 43 33 L 44 33 L 44 24 L 43 24 z M 45 24 L 45 33 L 46 33 L 46 24 L 45 24 z M 48 24 L 48 33 L 50 33 L 50 24 L 48 24 z M 52 24 L 52 33 L 53 33 L 53 24 L 52 24 z M 54 24 L 54 33 L 55 33 L 55 24 L 54 24 z M 10 44 L 28 44 C 28 44.6 28.4 45 29 45 L 37 45 C 37.5 45 38 44.6 38 44 L 54 44 C 54.6 44 54.8 44.4 54.5 44.9 L 53.4 47.1 C 53.2 47.6 52.5 48 52 48 L 12 48 C 11.5 48 10.8 47.6 10.6 47.1 L 9.5 44.9 C 9.2 44.4 9.5 44 10 44 z M 50 45 C 49.4 45 49 45.4 49 46 C 49 46.6 49.4 47 50 47 C 50.6 47 51 46.6 51 46 C 51 45.4 50.6 45 50 45 z "></path></svg>
                                        <span>Ou pode <strong> pagar pela internet </strong> utilizando o código de barras do boleto</span>

                                    </div>
                                    <div class="w-100 d-flex justify-content-center align-items-center">
                                        <svg id="icon-calendar-rounded" viewBox="0 0 64 64"><path fill="#666" d="M 32 0 C 14.3 0 0 14.3 0 32 C 0 49.7 14.3 64 32 64 C 49.7 64 64 49.7 64 32 C 64 14.3 49.7 0 32 0 z M 24 9 C 25.1 9 26 9.9 26 11 L 26 18 C 26 19.1 25.1 20 24 20 C 22.9 20 22 19.1 22 18 L 22 11 C 22 9.9 22.9 9 24 9 z M 40 9 C 41.1 9 42 9.9 42 11 L 42 18 C 42 19.1 41.1 20 40 20 C 38.9 20 38 19.1 38 18 L 38 11 C 38 9.9 38.9 9 40 9 z M 14 15 L 20 15 L 20 18.4 C 20 20.4 21.6 22 23.6 22 L 24.4 22 C 26.4 22 28 20.4 28 18.4 L 28 15 L 36 15 L 36 18.4 C 36 20.4 37.6 22 39.6 22 L 40.4 22 C 42.4 22 44 20.4 44 18.4 L 44 15 L 50 15 C 50.6 15 51 15.5 51 16 L 51 48 C 51 48.6 50.5 49 50 49 L 14 49 C 13.4 49 13 48.5 13 48 L 13 16 C 13 15.4 13.5 15 14 15 z M 16 24.3 L 16 45.3 L 48 45.3 L 48 24.3 L 16 24.3 z "></path></svg>
                                        <span>Lembrando que o prazo de validade do boleto é de <strong> 1 dia util </strong></span>

                                    </div>
                                    <div class="d-flex flex-column">
                                        <span>Expira em {{date_format(date_create($payment_info->expire_in),"d/m/y")}}</span>
                                        <a target="blank" href="{{$payment_info->url}}" class="theme-btn line-height-40 text-capitalize">Imprimir boleto</a>
                                        <span class="copyClip" onclick="copyToClipboard('{{$payment_info->code}}')">
                                            <svg width="12px" height="12px" viewBox="0 0 24 24" fill="none"><path d="M0 0h24v24H0z"></path><path clip-rule="evenodd" fill="currentColor" fill-rule="evenodd" d="M22.528 8.004c.035.105.06.214.072.324V15.6a3.6 3.6 0 01-3.6 3.6h-1.2v1.2a3.6 3.6 0 01-3.6 3.6H4.6A3.6 3.6 0 011 20.4v-12a3.6 3.6 0 013.6-3.6h1.2V3.6A3.6 3.6 0 019.4 0h4.86c.14.017.274.062.396.132a.384.384 0 01.108 0c.123.058.237.135.336.228l7.2 7.2c.093.1.17.213.228.336v.108zm-4.02-.804L15.4 4.092V6a1.2 1.2 0 001.2 1.2h1.907zM15.4 20.4a1.2 1.2 0 01-1.2 1.2H4.6a1.2 1.2 0 01-1.2-1.2v-12a1.2 1.2 0 011.2-1.2h1.2v8.4a3.6 3.6 0 003.6 3.6h6v1.2zm3.6-3.6a1.2 1.2 0 001.2-1.2v-6h-3.6A3.6 3.6 0 0113 6V2.4H9.4a1.2 1.2 0 00-1.2 1.2v12a1.2 1.2 0 001.2 1.2H19z"></path></svg>
                                            <span> Copie o código</span>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            @if($payment_info->payment_method == 'pix')
                                <div>
                                    <div class="d-flex justify-content-center p-3">
                                        <div style="display: grid; align-items:center;">
                                            <img src="{{assetC('frontend/images/pix-logo.png')}}" style="max-width: 200px;" alt="">
                                        </div>
                                        <div class="ml-5" style="font-size: 0.8rem;
                                        font-weight: bold;
                                        line-height: 1rem;
                                        text-align:start;">
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
                                    <div class="d-flex align-items-center flex-column">
                                        <span>Expira em {{date_format(date_create($payment_info->expire_in),"d/m/y")}}</span>
                                        <img src="{{ $payment_info->url}}" style="max-width: 200px;" />
                                        <span class="copyClip" onclick="copyToClipboard('{{$payment_info->code}}')">
                                            <svg width="12px" height="12px" viewBox="0 0 24 24" fill="none"><path d="M0 0h24v24H0z"></path><path clip-rule="evenodd" fill="currentColor" fill-rule="evenodd" d="M22.528 8.004c.035.105.06.214.072.324V15.6a3.6 3.6 0 01-3.6 3.6h-1.2v1.2a3.6 3.6 0 01-3.6 3.6H4.6A3.6 3.6 0 011 20.4v-12a3.6 3.6 0 013.6-3.6h1.2V3.6A3.6 3.6 0 019.4 0h4.86c.14.017.274.062.396.132a.384.384 0 01.108 0c.123.058.237.135.336.228l7.2 7.2c.093.1.17.213.228.336v.108zm-4.02-.804L15.4 4.092V6a1.2 1.2 0 001.2 1.2h1.907zM15.4 20.4a1.2 1.2 0 01-1.2 1.2H4.6a1.2 1.2 0 01-1.2-1.2v-12a1.2 1.2 0 011.2-1.2h1.2v8.4a3.6 3.6 0 003.6 3.6h6v1.2zm3.6-3.6a1.2 1.2 0 001.2-1.2v-6h-3.6A3.6 3.6 0 0113 6V2.4H9.4a1.2 1.2 0 00-1.2 1.2v12a1.2 1.2 0 001.2 1.2H19z"></path></svg>
                                            <span> Copie o código</span>
                                        </span>
                                    </div>
                                    
                                </div>
                            @endif
                          
                        </div>
                        <div class="card-footer text-muted">
                            <form action="{{ route('pagar.pending') }}" method="POST">
                                @csrf
                                <input type="hidden" name="transfer_id" value="{{$payment_info->transfer_id}}">
                                <p class="card-text ">Se você já pagou clique aqui para que possamos validar.</p>
                                <button type="submit" class="btn btn-primary mt-2">Validar pagamento</button>
                            </form>
                        </div>
                    </div>
                </div>
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