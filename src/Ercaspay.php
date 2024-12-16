<?php

/**
 * Author: Gabriel Ibenye
 * GitHub: https://github.com/gabbyTI
 * Email: gabrielibenye@gmail.com
 * Created: December 11, 2024
 */

namespace Decodeblock\Ercaspay;

use Decodeblock\Ercaspay\Exceptions\ErcaspayClientErrorException;
use Decodeblock\Ercaspay\Exceptions\ErcaspayRequestException;
use Decodeblock\Ercaspay\Exceptions\ErcaspayServerErrorException;
use Decodeblock\Ercaspay\Exceptions\IsNullException;
use Decodeblock\Ercaspay\Types\PayerDeviceDto;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Ercaspay
{
    //TODO: Remove the extra data sent for during logs for all function because setResponse() is already printing it
    /**
     * Secret key for authentication with Ercaspay API
     */
    private string $secretKey;

    /**
     * HTTP client instance for making API requests
     */
    private Client $client;

    /**
     * Base URL for the Ercaspay API
     */
    private string $baseUrl;

    /**
     * Stores the last API response
     */
    private $response;

    /**
     * Initializes the Ercaspay client with config values
     */
    public function __construct()
    {
        Log::info('Initializing Ercaspay client');
        $this->secretKey = Config::get('ercaspay.secretKey');
        $this->baseUrl = Config::get('ercaspay.baseUrl');
        $this->initializeClient();
        Log::info('Ercaspay client initialized successfully');
    }

    /**
     * Sets up the HTTP client with authentication and headers
     *
     * @param  bool  $verifySsl  Whether to verify SSL certificates
     */
    public function initializeClient(bool $verifySsl = true): void
    {
        Log::debug('Setting up HTTP client', ['verify_ssl' => $verifySsl]);
        $authBearer = 'Bearer '.$this->secretKey;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'verify' => $verifySsl,
            'headers' => [
                'Authorization' => $authBearer,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
        Log::debug('HTTP client configured successfully');
    }

    /**
     * Makes an HTTP request to the API and handles the response
     *
     * @param  string  $relativeUrl  The API endpoint URL
     * @param  string  $method  HTTP method (GET, POST etc)
     * @param  array  $body  Request body data
     */
    private function setResponse(string $relativeUrl, string $method, array $body = []): self
    {
        Log::info('Making API request', [
            'url' => $relativeUrl,
            'method' => $method,
            'body' => $body,
        ]);

        if (empty($method)) {
            throw new IsNullException('Method cannot be empty');
        }

        try {
            $options = ! empty($body) ? ['json' => $body] : [];
            $this->response = $this->client->request(strtoupper($method), $relativeUrl, $options);
            Log::info('API request successful');
        } catch (ClientException $e) {
            if (! $e->hasResponse()) {
                Log::error('Client error in API request', [
                    'response' => $e->getMessage(),
                    'status_code' => $e->getCode(),
                ]);
                throw new ErcaspayClientErrorException($e->getMessage(), $e->getCode());
            }
            $this->response = $e->getResponse();
            $errorData = $this->getResponse();

            Log::error('Ercaspay Client error in API request', [
                'response' => $errorData,
                'status_code' => $e->getCode(),
            ]);
            // Throw custom exception with readable message
            throw new ErcaspayClientErrorException($errorData['errorMessage'] ?? "An error occurred while calling ercapay api path: $relativeUrl", $e->getCode(), $errorData);
        } catch (ServerException $e) {
            if (! $e->hasResponse()) {
                Log::error('Server error in API request', [
                    'response' => $e->getMessage(),
                    'status_code' => $e->getCode(),
                ]);
                throw new ErcaspayServerErrorException($e->getMessage(), $e->getCode());
            }
            $this->response = $e->getResponse();
            $errorData = $this->getResponse();

            Log::error('Server error in API request', [
                'response' => $errorData,
                'status_code' => $e->getCode(),
            ]);
            throw new ErcaspayServerErrorException($errorData['errorMessage'] ?? "Ercaspay server error occurred while calling ercapay api path: $relativeUrl", $e->getCode(), $errorData);
        } catch (RequestException $e) {
            if (! $e->hasResponse()) {
                Log::error('Request error in API request', [
                    'response' => $e->getMessage(),
                    'status_code' => $e->getCode(),
                ]);
                throw new ErcaspayRequestException($e->getMessage(), $e->getCode());
            }

            $this->response = $e->getResponse();
            $errorData = $this->getResponse();

            Log::error('Request error in API request', [
                'response' => $errorData,
                'status_code' => $e->getCode(),
            ]);
            throw new ErcaspayRequestException($errorData['errorMessage'] ?? "An Ercaspay request error occurred while calling ercapay api path: $relativeUrl", $e->getCode(), $errorData);
        }

        return $this;
    }

    /**
     * Gets the decoded response data from the last API call
     *
     * @return array Response data
     */
    private function getResponse(): array
    {
        if (empty($this->response)) {
            Log::error('Attempted to get response when none exists');
            throw new IsNullException('No response found');
        }

        $response = json_decode($this->response->getBody(), true);
        Log::debug('API response decoded', ['response' => $response]);

        return $response;
    }

    /**
     * Initiates a new payment transaction
     *
     * @param  array  $data  Transaction details including amount, reference etc
     * @return array Response from payment initiation
     */
    public function initiateTransaction(array $data): array
    {
        Log::info('Initiating payment transaction', ['data' => $data]);

        $response = $this->setResponse('/api/v1/payment/initiate', 'POST', $data)->getResponse();
        Log::info('Payment transaction initiated successfully', ['response' => $response]);

        return $response;
    }

    /**
     * Verifies the status of a transaction
     *
     * @param  string  $transactionRef  Transaction reference to verify
     * @return array Verification response
     */
    public function verifyTransaction(string $transactionRef): array
    {

        Log::info('Verifying transaction', ['transaction_ref' => $transactionRef]);

        $response = $this->setResponse('/api/v1/payment/transaction/verify/'.$transactionRef, 'GET')->getResponse();
        Log::info('Transaction verification completed', ['response' => $response]);

        return $response;
    }

    /**
     * Initiates a bank transfer payment
     *
     * @param  string  $transactionRef  Transaction reference
     * @return array Bank transfer details
     */
    public function initiateBankTransfer(string $transactionRef): array
    {
        Log::info('Initiating bank transfer', ['transaction_ref' => $transactionRef]);

        $response = $this->setResponse('/api/v1/payment/bank-transfer/request-bank-account/'.$transactionRef, 'GET')->getResponse();
        Log::info('Bank transfer initiated successfully', ['response' => $response]);

        return $response;
    }

    /**
     * Initiates a USSD payment transaction
     *
     * @param  string  $transactionRef  Transaction reference
     * @param  string  $bankName  Name of bank for USSD
     * @return array USSD payment details
     */
    public function initiateUssdTransaction(string $transactionRef, string $bankName): array
    {
        Log::info('Initiating USSD transaction', [
            'transaction_ref' => $transactionRef,
            'bank_name' => $bankName,
        ]);

        $response = $this->setResponse('/api/v1/payment/ussd/request-ussd-code/'.$transactionRef, 'POST', ['bank_name' => $bankName])->getResponse();
        Log::info('USSD transaction initiated successfully', ['response' => $response]);

        return $response;
    }

    /**
     * Gets list of banks that support USSD payments
     *
     * @return array List of supported banks
     */
    public function getBankListForUssd(): array
    {

        Log::info('Fetching USSD supported banks list');
        $response = $this->setResponse('/api/v1/payment/ussd/supported-banks', 'GET')->getResponse();
        Log::debug('Retrieved USSD supported banks', ['banks' => $response]);

        return $response;
    }

    /**
     * Generates a unique payment reference ID
     *
     * @return string UUID for payment reference
     */
    public function generatePaymentReferenceUuid(): string
    {
        $uuid = Str::uuid()->toString();
        Log::debug('Generated payment reference UUID', ['uuid' => $uuid]);

        return $uuid;
    }

    /**
     * Fetches details for a transaction
     *
     * @param  string  $transactionRef  Transaction reference
     * @return array Transaction details
     */
    public function fetchTransactionDetails(string $transactionRef): array
    {
        Log::info('Fetching transaction details', ['transaction_ref' => $transactionRef]);

        $response = $this->setResponse('/api/v1/payment/details/'.$transactionRef, 'GET')->getResponse();
        Log::info('Transaction details retrieved', ['response' => $response]);

        return $response;
    }

    /**
     * Checks the current status of a transaction
     *
     * @param  string  $transactionRef  Transaction reference
     * @param  string  $paymentReference  Payment reference
     * @param  string  $paymentMethod  Payment method used
     * @return array Transaction status
     */
    public function fetchTransactionStatus(string $transactionRef, string $paymentReference, string $paymentMethod): array
    {
        Log::info('Fetching transaction status', [
            'transaction_ref' => $transactionRef,
            'payment_ref' => $paymentReference,
            'payment_method' => $paymentMethod,
        ]);

        $response = $this->setResponse('/api/v1/payment/status/'.$transactionRef, 'POST', [
            'payment_method' => $paymentMethod,
            'reference' => $paymentReference,
        ])->getResponse();
        Log::info('Transaction status retrieved', ['response' => $response]);

        return $response;
    }

    /**
     * Cancels a transaction
     *
     * @param  string  $transactionRef  Transaction reference to cancel
     * @return array Cancellation response
     */
    public function cancelTransaction(string $transactionRef): array
    {
        Log::info('Cancelling transaction', ['transaction_ref' => $transactionRef]);

        $response = $this->setResponse('/api/v1/payment/cancel/'.$transactionRef, 'GET')->getResponse();
        Log::info('Transaction cancelled successfully', ['response' => $response]);

        return $response;
    }

    /**
     * Initiates a card payment transaction
     *
     * @param  Request  $request  HTTP request object
     * @param  string  $transactionRef  Transaction reference
     * @param  string  $cardNumber  Card number
     * @param  string  $cardExpiryMonth  Card expiry month
     * @param  string  $cardExpiryYear  Card expiry year
     * @param  string  $cardCvv  Card CVV code
     * @param  string  $pin  Card PIN
     * @return array Card transaction response
     */
    public function initiateCardTransaction(Request $request, string $transactionRef, string $cardNumber, string $cardExpiryMonth, string $cardExpiryYear, string $cardCvv, string $pin): array
    {
        Log::info('Initiating card transaction', [
            'transaction_ref' => $transactionRef,
            'card_number' => substr($cardNumber, 0, 6).'******'.substr($cardNumber, -4),
        ]);

        $cardDetails = [
            'cvv' => $cardCvv,
            'pin' => $pin,
            'expiryDate' => $cardExpiryMonth.''.$cardExpiryYear,
            'pan' => $cardNumber,
        ];

        $deviceDetails = PayerDeviceDto::fromRequest($request)->toArray();
        Log::debug('Device details captured', ['device_details' => $deviceDetails]);

        $publicKeyPath = __DIR__.'/key/rsa_public_key.pub';
        Log::debug('Using public key for encryption', ['key_path' => $publicKeyPath]);

        $encryptor = new CardEncryptor($publicKeyPath);
        $encryptedCard = $encryptor->encrypt($cardDetails);
        Log::debug('Card details encrypted successfully');

        $data = [
            'transactionReference' => $transactionRef,
            'payload' => $encryptedCard,
            'deviceDetails' => $deviceDetails,
        ];

        $this->setResponse('/api/v1/payment/cards/initialize', 'POST', $data);

        $response = $this->getResponse();
        Log::info('Card transaction initiated successfully', ['response' => $response]);

        return $response;
    }

    /**
     * Submits OTP for card transaction
     *
     * @param  string  $transactionRef  Transaction reference
     * @param  string  $paymentReference  Payment reference
     * @param  string  $otp  One-time password
     * @return array OTP submission response
     */
    public function submitCardOTP(string $transactionRef, string $paymentReference, string $otp): array
    {
        Log::info('Submitting card OTP', [
            'transaction_ref' => $transactionRef,
            'payment_ref' => $paymentReference,
        ]);

        Log::debug('Making OTP submission request', [
            'endpoint' => '/api/v1/payment/cards/otp/submit/'.$transactionRef,
            'method' => 'POST',
        ]);

        $response = $this->setResponse('/api/v1/payment/cards/otp/submit/'.$transactionRef, 'POST', [
            'otp' => $otp,
            'gatewayReference' => $paymentReference,
        ])->getResponse();

        Log::info('OTP submission completed', ['response' => $response]);

        return $response;
    }

    /**
     * Requests new OTP for card transaction
     *
     * @param  string  $transactionRef  Transaction reference
     * @param  string  $paymentReference  Payment reference
     * @return array OTP resend response
     */
    public function resendCardOTP(string $transactionRef, string $paymentReference): array
    {
        Log::info('Requesting OTP resend', [
            'transaction_ref' => $transactionRef,
            'payment_ref' => $paymentReference,
        ]);

        Log::debug('Making OTP resend request', [
            'endpoint' => '/api/v1/payment/cards/otp/resend/'.$transactionRef,
            'method' => 'POST',
        ]);

        $response = $this->setResponse('/api/v1/payment/cards/otp/resend/'.$transactionRef, 'POST', [
            'gatewayReference' => $paymentReference,
        ])->getResponse();

        Log::info('OTP resend completed', ['response' => $response]);

        return $response;
    }

    /**
     * Gets saved card details
     *
     * @param  string  $transactionRef  Transaction reference
     * @return array Card details
     */
    public function getCardDetails(string $transactionRef): array
    {
        Log::info('Fetching saved card details', ['transaction_ref' => $transactionRef]);

        Log::debug('Making card details request', [
            'endpoint' => '/api/v1/payment/cards/details/'.$transactionRef,
            'method' => 'GET',
        ]);

        $response = $this->setResponse('/api/v1/payment/cards/details/'.$transactionRef, 'GET')->getResponse();

        Log::info('Card details retrieved successfully', ['response' => $response]);

        return $response;
    }

    /**
     * Verifies a card transaction
     *
     * @param  string  $transactionRef  Transaction reference
     * @return array Verification response
     */
    public function verifyCardTransaction(string $transactionRef): array
    {
        Log::info('Verifying card transaction', ['transaction_ref' => $transactionRef]);

        Log::debug('Making transaction verification request', [
            'endpoint' => '/api/v1/payment/cards/transaction/verify',
            'method' => 'POST',
        ]);

        $response = $this->setResponse('/api/v1/payment/cards/transaction/verify', 'POST', [
            'reference' => $transactionRef,
        ])->getResponse();

        Log::info('Transaction verification completed', ['response' => $response]);

        return $response;
    }
}
