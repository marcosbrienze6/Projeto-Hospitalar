<?php

namespace App\Http\Controllers;

use App\Http\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function payment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'source' => 'required|string',
            'description' => 'required|string'
        ]);

        $result = $this->paymentService->processPayment(
            $request->amount,
            $request->currency,
            $request->source,
            $request->description
        );

        if ($result['success']) {
            return response()->json([
                'error' => false,
                'message' => 'Pagamento realizado com sucesso!',
                'data' => $result['data']
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => $result['message']
            ], 400);
        }
    }
}
