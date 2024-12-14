<?php

namespace Workbench\App\Http\Controllers;

use Decodeblock\Ercaspay\Ercaspay;
use Illuminate\Http\Request;

class PaymentController
{
    protected $ercaspay;

    public function __construct(Ercaspay $ercaspay)
    {
        $this->ercaspay = $ercaspay;
    }

    public function index()
    {
        return view('payment');
    }

    public function initiatePayment(Request $request)
    {
        // dd($request->all());
        $referenceUuid = $this->ercaspay->generatePaymentReferenceUuid();
        $data = $request->all();
        $data['paymentReference'] = $referenceUuid;
        $this->ercaspay->initializeClient(false);
        $response = $this->ercaspay->initiateTransaction($data);

        // dd($response);

        switch ($request->checkoutType) {
            case 'ercaspay':
                return redirect()->to($response['responseBody']['checkoutUrl']);
                break;
            case 'custom':
                return view('checkout', [
                    'transactionRef' => $response['responseBody']['transactionReference'],
                    'paymentRef' => $response['responseBody']['paymentReference'],
                    'paymentMethods' => explode(',', $request->paymentMethods),
                ]);
                break;
            default:
                return redirect()->to($response['responseBody']['checkoutUrl']);
                break;
        }
    }

    public function processCheckout(Request $request)
    {
        switch ($request->paymentMethod) {
            case 'bank-transfer':
                $this->ercaspay->initializeClient(false);
                $response = $this->ercaspay->initiateBankTransfer($request->transactionRef);

                // dd($response);
                return view('bank-checkout', ['responseBody' => $response['responseBody']]);

                break;
            case 'ussd':
                dd($request->all());
                break;
            case 'card':
                return view('card-checkout', ['transactionRef' => $request->transactionRef]);
                dd($request->all());
                break;

            default:
                dd($request->all());
                break;
        }
    }

    public function processCardPayment(Request $request)
    {
        $validated = $request->validate([
            'transactionRef' => 'required|string',
            'cardNumber' => 'required|string|size:16',
            'cardExpiryMonth' => 'required|string|size:2',
            'cardExpiryYear' => 'required|string|size:4',
            'cardCvv' => 'required|string|min:3|max:4',
            'pin' => 'required|string|size:4',
        ]);
        $cardExpiryYear = substr($request->cardExpiryYear, -2);

        $this->ercaspay->initializeClient(false);
        $response = $this->ercaspay->initiateCardTransaction($request, $request->transactionRef, $request->cardNumber, $request->cardExpiryMonth, $cardExpiryYear, $request->cardCvv, $request->pin);

        dd($response);

        // Process the payment...
    }
}
