<?php

namespace App\Http\Middleware;

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PagarController;
use App\Model\Cart;
use App\Model\Enrollment;
use App\Model\Orders;
use App\Model\PaymentPagar;
use Closure;
use DateTime;

class VerifyPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check()){


            $orders = Orders::where('user_id', auth()->user()->id)->get();
            

            if($orders != null && $orders->count() > 0){
                foreach($orders as $order){

                    
                    if($order && $order->payments_pagar->status == 'waiting_payment'){
                        
                        $statusPagarme = (new PagarController)->transactionStatusPagarme($order->payments_pagar);
    
                        if($statusPagarme == 'paid'){
                            
                            $order->payments_pagar->status = 'paid';
                            $order->payments_pagar->save();
    
                            (new FrontendController)->finishCheckout($order);
    
                            return redirect()->route('my.courses');
                        }
    
                        $today_dt = new DateTime(date('Y-m-d'));
                        $expire_dt = new DateTime($order->payments_pagar->expire_in);
                        
                        if ($expire_dt < $today_dt) { 
                            $order->payments_pagar->status = 'expired';
                            $order->payments_pagar->save();
                            
                        }
    
                    }
                }
            }



            
        }

        return $next($request);
    }
}
