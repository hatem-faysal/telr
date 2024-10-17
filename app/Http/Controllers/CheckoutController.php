<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CheckoutController extends Controller
{
    public function index() {
        return view('checkout');
    }

    public function store(Request $request) {
        
        $order_id = rand(4111, 9999);
        $amount = 231;

        $telrManager = new \TelrGateway\TelrManager();

        $billingParams = [
                'first_name' => 'Moustafa Gouda',
                'sur_name' => 'Bafi',
                'address_1' => 'Gnaklis',
                'address_2' => 'Gnaklis 2',
                'city' => 'Alexandria',
                'region' => 'San Stefano',
                'zip' => '11231',
                'country' => 'EG',
                'email' => 'example@company.com',
            ];
            
        return $telrManager->pay($order_id, $amount, 'Telr Testing Youtube', $billingParams)->redirect();
    }

    public function success(Request $request) {
        $telrManager = new \TelrGateway\TelrManager();

        $transaction = $telrManager->handleTransactionResponse($request);

        $card_last_4 = $transaction->response['order']['card']['last4'];
        $name = $transaction->response['order']['customer']['name']['forenames']." ".$transaction->response['order']['customer']['name']['surname'];

        return view('success')->with([
            'request'   =>  $request,
            'card_last_4'   =>  $card_last_4,
            'name'  =>  $name,
        ]);
    }
    public function cancel(Request $request) {
        return view(('cancel'));
    }
    public function declined(Request $request) {
        return view(('decline'));
    }
}
