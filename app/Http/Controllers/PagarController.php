<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Demo;
use Session;
use Auth;
use Alert;
use App\Model\Cart;
use App\Model\Coupon;
use App\Model\PaymentPagar;
use App\Model\Student;
use Carbon\Carbon;
use DateTime;
use Exception;
use PagarMe\Client as ClientPagarme;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use chillerlan\QRCode\{QRCode, QROptions};

class PagarController extends Controller
{

    //make_payment
    public function make_payment(Request $request)
    {

        $pagarme = new ClientPagarme(
            env('PAGAR_API_KEY'),
        );

        try {

            $amountWithCoupon = 0;

            if($request->coupon){
               $amountWithCoupon = amountCalculatedWithCoupon($request->coupon, true);
               if($amountWithCoupon == 0){
                   return $this->paymentFree();
               }
            }
                
            $result = $pagarme->transactions()->create($this->createTransactionObj($request, $amountWithCoupon));
            return $this->procedWithResult($result, $request);

        } catch (Exception $e) {
            $message = 'Por favor, tente novamente mais tarde.';

            if($e && $e->getMessage())
                $message = 'Erro: ' . $e->getMessage() . ' ' .$message; 

            Alert::warning('error', $message);
            return back();
        }


        
    }

    public function createTransactionObj(Request $request, $amount_total = 0){

        $user = auth()->user();

        $student = Student::where('user_id', $user->id)->where('email', $user->email)->first();
        
        if (is_null($student)) {
            Alert::warning('error', 'Estudante não encontrado.');
            return back();
        }

        $address = formatAddress('array',$student->address);

        //$amount =  calculateCreditCardTax($request->amount, $request->installments);

        $payment_method = $request->payment_method;

        $objDefault = [
                      
            'customer' => [
                'external_id' => '1',
                'name' => $student->name,
                'type' => 'individual',
                'country' => $address['country'],
                'documents' => [
                    [
                        'type' => 'cpf',
                        'number' => $student->cpf
                    ]
                ],
                'phone_numbers' => ['+55'.$student->phone],
                'email' => $student->email
            ],
            'billing' => [
                'name' => $student->name,
                'address' => [
                    'country' => $address['country'],
                    'street' => $address['street'],
                    'street_number' => $address['street_number'],
                    'state' => $address['state'],
                    'city' => $address['city'],
                    'neighborhood' => $address['neighborhood'],
                    'zipcode' => $address['zipcode']
                ]
            ],
            'items' => [
                [
                    'id' => '1',
                    'title' => 'curso',
                    'unit_price' => $request->amount,
                    'quantity' => 1,
                    'tangible' => false
                ],

            ]
        ];
        
        if($amount_total == 0)
            $amount_total = $request->amount;


        if($payment_method == 'credit_card'){

            
            if($request->installments > 1){

                $installments = $this->calculateParcelamento($amount_total);

                foreach ($installments as $in) {
                    if($in->installment == $request->installments)
                    $amount = $in->amount;
                }

            }


            $objDefault = array_merge($objDefault , 
            [
                'amount' => $amount_total,  
                'payment_method' => 'credit_card',
                'installments' => $request->installments,
                'card_holder_name' => $request->pagar_cardholder_name,
                'card_cvv' => $request->pagar_cvv,
                'card_number' => $request->pagar_card_no,
                'card_expiration_date' => $request->pagar_expiry,/* TODO:FORMATAR EX: 1225 DEZ DE 2025  */
            ]);

        }else if($payment_method == 'pix'){

            $objDefault = array_merge($objDefault , 
            [   
                'amount' => $amount_total,  
                'payment_method' => 'pix',
                'pix_expiration_date' => date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days'))
                
            ]);

        }else if($payment_method == 'boleto'){

            $objDefault = array_merge($objDefault , 
            [
                'amount' => $amount_total,  
                'payment_method' => 'boleto',
                "instructions" => "Pagar até o vencimento",
                "boleto_expiration_date" => date('Y-m-d', strtotime(date('Y-m-d'). ' + 5 days')),
                "document_number" => rand(10,100),
                "type" => "DM"   
            ]);

        }

        return $objDefault;

    }

