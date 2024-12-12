<?php

use Decodeblock\Ercaspay\Ercaspay;
use Decodeblock\Ercaspay\Exceptions\IsNullException;

beforeEach(function () {
    $this->ercaspay = new Ercaspay;
    $this->callPrivateMethod($this->ercaspay, 'initializeClient', false);
});

it('throws exception when required fields are not provided', function () {
    expect(fn () => $this->ercaspay->initiateTransaction([]))
        ->toThrow(InvalidArgumentException::class);
});

it('generates a payment reference in UUID format', function () {
    $paymentRef = $this->ercaspay->generatePaymentReferenceUuid();
    expect($paymentRef)
        ->toBeString()
        ->toMatch('/[0-9a-fA-F\-]{36}/'); // Matches UUID format
});

it('throws an exception if a required paraeeter are missing', function () {
    // Act & Assert: Check each required field validation
    expect(fn () => $this->ercaspay->fetchTransactionStatus('       ', 'paymentReference', 'paymentMethod'))
        ->toThrow(IsNullException::class);

    expect(fn () => $this->ercaspay->verifyTransaction(''))
        ->toThrow(IsNullException::class);

    expect(fn () => $this->ercaspay->initiateUssdTransaction('transactionRef', ''))
        ->toThrow(IsNullException::class);
});
