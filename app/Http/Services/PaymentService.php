<?php

namespace App\Http\Services;

use Stripe\Stripe;
use Stripe\Charge;
use Exception;

class PaymentService
{
    public function processPayment($amount, $currency, $source, $description)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $charge = Charge::create([
                "amount" => $amount * 100, 
                "currency" => $currency,
                "source" => $source, 
                "description" => $description,
            ]);

            return [
                'success' => true,
                'data' => $charge
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
