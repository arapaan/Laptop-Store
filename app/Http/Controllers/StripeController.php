<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function index()
    {
        return view('stripe.index');
    }

    public function checkout()
    {
        Stripe::setApiKey(config('stripe.sk'));

        $datas = [
            [
                'currency'  => 'idr',
                'name'  => 'Send me Moneyy!!!',
                'unit_amount'  => 100000000,
                'quantity'  => 1,
            ],
            [
                'currency'  => 'idr',
                'name'  => 'ashdbhasdb!!!',
                'unit_amount'  => 500000000,
                'quantity'  => 5,
            ],
            [
                'currency'  => 'idr',
                'name'  => 'asjkdbhasbdnbasd!!!',
                'unit_amount'  => 500000000,
                'quantity'  => 1,
            ],
        ];

        $lineItems = [];
        foreach ($datas as $data) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'idr',
                    'product_data' => [
                        'name' => $data['name'],
                    ],
                    'unit_amount' => $data['unit_amount'],
                ],
                'quantity' => $data['quantity'],
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            // 'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            // 'cancel_url' => route('payment.cancel'),
            'success_url' => route('success'),
            'cancel_url' => route('index'),
        ]);

        return redirect()->away($session->url);
    }

    public function success()
    {
        return view('stripe.index');
    }

    public function cancel()
    {
        //
    }
}
