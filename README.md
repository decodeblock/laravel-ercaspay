# Laravel Ercaspay

A Laravel package for seamless integration with the Ercaspay payment gateway API.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/decodeblock/laravel-ercaspay.svg?style=flat-square)](https://packagist.org/packages/decodeblock/laravel-ercaspay)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/decodeblock/laravel-ercaspay/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/decodeblock/laravel-ercaspay/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/decodeblock/laravel-ercaspay/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/decodeblock/laravel-ercaspay/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/decodeblock/laravel-ercaspay.svg?style=flat-square)](https://packagist.org/packages/decodeblock/laravel-ercaspay)

## Features

-   Easy integration with Ercaspay payment gateway API
-   Support for multiple payment methods
-   Payment verification
-   Comprehensive error handling

## Requirements

-   PHP 8.3 or higher
-   Laravel 9.0 or higher

## Ercaspay API Documentation

- [Click here for the documentation](https://docs.ercaspay.com/#2f601f17-0bde-44ba-971a-f8458cadb213)

## Installation

Install the package via composer:

```bash
composer require decodeblock/laravel-ercaspay
```

## Configuration

1. Publish the config file:

    ```bash
    php artisan vendor:publish --tag="ercaspay-config"
    ```

2. Add your Ercaspay credentials to your .env file:

    ```
    ERCASPAY_PUBLIC_KEY=your_public_key
    ERCASPAY_SECRET_KEY=your_secret_key
    ```

## Usage Example

Here are some examples of how to use this package:

### Generate Payment Reference UUID

```php
use Decodeblock\Ercaspay;

$ercaspay = new Ercaspay();
$referenceUuid = $ercaspay->generatePaymentReferenceUuid();
echo "Generated Reference UUID: " . $referenceUuid;
```

### Initiate a Transaction

```php
use Decodeblock\Ercaspay;

$ercaspay = new Ercaspay();
$referenceUuid = $ercaspay->generatePaymentReferenceUuid();
$response = $ercaspay->initiateTransaction([
    'paymentReference' => $referenceUuid,
    'amount' => 2000,
    'currency' => 'NGN',
    'description' => 'Payment for services',
    'paymentMethods' => 'card, bank-transfer',
    'customerName' => 'John Doe',
    'customerEmail' => 'john@example.com',
]);

print_r($response);

echo "Request status is: " . $response['responseMessage']

```

### Verify a Transaction

```php
$response = $ercaspay->verifyTransaction('transaction_reference');

print_r($response);
```

### Initiate a Bank Transfer

```php
$response = $ercaspay->initiateBankTransfer('transaction_reference');

print_r($response);
```

### Initiate a Card Transaction

```php
$transactionRef = 'TEST-REF-'.time();
$cardNumber = '4111111111111111';
$cardExpiryMonth = '12';
$cardExpiryYear = '25';
$cardCvv = '123';
$pin = '1234';

$response = $this->ercaspay->initiateCardTransaction(
    $request,
    $transactionRef,
    $cardNumber,
    $cardExpiryMonth,
    $cardExpiryYear,
    $cardCvv,
    $pin
);

print_r($response);
```

## Available Methods

Below are all the methods available in the package:

```php
/**
 * Initiates a new payment transaction
 *
 * @param array $data Transaction details including amount, reference etc
 * @return array Ercaspay API Response
 */
public function initiateTransaction(array $data): array
```

```php
/**
 * Verifies the status of a transaction
 *
 * @param string $transactionRef Transaction reference to verify
 * @return array Ercaspay API Response
 */
public function verifyTransaction(string $transactionRef): array
```

```php
/**
 * Initiates a bank transfer payment
 *
 * @param string $transactionRef Transaction reference
 * @return array Ercaspay API Response
 */
public function initiateBankTransfer(string $transactionRef): array
```

```php
/**
 * Initiates a USSD payment transaction
 *
 * @param string $transactionRef Transaction reference
 * @param string $bankName Name of bank for USSD
 * @return array Ercaspay API Response
 */
public function initiateUssdTransaction(string $transactionRef, string $bankName): array
```

```php
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
     * @return array Ercaspay API Response
     */
    public function initiateCardTransaction(Request $request, string $transactionRef, string $cardNumber, string $cardExpiryMonth, string $cardExpiryYear, string $cardCvv, string $pin): array
```

```php
/**
 * Gets list of banks that support USSD payments
 *
 * @return array Ercaspay API Response
 */
public function getBankListForUssd(): array
```

```php
/**
 * Generates a unique payment reference ID
 *
 * @return string UUID for payment reference
 */
public function generatePaymentReferenceUuid(): string
```

```php
/**
 * Fetches details for a transaction
 *
 * @param string $transactionRef Transaction reference
 * @return array Ercaspay API Response
 */
public function fetchTransactionDetails(string $transactionRef): array
```

```php
/**
 * Checks the current status of a transaction
 *
 * @param string $transactionRef Transaction reference
 * @param string $paymentReference Payment reference
 * @param string $paymentMethod Payment method used
 * @return array Ercaspay API Response
 */
public function fetchTransactionStatus(string $transactionRef, string $paymentReference, string $paymentMethod): array
```

```php
/**
 * Cancels a transaction
 *
 * @param string $transactionRef Transaction reference to cancel
 * @return array Ercaspay API Response
 */
public function cancelTransaction(string $transactionRef): array
```

## Testing

Run tests using:

```bash
composer test
```

---

## Changelog

Detailed changes for each release are documented in the [CHANGELOG](CHANGELOG.md).

---

## Contributing

We welcome contributions! Please see the [CONTRIBUTING](CONTRIBUTING.md) guide for details.

---

## Credits

-   **[Gabriel Ibenye](https://github.com/gabbyti)**

---

## License

This package is licensed under the [MIT License](LICENSE.md).