    public function procedWithResult($result, $request){

        if($result){

            if($request->payment_method == 'credit_card'){

                if ($result->status == 'paid') {
                    Session::forget('coupon');
                    $request->session()->put('payment', 'paid');

                    $payment = new PaymentPagar();
                    $payment->user_id = auth()->user()->id;
                    $payment->amount = ($result->amount/100);
                    $payment->transfer_id = $result->id;
                    $payment->payment_method = $result->payment_method;
                    $payment->status = $result->status;
                    $payment->save();


                    return redirect()->route('shopping.pending', $payment->id);

                }else if($result->status == 'refused'){
                    Alert::warning('error', 'O pagamento foi recusado. Por favor, tente novamente ou entre em contato conosco');
                    return back();
                }else {
                    Alert::warning('error', 'O pagamento falhou. Tente novamente.');
                    return back();
                }

            }else if($request->payment_method == 'pix'){

                

                $payment = new PaymentPagar();
                $payment->user_id = auth()->user()->id;
                $payment->amount = ($result->amount/100);
                $payment->transfer_id = $result->id;
                $payment->payment_method = $result->payment_method;
                $payment->status = $result->status;
                $payment->url = (new QRCode)->render($result->pix_qr_code);
                $payment->code = $result->pix_qr_code;
                $payment->expire_in = date('y-m-d', strtotime($result->pix_expiration_date) );


                if ($result->status == 'paid') {
                    Session::forget('coupon');
                    $request->session()->put('payment', 'paid');

                    $payment->save();

                    return redirect()->route('shopping.pending', $payment->id);

                }else if($result->status == 'waiting_payment'){

                    $payment->save();
                    $request->session()->put('payment', 'waiting_payment');
                    return redirect()->route('shopping.pending', $payment->id);

                }else if($result->status == 'refused'){
                    Alert::warning('error', 'O pagamento foi recusado. Por favor, tente novamente ou entre em contato conosco');
                    return back();
                }else {
                    Alert::warning('error', 'O pagamento falhou. Tente novamente.');
                    return back();
                }

            }else if($request->payment_method == 'boleto'){

                $payment = new PaymentPagar();
                $payment->user_id = auth()->user()->id;
                $payment->amount = ($result->amount/100);
                $payment->transfer_id = $result->id;
                $payment->payment_method = $result->payment_method;
                $payment->status = $result->status;
                $payment->url = $result->boleto_url;
                $payment->code = $result->boleto_barcode;
                $payment->expire_in = date('y-m-d', strtotime($result->boleto_expiration_date) );


                if ($result->status == 'paid') {
                    Session::forget('coupon');
                    $request->session()->put('payment', 'paid');
                    $payment->save();

                    return redirect()->route('shopping.pending', $payment->id);

                }else if($result->status == 'waiting_payment'){

                    $payment->save();
                    $request->session()->put('payment', 'waiting_payment');
                    return redirect()->route('shopping.pending', $payment->id);

                }else if($result->status == 'refused'){
                    Alert::warning('error', 'O pagamento foi recusado. Por favor, tente novamente ou entre em contato conosco');
                    return back();
                }else {
                    Alert::warning('error', 'O pagamento falhou. Tente novamente.');
                    return back();
                }

                
            }

            

        }else
            throw new Exception("A transação não foi criada.");
    }

    public function paymentFree(){

        //temp solcition for 100% discount

        $payment = new PaymentPagar();
        $payment->user_id = auth()->user()->id;
        $payment->amount = 0;
        $payment->transfer_id = 1;
        $payment->payment_method = 'credit_card';
        $payment->status = 'paid';
        $payment->save();

        request()->session()->put('payment', 'paid');

        return redirect()->route('shopping.pending', $payment->id);
    }

    // paymentCallback
    public function paymentCallback(Request $request)
    {
        return $request;
    }
    //ENDS HERE

    public function paymentValidation(Request $request){

        $pagarme = new ClientPagarme(
            env('PAGAR_API_KEY'),
        );


        $payment_info = PaymentPagar::where('user_id', auth()->user()->id)->where('id', $request->id)->first();
        if($payment_info->status == 'waiting_payment' ){

            $result = $pagarme->transactions()->get([
                'id' => $payment_info->transfer_id 
            ]);
    
    
            if ($result->status == 'paid') {
                Session::forget('coupon');

                $request->session()->put('payment', 'paid');
                $payment_info->status = 'paid';
                $payment_info->save();

                $request->session()->put('checkout', 'ok');
                return redirect()->route('checkout', $payment_info->id);
    
            }else if($result->status == 'waiting_payment'){
                Alert::warning('error', 'O pagamento ainda não foi aprovado.');
                return back();
    
            }else if($result->status == 'refused'){
                Alert::warning('error', 'O pagamento foi recusado. Por favor, tente novamente ou entre em contato conosco');
                return back();
            }else {
                Alert::warning('error', 'O pagamento falhou. Tente novamente.');
                return back();
            }
            
        }else if($payment_info->status == 'paid'){
            Alert::success('Sucesso', 'O pagamento já foi aprovado.');
        }else if($payment_info->status == 'expired'){
            Alert::warning('Aviso', 'O pagamento expirou');
        }

        return redirect()->back();

        


    }

    public function transactionStatusPagarme($payment_info){

        $pagarme = new ClientPagarme(
            env('PAGAR_API_KEY'),
        );

        try{
    
            $result = $pagarme->transactions()->get([
                'id' => $payment_info->transfer_id 
            ]);
    
            return $result->status;

        }catch(Exception $e){
            //Alert::warning('error', 'Erro ao tentar consultar a transação');
            return 'error';
        }


        
    }

    public function calculateParcelamento($amount){

        $pagarme = new ClientPagarme(
            env('PAGAR_API_KEY'),
        );

        try{

            $calculateInstallments = $pagarme->transactions()->calculateInstallments([
                'amount' => (float)$amount,
                'free_installments' => '1',
                'max_installments' => '12',
                'interest_rate' => env('PAGAR_CREDIT_CARD_TAX_INCREASE')
            ]);

            
    
            return (array)$calculateInstallments->installments;

        }catch(Exception $e){
            //Alert::warning('Atenção', 'Erro ao tentar consultar as parcelas.');
            
            return 0;
        }

        

    }

    public function getInstallments(Request $request){
        if($request->amount)
            return response(['status' => true, 'installments' => $this->calculateParcelamento($request->amount)]);
        else   
            return response(['status' => false, 'message' => 'Não foi possível calcular as parcelas']); 
    }

    public function payTest($id){
        
        $pagarme = new ClientPagarme(
            env('PAGAR_API_KEY'),
        );
        //simular status boleto
        return $pagarme->transactions()->simulateStatus([
            'id' => $id,
            'status' => 'paid'
        ]);
    }

    
}

